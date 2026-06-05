<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilos personalizados -->
    <style>
        .btn-nuevo {
            background-color: #22c55e;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-nuevo:hover {
            background-color: #15803d;
        }

        .btn-editar {
            background-color: #eab308;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-editar:hover {
            background-color: #a16207;
        }

        .btn-eliminar {
            background-color: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-eliminar:hover {
            background-color: #b91c1c;
        }
    </style>

</head>

<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100">

        {{-- Barra de navegación de Breeze --}}
        @include('layouts.navigation')

        {{-- Menú del sistema --}}
        @include('menu')

        {{-- Encabezado de página --}}
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Contenido de cada vista --}}
        <main>
            {{ $slot }}
        </main>

    </div>

</body>
</html>