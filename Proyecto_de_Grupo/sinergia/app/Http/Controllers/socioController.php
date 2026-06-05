<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socio;
use Illuminate\Validation\Rule;

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
        $request->validate(
            [
                'Nombre' => 'required|max:100',
                'Documento' => 'required|unique:Socios,Documento',
                'Email' => 'required|email|unique:Socios,Email',
                'Telefono' => 'required',
                'FechaNacimiento' => 'required|date',
            ],
            [
                'Nombre.required' => 'Debe ingresar el nombre.',
                'Nombre.max' => 'El nombre no puede superar los 100 caracteres.',

                'Documento.required' => 'Debe ingresar el documento.',
                'Documento.unique' => 'Ya existe un socio con ese documento.',

                'Email.required' => 'Debe ingresar el correo electrónico.',
                'Email.email' => 'El correo electrónico no es válido.',
                'Email.unique' => 'Ya existe un socio con ese correo electrónico.',

                'Telefono.required' => 'Debe ingresar el teléfono.',

                'FechaNacimiento.required' => 'Debe ingresar la fecha de nacimiento.',
                'FechaNacimiento.date' => 'La fecha de nacimiento no es válida.',
            ]
        );

        Socio::create([
            'Nombre' => $request->Nombre,
            'Documento' => $request->Documento,
            'Email' => $request->Email,
            'Telefono' => $request->Telefono,
            'FechaNacimiento' => $request->FechaNacimiento,
            'FechaAlta' => date('Y-m-d'),
            'Activo' => $request->Activo
        ]);

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio creado correctamente');
    }

    public function edit($id)
    {
        $socio = Socio::findOrFail($id);

        return view('socios.edit', compact('socio'));
    }

    public function update(Request $request, $id)
    {
        $socio = Socio::findOrFail($id);

        $request->validate(
            [
                'Nombre' => 'required|max:100',
                'Documento' => [
                    'required',
                    Rule::unique('Socios', 'Documento')->ignore($id, 'SocioID')
                ],
                'Email' => [
                    'required',
                    'email',
                    Rule::unique('Socios', 'Email')->ignore($id, 'SocioID')
                ],
                'Telefono' => 'required',
                'FechaNacimiento' => 'required|date',
            ],
            [
                'Nombre.required' => 'Debe ingresar el nombre.',
                'Nombre.max' => 'El nombre no puede superar los 100 caracteres.',

                'Documento.required' => 'Debe ingresar el documento.',
                'Documento.unique' => 'Ya existe un socio con ese documento.',

                'Email.required' => 'Debe ingresar el correo electrónico.',
                'Email.email' => 'El correo electrónico no es válido.',
                'Email.unique' => 'Ya existe un socio con ese correo electrónico.',

                'Telefono.required' => 'Debe ingresar el teléfono.',

                'FechaNacimiento.required' => 'Debe ingresar la fecha de nacimiento.',
                'FechaNacimiento.date' => 'La fecha de nacimiento no es válida.',
            ]
        );

        $socio->update([
            'Nombre' => $request->Nombre,
            'Documento' => $request->Documento,
            'Email' => $request->Email,
            'Telefono' => $request->Telefono,
            'FechaNacimiento' => $request->FechaNacimiento,
            'FechaAlta' => $request->FechaAlta,
            'Activo' => $request->Activo
        ]);

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio actualizado correctamente');
    }

    public function destroy($id)
    {
        $socio = Socio::findOrFail($id);

        $socio->delete();

        return redirect()
            ->route('socios.index')
            ->with('success', 'Socio eliminado correctamente');
    }
}