<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoPrecio;
use App\Models\TipoPrecio;
use App\Models\NotificacionComercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $comercio = $usuario?->comercio;

        if (! $comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $productos = Producto::with('precios.tipoPrecio')
            ->where('id_comercio', $comercio->id_comercio)
            ->latest('id_producto')
            ->get();

        $hoy = now()->toDateString();

        $pedidos = Pedido::with([
            'detalles.producto',
            'estadoPedido',
            'cliente',
        ])
            ->where('id_comercio', $comercio->id_comercio)
            ->whereDate('fecha', $hoy)
            ->latest('fecha')
            ->get();

        $pedidosAnteriores = Pedido::with([
            'detalles.producto',
            'estadoPedido',
            'cliente',
        ])
            ->where('id_comercio', $comercio->id_comercio)
            ->whereDate('fecha', '<', $hoy)
            ->latest('fecha')
            ->get();

        $pedidosPorDia = $pedidosAnteriores
            ->groupBy(fn ($pedido) => $pedido->fecha?->format('Y-m-d'))
            ->sortKeysDesc();

        $notificaciones = NotificacionComercio::where(
            'id_comercio',
            $comercio->id_comercio
        )
            ->orderByDesc('fecha_creacion')
            ->get();

        $notificacionesNuevas = $notificaciones
            ->where('leida', false);

        return view('dashboard.index', compact(
            'comercio',
            'productos',
            'pedidos',
            'pedidosPorDia',
            'notificaciones',
            'notificacionesNuevas'
        ));
    }

    public function marcarNotificacionLeida($idNotificacion)
    {
        $comercio = Auth::user()?->comercio;

        if (! $comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $notificacion = NotificacionComercio::where(
            'id_notificacion',
            $idNotificacion
        )
            ->where('id_comercio', $comercio->id_comercio)
            ->firstOrFail();

        $notificacion->leida = true;
        $notificacion->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Notificación marcada como leída.');
    }

    public function storeProducto(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'precio' => ['required', 'numeric', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $usuario = Auth::user();
        $comercio = $usuario?->comercio;

        if (! $comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreCarpeta = Str::slug($comercio->nombre);
            $nombreArchivo = time() . '_' . Str::slug($request->nombre) . '.' . $archivo->getClientOriginalExtension();

            $rutaImagen = $archivo->storeAs(
                "img/{$nombreCarpeta}",
                $nombreArchivo,
                'public'
            );
        }

        $producto = Producto::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'disponible' => true,
            'stock' => (int) $request->input('stock'),
            'imagen' => $rutaImagen,
            'id_comercio' => $comercio->id_comercio,
        ]);

        $tipoPrecio = TipoPrecio::where('activo', true)
            ->orderBy('id_tipo_precio')
            ->first();

        if (! $tipoPrecio) {
            $tipoPrecio = TipoPrecio::create([
                'nombre' => 'General',
                'descripcion' => 'Precio base',
                'activo' => true,
            ]);
        }

        ProductoPrecio::create([
            'id_producto' => $producto->id_producto,
            'id_tipo_precio' => $tipoPrecio->id_tipo_precio,
            'precio' => $request->input('precio'),
        ]);

        return back()->with('success', 'Producto agregado correctamente.');
    }

    public function updateProducto(Request $request, $idProducto)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'precio' => ['required', 'numeric', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $comercio = Auth::user()?->comercio;

        if (! $comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $producto = Producto::where('id_comercio', $comercio->id_comercio)
            ->where('id_producto', $idProducto)
            ->firstOrFail();

        $imagenAnterior = $producto->imagen;
        $nuevaImagen = null;

        DB::transaction(function () use ($request, $producto, $comercio, &$nuevaImagen) {

            $datosActualizar = [
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'stock' => (int) $request->input('stock'),
            ];

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreCarpeta = Str::slug($comercio->nombre);
                $nombreArchivo = time() . '_' . Str::slug($request->input('nombre')) . '.' . $archivo->getClientOriginalExtension();

                $nuevaImagen = $archivo->storeAs(
                    "img/{$nombreCarpeta}",
                    $nombreArchivo,
                    'public'
                );

                $datosActualizar['imagen'] = $nuevaImagen;
            }

            $producto->update($datosActualizar);

            $precioProducto = $producto->precios()->first();

            if ($precioProducto) {
                $precioProducto->update([
                    'precio' => $request->input('precio'),
                ]);
            } else {
                $tipoPrecio = TipoPrecio::where('activo', true)
                    ->orderBy('id_tipo_precio')
                    ->first();

                if (! $tipoPrecio) {
                    $tipoPrecio = TipoPrecio::create([
                        'nombre' => 'General',
                        'descripcion' => 'Precio base',
                        'activo' => true,
                    ]);
                }

                ProductoPrecio::create([
                    'id_producto' => $producto->id_producto,
                    'id_tipo_precio' => $tipoPrecio->id_tipo_precio,
                    'precio' => $request->input('precio'),
                ]);
            }
        });

        if ($nuevaImagen && $imagenAnterior && $imagenAnterior !== $nuevaImagen) {
            $this->eliminarImagen($imagenAnterior);
        }

        return back()->with('success', 'Producto actualizado correctamente.');
    }

    public function destroyProducto($idProducto)
    {
        $comercio = Auth::user()?->comercio;

        if (! $comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $producto = Producto::where('id_comercio', $comercio->id_comercio)
            ->where('id_producto', $idProducto)
            ->firstOrFail();

        $rutaImagen = $producto->imagen;

        try {
            DB::transaction(function () use ($producto) {
                $producto->precios()->delete();
                $producto->delete();
            });

        } catch (QueryException $e) {
            return back()->with(
                'error',
                'No se pudo eliminar el producto. Puede estar asociado a pedidos existentes.'
            );
        }

        $this->eliminarImagen($rutaImagen);

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    public function updatePedidoEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'id_estado' => ['required', 'integer', 'exists:estado_pedido,id_estado'],
        ]);

        $comercio = Auth::user()?->comercio;

        if (! $comercio || (int) $pedido->id_comercio !== (int) $comercio->id_comercio) {
            abort(403, 'No tienes permisos para cambiar este pedido.');
        }

        $nuevoEstado = (int) $request->input('id_estado');

        DB::transaction(function () use ($pedido, $comercio, $nuevoEstado) {

            $pedidoActual = Pedido::where('id_pedido', $pedido->id_pedido)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $pedidoActual->id_comercio !== (int) $comercio->id_comercio) {
                abort(403, 'No tienes permisos para cambiar este pedido.');
            }

            $estadoActual = (int) $pedidoActual->id_estado;

            if ($estadoActual === 5 && $nuevoEstado !== 5) {
                throw ValidationException::withMessages([
                    'id_estado' => 'Un pedido cancelado no puede volver a otro estado.',
                ]);
            }

            if ($estadoActual === 4 && $nuevoEstado === 5) {
                throw ValidationException::withMessages([
                    'id_estado' => 'Un pedido entregado no puede ser cancelado.',
                ]);
            }

            if ($estadoActual === $nuevoEstado) {
                return;
            }

            if ($nuevoEstado === 5) {
                $pedidoActual->load('detalles');

                foreach ($pedidoActual->detalles as $detalle) {
                    $producto = Producto::where(
                        'id_producto',
                        $detalle->id_producto
                    )
                        ->where('id_comercio', $comercio->id_comercio)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($producto->stock !== null) {
                        $producto->increment('stock', (int) $detalle->cantidad);
                    }
                }
            }

            $pedidoActual->id_estado = $nuevoEstado;
            $pedidoActual->save();
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Estado del pedido actualizado correctamente.');
    }

    private function eliminarImagen(?string $rutaImagen)
    {
        if (! $rutaImagen) {
            return;
        }

        Storage::disk('public')->delete($rutaImagen);

        $rutaPublica = public_path($rutaImagen);

        if (is_file($rutaPublica)) {
            @unlink($rutaPublica);
        }
    }
}
