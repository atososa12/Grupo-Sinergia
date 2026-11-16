<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\PagoSuscripcion;
use App\Models\Suscripcion;
use App\Models\NotificacionComercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $comercios = Comercio::with('suscripciones')->get();

        return view('admin.index', compact('pagos', 'comercios'));
    }

    public function generarRenovacion($idComercio)
    {
        $resultado = DB::transaction(function () use ($idComercio) {
            $comercio = Comercio::query()
                ->lockForUpdate()
                ->findOrFail($idComercio);

            $suscripcion = Suscripcion::query()
                ->where('id_comercio', $comercio->id_comercio)
                ->orderByDesc('id_suscripcion')
                ->lockForUpdate()
                ->first();

            if (!$suscripcion) {
                return 'Este comercio no tiene una suscripción registrada.';
            }

            $pagoPendiente = PagoSuscripcion::where(
                'id_suscripcion',
                $suscripcion->id_suscripcion
            )
                ->whereIn('estado', ['pendiente', 'en_revision'])
                ->exists();

            if ($pagoPendiente) {
                return 'Este comercio ya tiene un pago pendiente o en revisión.';
            }

            $hoy = Carbon::today();

            if ($suscripcion->fecha_vencimiento) {
                $vencimiento = Carbon::parse($suscripcion->fecha_vencimiento);

                if ($vencimiento->greaterThanOrEqualTo($hoy)) {
                    $fechaInicio = $vencimiento->copy()->addDay();
                } else {
                    $fechaInicio = $hoy->copy();
                }
            } else {
                $fechaInicio = $hoy->copy();
            }

            $fechaFin = $fechaInicio->copy()->addMonthNoOverflow();

            DB::table('pago_suscripcion')->insert([
                'id_suscripcion' => $suscripcion->id_suscripcion,
                'monto' => $suscripcion->monto_renovacion ?? $suscripcion->monto,
                'fecha_pago' => null,
                'periodo_desde' => $fechaInicio->toDateString(),
                'periodo_hasta' => $fechaFin->toDateString(),
                'comprobante' => null,
                'estado' => 'pendiente',
                'fecha_revision' => null,
                'observaciones' => null,
                'fecha_creacion' => now(),
                'id_cuenta' => null,
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
            ->with('success', 'Se generó el pago de renovación. El comercio ya puede adjuntar su comprobante.');
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

            if (!$pago->comprobante) {
                return 'No se puede aprobar un pago que todavía no tiene comprobante.';
            }

            $suscripcion = Suscripcion::query()
                ->lockForUpdate()
                ->findOrFail($pago->id_suscripcion);

            if (round((float) $pago->monto, 2) !== round((float) ($suscripcion->monto_renovacion ?? $suscripcion->monto), 2)) {
                return 'El monto del pago no coincide con el monto de la suscripción.';
            }

            $comercio = Comercio::query()
                ->lockForUpdate()
                ->findOrFail($suscripcion->id_comercio);

            $hoy = Carbon::today();

            if ($suscripcion->fecha_vencimiento) {
                $vencimientoActual = Carbon::parse($suscripcion->fecha_vencimiento);

                if ($vencimientoActual->greaterThanOrEqualTo($hoy)) {
                    $fechaInicio = $vencimientoActual->copy()->addDay();
                } else {
                    $fechaInicio = $hoy->copy();
                }
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
                $suscripcion->fecha_inicio = $fechaInicio->toDateString();
            }

            $suscripcion->fecha_vencimiento = $fechaVencimiento->toDateString();
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
