<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoPrecio;
use App\Models\TipoPrecio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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

        $pedidos = Pedido::with(['detalles.producto', 'estadoPedido'])
            ->where('id_comercio', $comercio->id_comercio)
            ->latest('id_pedido')
            ->get();

        return view('dashboard.index', compact('comercio', 'productos', 'pedidos'));
    }

    public function storeProducto(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        $usuario = Auth::user();
        $comercio = $usuario?->comercio;

        if (! $comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $producto = Producto::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'disponible' => true,
            'stock' => (int) $request->input('stock'),
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
    ]);

    $comercio = Auth::user()?->comercio;

    if (! $comercio) {
        abort(403, 'Este usuario no tiene un comercio asociado.');
    }

    $producto = Producto::where('id_comercio', $comercio->id_comercio)
        ->where('id_producto', $idProducto)
        ->firstOrFail();

    DB::transaction(function () use ($request, $producto) {
        $producto->update([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'stock' => (int) $request->input('stock'),
        ]);

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

    $pedido->id_estado = (int) $request->input('id_estado');
    $pedido->save();

    return redirect()
        ->route('dashboard')
        ->with('success', 'Estado del pedido actualizado.');
}

}
