<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClientePedidoController extends Controller
{
    public function index(Request $request)
    {
        $idCliente = Auth::guard('clientes')->id();

        $referer = $request->headers->get('referer');

        if ($referer) {
            $hostReferer = parse_url($referer, PHP_URL_HOST);
            $hostActual = $request->getHost();
            $pathReferer = parse_url($referer, PHP_URL_PATH);

            if ($hostReferer === $hostActual &&
                preg_match('#^/comercio/([0-9]+)$#', $pathReferer, $coincidencias)) {

                session([
                    'cliente_pedidos_volver' => route('comercio.show', $coincidencias[1])
                ]);
            }
        }

        $urlVolver = session(
            'cliente_pedidos_volver',
            route('comercios.index')
        );

        $pedidos = Pedido::with([
            'comercio',
            'detalles.producto'
        ])
            ->where('id_cliente', $idCliente)
            ->orderByDesc('fecha')
            ->orderByDesc('id_pedido')
            ->get();

        return view('cliente.pedidos', compact(
            'pedidos',
            'urlVolver'
        ));
    }

    public function cancelar(int $idPedido)
    {
        $idCliente = Auth::guard('clientes')->id();

        DB::transaction(function () use ($idPedido, $idCliente) {

            $pedido = Pedido::where('id_pedido', $idPedido)
                ->where('id_cliente', $idCliente)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array((int) $pedido->id_estado, [1, 6], true)) {
                throw ValidationException::withMessages([
                    'pedido' => 'Este pedido ya no se puede cancelar porque su estado cambió.'
                ]);
            }

            $detalles = DetallePedido::where(
                'id_pedido',
                $pedido->id_pedido
            )->get();

            foreach ($detalles as $detalle) {

                if (!$detalle->id_producto || !$detalle->cantidad) {
                    continue;
                }

                $producto = Producto::where(
                    'id_producto',
                    $detalle->id_producto
                )
                    ->where('id_comercio', $pedido->id_comercio)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($producto->stock !== null) {
                    $producto->stock += (int) $detalle->cantidad;
                    $producto->save();
                }
            }

            $pedido->id_estado = 5;
            $pedido->save();
        });

        return redirect()
            ->route('cliente.pedidos')
            ->with('success', 'Tu pedido fue cancelado correctamente.');
    }
}
