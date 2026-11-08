<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Comercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClienteAuthController extends Controller
{
    public function mostrarRegistro(Request $request)
    {
        $this->guardarDestinoComercio($request);

        return view('auth.cliente-registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (Cliente::where('email', $datos['email'])->exists()) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'email' => 'Este correo electrónico ya está registrado.',
                ]);
        }

        $cliente = Cliente::create([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'telefono' => $datos['telefono'],
            'email' => $datos['email'],
            'contrasena' => Hash::make($datos['password']),
        ]);

        Auth::guard('clientes')->login($cliente);

        $request->session()->regenerate();

        return redirect()
            ->intended('/')
            ->with('success', 'Tu cuenta fue creada correctamente.');
    }

    public function mostrarLogin(Request $request)
    {
        $this->guardarDestinoComercio($request);

        return view('auth.cliente-login');
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('clientes')->attempt($datos)) {
            $request->session()->regenerate();

            return redirect()
                ->intended('/')
                ->with('success', 'Iniciaste sesión correctamente.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'El correo electrónico o la contraseña son incorrectos.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('clientes')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Cerraste sesión correctamente.');
    }

    private function guardarDestinoComercio(Request $request)
    {
        $idComercio = $request->query('comercio');

        if ($idComercio && ctype_digit((string) $idComercio)) {
            $comercio = Comercio::find($idComercio);

            if ($comercio) {
                $request->session()->put(
                    'url.intended',
                    route('comercio.show', $comercio->id_comercio)
                );
            }
        }
    }
}
