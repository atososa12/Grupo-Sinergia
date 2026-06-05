<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;
use App\Models\Instructor;

class ClaseController extends Controller
{
    public function index()
    {
        $clases = Clase::with('instructor')->get();

        return view('clases.index', compact('clases'));
    }

    public function create()
    {
        $instructores = Instructor::all();

        return view('clases.create', compact('instructores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|max:100',
            'Tipo' => 'required|max:50',
            'InstructorID' => 'required',
            'Dias' => 'required|max:100',
            'Horario' => 'required|max:50',
            'CupoMaximo' => 'required|integer'
        ]);

        Clase::create($request->all());

        return redirect()->route('clases.index')
            ->with('success', 'Clase creada');
    }

    public function edit($id)
    {
        $clase = Clase::findOrFail($id);

        $instructores = Instructor::all();

        return view('clases.edit', compact(
            'clase',
            'instructores'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|max:100',
            'Tipo' => 'required|max:50',
            'InstructorID' => 'required',
            'Dias' => 'required|max:100',
            'Horario' => 'required|max:50',
            'CupoMaximo' => 'required|integer'
        ]);

        $clase = Clase::findOrFail($id);

        $clase->update($request->all());

        return redirect()->route('clases.index')
            ->with('success', 'Clase actualizada');
    }

    public function destroy($id)
    {
        $clase = Clase::findOrFail($id);

        $clase->delete();

        return redirect()->route('clases.index')
            ->with('success', 'Clase eliminada');
    }
}