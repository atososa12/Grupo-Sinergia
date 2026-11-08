<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClienteNotificacionController extends Controller
{
    public function nuevas()
    {
        $idCliente = Auth::guard('clientes')->id();

        $notificaciones = DB::table('notificacion_cliente')
            ->where('id_cliente', $idCliente)
            ->where('leida', false)
            ->orderBy('fecha', 'asc')
            ->get([
                'id_notificacion',
                'mensaje',
                'fecha',
            ]);

        if ($notificaciones->isNotEmpty()) {
            DB::table('notificacion_cliente')
                ->whereIn(
                    'id_notificacion',
                    $notificaciones->pluck('id_notificacion')
                )
                ->where('id_cliente', $idCliente)
                ->update(['leida' => true]);
        }

        return response()->json($notificaciones);
    }
}