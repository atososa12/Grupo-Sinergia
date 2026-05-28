<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socio;

class SocioController extends Controller
{
    public function index()
    {
        $socios = Socio::all();

        return view('socios.index', compact('socios'));
    }

    public function create()
    {
        return view('socios.create');
    }

    public function store(Request $request)
    {
        Socio::create([
            'Nombre' => $request->Nombre,
            'Documento' => $request->Documento,
            'Email' => $request->Email,
            'Telefono' => $request->Telefono,
            'FechaNacimiento' => $request->FechaNacimiento,
            'FechaAlta' => $request->FechaAlta,
            'Activo' => $request->Activo
        ]);

        return redirect()->route('socios.index');
    }

    public function edit($id)
    {
        $socio = Socio::findOrFail($id);

        return view('socios.edit', compact('socio'));
    }

    public function update(Request $request, $id)
    {
        $socio = Socio::findOrFail($id);

        $socio->update([
            'Nombre' => $request->Nombre,
            'Documento' => $request->Documento,
            'Email' => $request->Email,
            'Telefono' => $request->Telefono,
            'FechaNacimiento' => $request->FechaNacimiento,
            'FechaAlta' => $request->FechaAlta,
            'Activo' => $request->Activo
        ]);

        return redirect()->route('socios.index');
    }

    public function destroy($id)
    {
        $socio = Socio::findOrFail($id);

        $socio->delete();

        return redirect()->route('socios.index');
    }
}