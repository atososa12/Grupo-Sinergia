<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoPrecio;
use App\Models\TipoPrecio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function updatePedidoEstado(Request $request, Pedido $pedido)
    {
        $usuario = Auth::user();

        if ($pedido->id_comercio !== $usuario->id_comercio) {
            abort(403, 'No tienes permisos para cambiar este pedido.');
        }

        $request->validate([
            'id_estado' => ['required', 'integer', 'exists:estado_pedido,id_estado'],
        ]);

        $pedido->update(['id_estado' => $request->input('id_estado')]);

        return back()->with('success', 'Estado del pedido actualizado.');
    }
}
