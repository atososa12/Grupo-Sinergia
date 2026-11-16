<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Las credenciales ingresadas no son válidas.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $usuario = Auth::user();

        if ($usuario->rol === 'admin') {
            return redirect()->route('admin.index');
        }

        if ($usuario->rol === 'dueño') {
            $comercio = $usuario->comercio;

            if (!$comercio || !$comercio->activo) {
                return redirect()->route('suscripcion.pago');
            }

            return redirect()->intended('/dashboard');
        }

        Auth::logout();

        return redirect()->route('login')->withErrors([
            'email' => 'Tu usuario no tiene permisos para ingresar.',
        ]);
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Las credenciales ingresadas no son válidas.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()->rol !== 'admin') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'No tenés permisos para ingresar al panel de administración.',
            ]);
        }

        return redirect()->route('admin.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
