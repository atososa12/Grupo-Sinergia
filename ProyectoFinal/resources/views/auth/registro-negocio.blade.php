<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de negocio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen py-6 px-4 flex items-center justify-center relative overflow-y-auto">

    <div class="absolute -top-24 -left-24 w-80 h-80 bg-[#00A88F]/30 rounded-full blur-[90px] pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#00A88F]/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-xl bg-white/95 backdrop-blur-md p-6 sm:p-8 rounded-2xl shadow-2xl shadow-slate-950/50 border border-white/20 relative z-10 my-auto">
        
        <div class="text-center mb-6">
            <a href="/" class="inline-block mb-3">
                <img src="{{ asset('img/logo-sinergia.png') }}" alt="Logo Sinergia" class="h-24 w-24 object-cover rounded-full shadow-lg mx-auto">
            </a>
            <h1 class="text-2xl font-black text-[#032B45] tracking-tight">Registrar mi negocio</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-xs border border-red-100 font-medium">
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('registro.negocio.submit') }}" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="sm:col-span-2">
                    <label for="nombre_comercio" class="block text-xs font-semibold text-slate-700 mb-1">Nombre del comercio</label>
                    <input id="nombre_comercio" name="nombre_comercio" type="text" maxlength="150" value="{{ old('nombre_comercio') }}" required
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div class="sm:col-span-2">
                    <label for="direccion" class="block text-xs font-semibold text-slate-700 mb-1">Dirección</label>
                    <input id="direccion" name="direccion" type="text" maxlength="255" value="{{ old('direccion') }}"
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div>
                    <label for="telefono" class="block text-xs font-semibold text-slate-700 mb-1">Teléfono de contacto</label>
                    <input id="telefono" name="telefono" type="text" maxlength="30" value="{{ old('telefono') }}"
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div>
                    <label for="email_comercio" class="block text-xs font-semibold text-slate-700 mb-1">Email del comercio</label>
                    <input id="email_comercio" name="email_comercio" type="email" maxlength="150" value="{{ old('email_comercio') }}"
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div>
                    <label for="nombre" class="block text-xs font-semibold text-slate-700 mb-1">Nombre del dueño</label>
                    <input id="nombre" name="nombre" type="text" maxlength="100" value="{{ old('nombre') }}" required
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div>
                    <label for="apellido" class="block text-xs font-semibold text-slate-700 mb-1">Apellido</label>
                    <input id="apellido" name="apellido" type="text" maxlength="100" value="{{ old('apellido') }}" required
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email de acceso</label>
                    <input id="email" name="email" type="email" maxlength="150" value="{{ old('email') }}" required
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Contraseña</label>
                    <input id="password" name="password" type="password" minlength="8" required
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required
                        class="w-full px-3.5 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-sm text-slate-800 bg-slate-50/50">
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all duration-300 text-sm mt-2">
                Crear negocio y entrar al panel &rarr;
            </button>
        </form>

        <div class="text-center mt-4 pt-4 border-t border-slate-100">
            <a href="{{ route('login') }}" class="text-[#00A88F] hover:text-[#032B45] font-semibold text-xs no-underline transition-colors">
                &larr; Ya tengo una cuenta
            </a>
        </div>

    </div>

</body>
</html>