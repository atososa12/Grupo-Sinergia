<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;

class InstructorController extends Controller
{
    public function index()
    {
        $instructores = Instructor::all();

        return view('instructores.index', compact('instructores'));
    }

    public function create()
    {
        return view('instructores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|max:100',
            'Especialidad' => 'nullable|max:100',
            'Email' => 'nullable|email',
            'Telefono' => 'nullable|max:20',
            'Activo' => 'required'
        ]);

        Instructor::create($request->all());

        return redirect()->route('instructores.index')
            ->with('success', 'Instructor creado');
    }

    public function edit($id)
    {
        $instructor = Instructor::findOrFail($id);

        return view('instructores.edit', compact('instructor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|max:100',
            'Especialidad' => 'nullable|max:100',
            'Email' => 'nullable|email',
            'Telefono' => 'nullable|max:20',
            'Activo' => 'required'
        ]);

        $instructor = Instructor::findOrFail($id);

        $instructor->update($request->all());

        return redirect()->route('instructores.index')
            ->with('success', 'Instructor actualizado');
    }

    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);

        $instructor->delete();

        return redirect()->route('instructores.index')
            ->with('success', 'Instructor eliminado');
    }
}