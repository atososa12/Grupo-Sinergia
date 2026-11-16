<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\User;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'activo' => false,
            ]);

            $usuario = User::create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'contrasena' => Hash::make($data['password']),
                'telefono' => $data['telefono'] ?? null,
                'rol' => 'dueño',
                'id_comercio' => $comercio->id_comercio,
            ]);

            Suscripcion::create([
                'id_comercio' => $comercio->id_comercio,
                'fecha_inicio' => null,
                'fecha_vencimiento' => null,
                'monto' => 5000,
                'periodicidad' => 'mensual',
                'estado' => 'pendiente_pago',
                'meses_promocion' => 12,
                'monto_renovacion' => null,
            ]);

            return $usuario;
        });

        Auth::login($usuario);

        $request->session()->regenerate();

        return redirect()->route('suscripcion.pago');
    }
}
