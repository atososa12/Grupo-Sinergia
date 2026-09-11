<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistroNegocioController extends Controller
{
    public function create()
    {
        return view('auth.registro-negocio');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_comercio' => ['required', 'string', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email_comercio' => ['nullable', 'email', 'max:150'],
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:usuario,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = DB::transaction(function () use ($data) {
            $comercio = Comercio::create([
                'nombre' => $data['nombre_comercio'],
                'direccion' => $data['direccion'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'email' => $data['email_comercio'] ?? null,
                'activo' => true,
            ]);

            $usuario = User::create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'contrasena' => bcrypt($data['password']),
                'telefono' => $data['telefono'] ?? null,
                'rol' => 'dueño',
                'id_comercio' => $comercio->id_comercio,
            ]);

            return $usuario;
        });

        Auth::login($usuario);

        return redirect()->route('dashboard');
    }
}
