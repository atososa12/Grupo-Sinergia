<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Socio;
use App\Models\Membresia;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with('socio', 'membresia')->get();

        return view('inscripciones.index', compact('inscripciones'));
    }

    public function create()
    {
        $socios = Socio::all();
        $membresias = Membresia::all();

        return view('inscripciones.create', compact('socios', 'membresias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'SocioID' => 'required',
            'MembresiaID' => 'required',
            'FechaInicio' => 'required|date',
            'FechaVencimiento' => 'required|date',
            'Estado' => 'required'
        ]);

        $inscripcionActiva = Inscripcion::where('SocioID', $request->SocioID)
            ->where('Estado', 'activa')
            ->first();

        if ($inscripcionActiva) {
            return back()
                ->withInput()
                ->with('error', 'El socio ya tiene una inscripción activa.');
        }

        Inscripcion::create($request->all());

        return redirect()->route('inscripciones.index')
            ->with('success', 'Inscripción creada correctamente');
    }

    public function edit($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        $socios = Socio::all();
        $membresias = Membresia::all();

        return view('inscripciones.edit', compact(
            'inscripcion',
            'socios',
            'membresias'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'SocioID' => 'required',
            'MembresiaID' => 'required',
            'FechaInicio' => 'required|date',
            'FechaVencimiento' => 'required|date',
            'Estado' => 'required'
        ]);

        $inscripcion = Inscripcion::findOrFail($id);

        $inscripcion->update($request->all());

        return redirect()->route('inscripciones.index')
            ->with('success', 'Inscripción actualizada');
    }

    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        $inscripcion->delete();

        return redirect()->route('inscripciones.index')
            ->with('success', 'Inscripción eliminada');
    }
}