<?php

namespace App\Http\Controllers;

use App\Models\CuentaBancariaSinergia;
use App\Models\PagoSuscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuscripcionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $comercio = $usuario->comercio;

        if (!$comercio) {
            abort(403);
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

        $pagos = PagoSuscripcion::where('id_suscripcion', $suscripcion->id_suscripcion)
            ->orderByDesc('id_pago_suscripcion')
            ->get();

        return view('suscripcion.pago', compact(
            'comercio',
            'suscripcion',
            'cuentas',
            'pagos'
        ));
    }

    public function subirComprobante(Request $request)
    {
        $usuario = Auth::user();
        $comercio = $usuario->comercio;

        if (!$comercio) {
            abort(403);
        }

        $suscripcion = $comercio->suscripciones()
            ->orderByDesc('id_suscripcion')
            ->first();

        if (!$suscripcion) {
            abort(404, 'No se encontró una suscripción para este comercio.');
        }

        $data = $request->validate([
            'id_cuenta' => ['required', 'integer', 'exists:cuenta_bancaria_sinergia,id_cuenta'],
            'comprobante' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
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

        $pagoPendiente = PagoSuscripcion::where('id_suscripcion', $suscripcion->id_suscripcion)
            ->whereIn('estado', ['pendiente', 'en_revision'])
            ->exists();

        if ($pagoPendiente) {
            return back()->with('error', 'Ya tenés un comprobante pendiente de revisión.');
        }

        $rutaComprobante = $request->file('comprobante')
            ->store('comprobantes-suscripcion', 'public');

        PagoSuscripcion::create([
            'id_suscripcion' => $suscripcion->id_suscripcion,
            'id_cuenta' => $cuenta->id_cuenta,
            'monto' => $suscripcion->monto,
            'fecha_pago' => now(),
            'periodo_desde' => now()->toDateString(),
            'periodo_hasta' => now()->addMonth()->toDateString(),
            'comprobante' => $rutaComprobante,
            'estado' => 'en_revision',
        ]);

        return redirect()
            ->route('suscripcion.pago')
            ->with('success', 'Comprobante enviado correctamente. Estamos verificando tu transferencia.');
    }
}
