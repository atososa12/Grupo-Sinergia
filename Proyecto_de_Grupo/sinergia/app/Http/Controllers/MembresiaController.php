<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;

class MembresiaController extends Controller
{
    /**
     * Mostrar listado
     */
    public function index()
    {
        $membresias = Membresia::all();

        return view('membresias.index', compact('membresias'));
    }

    /**
     * Mostrar formulario crear
     */
    public function create()
    {
        return view('membresias.create');
    }

    /**
     * Guardar nueva membresía
     */
    public function store(Request $request)
    {
        $request->validate([
            'Tipo' => 'required|max:50',
            'Precio' => 'required|numeric',
            'CantidadClases' => 'nullable|integer'
        ]);

        Membresia::create($request->all());

        return redirect()->route('membresias.index')
            ->with('success', 'Membresía creada correctamente');
    }

    /**
     * Mostrar formulario editar
     */
    public function edit($id)
    {
        $membresia = Membresia::findOrFail($id);

        return view('membresias.edit', compact('membresia'));
    }

    /**
     * Actualizar membresía
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Tipo' => 'required|max:50',
            'Precio' => 'required|numeric',
            'CantidadClases' => 'nullable|integer'
        ]);

        $membresia = Membresia::findOrFail($id);

        $membresia->update($request->all());

        return redirect()->route('membresias.index')
            ->with('success', 'Membresía actualizada');
    }

    /**
     * Eliminar membresía
     */
    public function destroy($id)
    {
        $membresia = Membresia::findOrFail($id);

        $membresia->delete();

        return redirect()->route('membresias.index')
            ->with('success', 'Membresía eliminada');
    }
}