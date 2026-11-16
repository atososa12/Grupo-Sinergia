<?php

namespace App\Http\Controllers;

use App\Models\CuentaBancariaSinergia;
use App\Models\PagoSuscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuscripcionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $comercio = $usuario?->comercio;

        if (!$comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $suscripcion = $comercio->suscripciones()
            ->orderByDesc('id_suscripcion')
            ->first();

        if (!$suscripcion) {
            abort(404, 'No se encontró una suscripción para este comercio.');
        }

        $cuentas = CuentaBancariaSinergia::where('activa', true)
            ->orderBy('banco')
            ->get();

        $pagos = PagoSuscripcion::where(
            'id_suscripcion',
            $suscripcion->id_suscripcion
        )
            ->orderByDesc('id_pago_suscripcion')
            ->get();

        $pagoPendiente = $pagos->first(function ($pago) {
            return $pago->estado === 'pendiente' && !$pago->comprobante;
        });

        $pagoEnRevision = $pagos->first(function ($pago) {
            return $pago->estado === 'en_revision';
        });

        return view('suscripcion.pago', compact(
            'comercio',
            'suscripcion',
            'cuentas',
            'pagos',
            'pagoPendiente',
            'pagoEnRevision'
        ));
    }

    public function subirComprobante(Request $request)
    {
        $usuario = Auth::user();
        $comercio = $usuario?->comercio;

        if (!$comercio) {
            abort(403, 'Este usuario no tiene un comercio asociado.');
        }

        $suscripcion = $comercio->suscripciones()
            ->orderByDesc('id_suscripcion')
            ->first();

        if (!$suscripcion) {
            abort(404, 'No se encontró una suscripción para este comercio.');
        }

        $data = $request->validate([
            'id_pago_suscripcion' => ['required', 'integer'],
            'id_cuenta' => ['required', 'integer', 'exists:cuenta_bancaria_sinergia,id_cuenta'],
            'comprobante' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'id_pago_suscripcion.required' => 'No se encontró el pago que debés abonar.',
            'id_cuenta.required' => 'Seleccioná la cuenta bancaria a la que realizaste la transferencia.',
            'id_cuenta.exists' => 'La cuenta bancaria seleccionada no es válida.',
            'comprobante.required' => 'Tenés que adjuntar el comprobante de transferencia.',
            'comprobante.mimes' => 'El comprobante debe ser una imagen JPG, PNG o un PDF.',
            'comprobante.max' => 'El comprobante no puede superar los 5 MB.',
        ]);

        $cuenta = CuentaBancariaSinergia::where('id_cuenta', $data['id_cuenta'])
            ->where('activa', true)
            ->first();

        if (!$cuenta) {
            return back()->withErrors([
                'id_cuenta' => 'La cuenta bancaria seleccionada no está disponible.',
            ])->withInput();
        }

        $pago = PagoSuscripcion::where(
            'id_pago_suscripcion',
            $data['id_pago_suscripcion']
        )
            ->where('id_suscripcion', $suscripcion->id_suscripcion)
            ->first();

        if (!$pago) {
            abort(404, 'El pago seleccionado no pertenece a tu comercio.');
        }

        if ($pago->estado !== 'pendiente' || $pago->comprobante) {
            return back()->with(
                'error',
                'Este pago ya fue enviado o procesado. Actualizá la página para consultar su estado.'
            );
        }

        $rutaComprobante = $request->file('comprobante')
            ->store('comprobantes-suscripcion', 'public');

        try {
            DB::transaction(function () use ($pago, $cuenta, $rutaComprobante) {
                $pagoActualizado = PagoSuscripcion::where(
                    'id_pago_suscripcion',
                    $pago->id_pago_suscripcion
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($pagoActualizado->estado !== 'pendiente' || $pagoActualizado->comprobante) {
                    throw new \RuntimeException('El pago ya fue enviado o procesado.');
                }

                $pagoActualizado->id_cuenta = $cuenta->id_cuenta;
                $pagoActualizado->comprobante = $rutaComprobante;
                $pagoActualizado->fecha_pago = now();
                $pagoActualizado->estado = 'en_revision';
                $pagoActualizado->save();
            });
        } catch (\RuntimeException $e) {
            return back()->with(
                'error',
                'El pago ya fue enviado o procesado. Actualizá la página.'
            );
        }

        return redirect()
            ->route('suscripcion.pago')
            ->with('success', 'Comprobante enviado correctamente. Estamos verificando tu transferencia.');
    }
}