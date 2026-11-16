<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarComercioActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();

        if (!$usuario) {
            abort(403);
        }

        if ($usuario->rol === 'admin') {
            return redirect()->route('admin.index');
        }

        if ($usuario->rol !== 'dueño') {
            abort(403);
        }

        $comercio = $usuario->comercio;

        if (!$comercio) {
            abort(403);
        }

        if (!$comercio->activo) {
            return redirect()->route('suscripcion.pago');
        }

        return $next($request);
    }
}
