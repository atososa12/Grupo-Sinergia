<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\PagoSuscripcion;
use App\Models\Suscripcion;
use App\Models\NotificacionComercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $pagos = PagoSuscripcion::with([
            'suscripcion.comercio',
            'cuenta'
        ])
        ->orderByRaw("
            CASE
                WHEN estado = 'en_revision' THEN 0
                WHEN estado = 'pendiente' THEN 1
                ELSE 2
            END
        ")
        ->orderByDesc('id_pago_suscripcion')
        ->get();

        return view('admin.index', compact('pagos'));
    }

    public function aprobarPago(PagoSuscripcion $pago)
    {
        $resultado = DB::transaction(function () use ($pago) {
            $pago = PagoSuscripcion::query()
                ->lockForUpdate()
                ->findOrFail($pago->id_pago_suscripcion);

            if (!in_array($pago->estado, ['pendiente', 'en_revision'])) {
                return 'Este pago ya fue procesado.';
            }

            $suscripcion = Suscripcion::query()
                ->lockForUpdate()
                ->findOrFail($pago->id_suscripcion);

            if (round((float) $pago->monto, 2) !== round((float) $suscripcion->monto, 2)) {
                return 'El monto del pago no coincide con el monto de la suscripción.';
            }

            $comercio = Comercio::query()
                ->lockForUpdate()
                ->findOrFail($suscripcion->id_comercio);

            $hoy = today();

            if (
                $suscripcion->fecha_vencimiento &&
                $suscripcion->fecha_vencimiento->greaterThanOrEqualTo($hoy)
            ) {
                $fechaInicio = $suscripcion->fecha_vencimiento->copy()->addDay();
            } else {
                $fechaInicio = $hoy->copy();
            }

            $fechaVencimiento = $fechaInicio->copy()->addMonthNoOverflow();

            $pago->estado = 'aprobado';
            $pago->fecha_pago = now();
            $pago->fecha_revision = now();
            $pago->periodo_desde = $fechaInicio->toDateString();
            $pago->periodo_hasta = $fechaVencimiento->toDateString();
            $pago->save();

            if (!$suscripcion->fecha_inicio) {
                $suscripcion->fecha_inicio = $fechaInicio;
            }

            $suscripcion->fecha_vencimiento = $fechaVencimiento;
            $suscripcion->estado = 'activa';
            $suscripcion->save();

            $comercio->activo = true;
            $comercio->save();

            NotificacionComercio::create([
                'id_comercio' => $comercio->id_comercio,
                'id_pago_suscripcion' => $pago->id_pago_suscripcion,
                'titulo' => '¡Pago aprobado!',
                'tipo' => 'pago_aprobado',
                'mensaje' => 'Tu transferencia fue verificada. Tu suscripción está activa hasta el ' . $fechaVencimiento->format('d/m/Y') . '.',
                'leida' => false,
                'fecha_creacion' => now(),
            ]);

            return null;
        });

        if ($resultado !== null) {
            return redirect()
                ->route('admin.index')
                ->with('error', $resultado);
        }

        return redirect()
            ->route('admin.index')
            ->with('success', 'El pago fue aprobado y el comercio quedó activo.');
    }

    public function rechazarPago(Request $request, PagoSuscripcion $pago)
    {
        $datos = $request->validate([
            'observaciones' => ['required', 'string', 'max:500'],
        ]);

        $resultado = DB::transaction(function () use ($pago, $datos) {
            $pago = PagoSuscripcion::query()
                ->lockForUpdate()
                ->findOrFail($pago->id_pago_suscripcion);

            if (!in_array($pago->estado, ['pendiente', 'en_revision'])) {
                return 'Este pago ya fue procesado.';
            }

            $suscripcion = Suscripcion::query()
                ->lockForUpdate()
                ->findOrFail($pago->id_suscripcion);

            $pago->estado = 'rechazado';
            $pago->fecha_revision = now();
            $pago->observaciones = trim($datos['observaciones']);
            $pago->save();

            NotificacionComercio::create([
                'id_comercio' => $suscripcion->id_comercio,
                'id_pago_suscripcion' => $pago->id_pago_suscripcion,
                'titulo' => 'Pago rechazado',
                'tipo' => 'pago_rechazado',
                'mensaje' => 'Tu comprobante fue rechazado. Motivo: ' . trim($datos['observaciones']) . '. Podés enviar un nuevo comprobante.',
                'leida' => false,
                'fecha_creacion' => now(),
            ]);

            return null;
        });

        if ($resultado !== null) {
            return redirect()
                ->route('admin.index')
                ->with('error', $resultado);
        }

        return redirect()
            ->route('admin.index')
            ->with('success', 'El comprobante fue rechazado correctamente.');
    }
}
