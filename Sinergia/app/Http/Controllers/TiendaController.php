<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiendaController extends Controller
{
    public function show(string $idComercio)
    {
        $comercio = Comercio::with('productos.precios.tipoPrecio')->findOrFail($idComercio);

        return view('storefront.show', compact('comercio'));
    }

    public function storePedido(Request $request, string $idComercio)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'tipo_entrega' => ['nullable', 'integer'],
            'metodo_pago' => ['nullable', 'integer'],
            'items' => ['required', 'string'],
        ]);

        $comercio = Comercio::findOrFail($idComercio);

        $items = json_decode($request->input('items'), true);
        if (! is_array($items) || empty($items)) {
            return back()->withErrors(['items' => 'Debes seleccionar al menos un producto.']);
        }

        $pedido = DB::transaction(function () use ($request, $comercio, $items) {
            $pedido = Pedido::create([
                'id_usuario' => null,
                'fecha' => now(),
                'id_tipo_entrega' => $request->input('tipo_entrega'),
                'direccion_entrega' => $request->input('direccion'),
                'id_estado' => 1,
                'total' => 0,
                'id_comercio' => $comercio->id_comercio,
            ]);

            $total = 0;
            foreach ($items as $item) {
                $producto = Producto::with('precios')->findOrFail($item['id_producto']);

                if ((int) $producto->id_comercio !== (int) $comercio->id_comercio) {
                    abort(422, 'El producto no pertenece a este comercio.');
                }

                $cantidad = max(1, (int) ($item['cantidad'] ?? 1));
                $precio = (float) ($producto->precios->first()->precio ?? 0);
                $subtotal = round($precio * $cantidad, 2);

                DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_producto' => $producto->id_producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $pedido->update(['total' => $total]);

            return $pedido;
        });

        return redirect()->route('comercio.show', $comercio->id_comercio)
            ->with('success', 'Pedido enviado correctamente. El negocio recibirá tu solicitud.');
    }
}
