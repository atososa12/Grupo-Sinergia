<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Inscripcion;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('inscripcion.socio')->get();

        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $inscripciones = Inscripcion::with('socio')->get();

        return view('pagos.create', compact('inscripciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'InscripcionID' => 'required',
            'Monto' => 'required|numeric',
            'FechaPago' => 'required|date',
            'MedioPago' => 'required'
        ]);

        Pago::create($request->all());

        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado');
    }

    public function edit($id)
    {
        $pago = Pago::findOrFail($id);

        $inscripciones = Inscripcion::with('socio')->get();

        return view('pagos.edit', compact(
            'pago',
            'inscripciones'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'InscripcionID' => 'required',
            'Monto' => 'required|numeric',
            'FechaPago' => 'required|date',
            'MedioPago' => 'required'
        ]);

        $pago = Pago::findOrFail($id);

        $pago->update($request->all());

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado');
    }
}