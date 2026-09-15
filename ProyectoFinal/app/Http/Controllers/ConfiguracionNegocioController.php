<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use App\Models\TipoEntrega;
use App\Models\TipoPrecio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracionNegocioController extends Controller
{
    public function crearTipoPrecio(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $tipo = TipoPrecio::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'activo' => $request->boolean('activo', true),
        ]);

        return back()->with('success', 'Tipo de precio creado correctamente.');
    }

    public function crearTipoEntrega(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        TipoEntrega::create([
            'nombre' => $request->input('nombre'),
            'activo' => $request->boolean('activo', true),
        ]);

        return back()->with('success', 'Tipo de entrega creado correctamente.');
    }

    public function crearMetodoPago(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        MetodoPago::create([
            'nombre' => $request->input('nombre'),
            'activo' => $request->boolean('activo', true),
        ]);

        return back()->with('success', 'Método de pago creado correctamente.');
    }
}