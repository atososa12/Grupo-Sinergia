<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\DetallePedido;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoPrecio;
use App\Models\TipoEntrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TiendaController extends Controller
{
    public function comercios()
    {
        $comercios = Comercio::where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('storefront.comercios', compact('comercios'));
    }

    public function show(string $idComercio)
    {
        $comercio = Comercio::with([
            'productos' => function ($query) {
                $query->where('disponible', 1)
                    ->where(function ($q) {
                        $q->whereNull('stock')
                            ->orWhere('stock', '>', 0);
                    });
            },
            'productos.precios' => function ($query) {
                $query->where('id_tipo_precio', 1);
            },
        ])->findOrFail($idComercio);

        if (!$comercio->activo) {
            abort(403, 'Este comercio no se encuentra activo.');
        }

        $cliente = Auth::guard('clientes')->user();

        $ultimaDireccion = null;

        if ($cliente) {
            $ultimaDireccion = Pedido::where('id_cliente', $cliente->id_cliente)
                ->where('id_comercio', $comercio->id_comercio)
                ->orderByDesc('fecha')
                ->orderByDesc('id_pedido')
                ->value('direccion_entrega');
        }

        return view('storefront.show', compact(
            'comercio',
            'cliente',
            'ultimaDireccion'
        ));
    }

    public function storePedido(Request $request, string $idComercio)
    {
        if (!Auth::guard('clientes')->check()) {
            session([
                'url.intended' => route('comercio.show', $idComercio),
            ]);

            return redirect()
                ->route('cliente.login', ['comercio' => $idComercio])
                ->with('info', 'Para realizar un pedido primero debes iniciar sesión.');
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'tipo_entrega' => [
                'nullable',
                'integer',
                'exists:tipo_entrega,id_tipo_entrega',
            ],
            'metodo_pago' => [
                'nullable',
                'integer',
                'exists:metodo_pago,id_metodo_pago',
            ],
            'items' => ['required', 'string', 'max:20000'],
        ]);

        $items = json_decode($data['items'], true);

        if (!is_array($items) || empty($items)) {
            throw ValidationException::withMessages([
                'items' => 'Debes seleccionar al menos un producto.',
            ]);
        }

        $validator = Validator::make(
            ['items' => $items],
            [
                'items' => ['required', 'array', 'min:1', 'max:50'],
                'items.*.id_producto' => [
                    'required',
                    'integer',
                    'distinct',
                    'min:1',
                ],
                'items.*.cantidad' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:1000',
                ],
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $comercio = Comercio::findOrFail($idComercio);

        if (!$comercio->activo) {
            abort(403, 'Este comercio no se encuentra activo.');
        }

        if (!empty($data['tipo_entrega'])) {
            $tipoEntrega = TipoEntrega::where(
                'id_tipo_entrega',
                $data['tipo_entrega']
            )->where('activo', true)->first();

            if (!$tipoEntrega) {
                throw ValidationException::withMessages([
                    'tipo_entrega' => 'El tipo de entrega seleccionado no está disponible.',
                ]);
            }
        }

        if (!empty($data['metodo_pago'])) {
            $metodoPago = MetodoPago::where(
                'id_metodo_pago',
                $data['metodo_pago']
            )->where('activo', true)->first();

            if (!$metodoPago) {
                throw ValidationException::withMessages([
                    'metodo_pago' => 'El método de pago seleccionado no está disponible.',
                ]);
            }
        }

        $clienteId = Auth::guard('clientes')->id();

        $pedido = DB::transaction(function () use ($data, $items, $comercio, $clienteId) {

            $pedido = Pedido::create([
                'id_usuario' => null,
                'fecha' => now(),
                'id_tipo_entrega' => $data['tipo_entrega'] ?? null,
                'direccion_entrega' => $data['direccion'] ?? null,
                'id_estado' => 1,
                'total' => 0,
                'id_comercio' => $comercio->id_comercio,
                'id_cliente' => $clienteId,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $cantidad = (int) $item['cantidad'];

                $producto = Producto::where(
                    'id_producto',
                    $item['id_producto']
                )
                    ->where('id_comercio', $comercio->id_comercio)
                    ->lockForUpdate()
                    ->first();

                if (!$producto) {
                    throw ValidationException::withMessages([
                        'items' => 'Uno de los productos seleccionados no pertenece a este comercio.',
                    ]);
                }

                if (!$producto->disponible) {
                    throw ValidationException::withMessages([
                        'items' => 'El producto ' . $producto->nombre . ' ya no está disponible.',
                    ]);
                }

                if ($producto->stock !== null && $producto->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'items' => 'No hay stock suficiente de ' . $producto->nombre . '. Disponible: ' . $producto->stock . '.',
                    ]);
                }

                $precioProducto = ProductoPrecio::where(
                    'id_producto',
                    $producto->id_producto
                )
                    ->where('id_tipo_precio', 1)
                    ->first();

                if (!$precioProducto) {
                    throw ValidationException::withMessages([
                        'items' => 'El producto ' . $producto->nombre . ' no tiene un precio configurado.',
                    ]);
                }

                $precio = (float) $precioProducto->precio;

                if ($precio < 0) {
                    throw ValidationException::withMessages([
                        'items' => 'El precio del producto no es válido.',
                    ]);
                }

                $subtotal = round($precio * $cantidad, 2);

                DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_producto' => $producto->id_producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);

                if ($producto->stock !== null) {
                    $producto->stock -= $cantidad;
                    $producto->save();
                }

                $total += $subtotal;
            }

            $total = round($total, 2);

            $pedido->update([
                'total' => $total,
            ]);

            Pago::create([
                'id_pedido' => $pedido->id_pedido,
                'id_metodo_pago' => $data['metodo_pago'] ?? null,
                'monto' => $total,
                'fecha_pago' => now(),
                'id_estado_pago' => 1,
            ]);

            return $pedido;
        });

        $relacionExiste = DB::connection('clientes')
            ->table('comercio_cliente')
            ->where('id_cliente', $clienteId)
            ->where('id_comercio', $comercio->id_comercio)
            ->exists();

        if (!$relacionExiste) {
            DB::connection('clientes')
                ->table('comercio_cliente')
                ->insert([
                    'id_cliente' => $clienteId,
                    'id_comercio' => $comercio->id_comercio,
                ]);
        }

        return redirect()
            ->route('comercio.show', $comercio->id_comercio)
            ->with('success', 'Pedido enviado correctamente. El negocio recibirá tu solicitud.');
    }
}
