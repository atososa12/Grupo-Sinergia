
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de Administración - Sinergia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#032B45] flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10">

        <div class="text-center mb-8">
            <img
                src="{{ asset('storage/img/logo-sinergia.png') }}"
                alt="Sinergia"
                class="h-20 mx-auto object-contain mb-5"
            >

            <h1 class="text-3xl font-extrabold text-[#032B45]">
                Administración
            </h1>

            <p class="text-gray-500 mt-2">
                Acceso exclusivo para el equipo de Sinergia
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-5 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-[#00A88F]"
                    placeholder="admin@sinergia.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-[#00A88F]"
                    placeholder="Ingresá tu contraseña"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-[#00A88F] hover:bg-[#008f7a] text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg"
            >
                Ingresar al panel
            </button>
        </form>

        <div class="text-center mt-7">
            <a href="/" class="text-sm text-gray-500 hover:text-[#00A88F] transition-colors">
                Volver al inicio
            </a>
        </div>

    </div>

</body>
</html>
