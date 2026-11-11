<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al negocio - Sinergia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-800 font-sans antialiased min-h-screen flex">

    <div class="w-full min-h-screen grid grid-cols-1 lg:grid-cols-12">

        <div class="hidden lg:flex lg:col-span-5 xl:col-span-6 bg-gradient-to-br from-[#032B45] via-[#021d30] to-[#00A88F] p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-16 -left-16 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-[#00A88F]/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="/" class="inline-flex items-center gap-5">
                    <img src="{{ asset('storage/img/logo-sinergia.png') }}" alt="Logo Sinergia" class="h-28 w-28 object-cover rounded-full shadow-xl">
                    <span class="text-5xl font-black text-white tracking-tight">Sinergia</span>
                </a>
            </div>

            <div class="relative z-10 my-auto py-12">
                <span class="inline-block bg-[#00A88F]/20 text-[#00A88F] text-xs font-bold uppercase tracking-widest px-3.5 py-1.5 rounded-full border border-[#00A88F]/30 mb-5">
                    Panel de Comercios
                </span>
                <h2 class="text-4xl xl:text-5xl font-black text-white leading-tight mb-5">
                    Potenciá las ventas de tu local.
                </h2>
                <p class="text-slate-300 text-lg leading-relaxed max-w-md">
                    Gestioná tu catálogo de productos, recibí pedidos en tiempo real y conectá con tus clientes desde un solo lugar.
                </p>
            </div>

            <div class="relative z-10 text-slate-400 text-sm">
                © {{ date('Y') }} Sinergia. Tu local, más cerca.
            </div>
        </div>

        <div class="col-span-1 lg:col-span-7 xl:col-span-6 bg-slate-50 flex items-center justify-center p-6 sm:p-12">

            <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">

                <div class="lg:hidden text-center mb-6">
                    <a href="/" class="inline-block">
                        <img src="{{ asset('storage/img/logo-sinergia.png') }}" alt="Logo Sinergia" class="h-32 w-32 object-cover rounded-full shadow-md mx-auto">
                    </a>
                </div>

                <div class="mb-8 text-center lg:text-left">
                    <h1 class="text-3xl font-black text-[#032B45] tracking-tight">Acceso al negocio</h1>
                    <p class="text-slate-500 text-sm mt-1">Ingresá tus credenciales para administrar tu tienda.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-6 text-sm border border-red-100 font-medium flex items-center gap-2">
                        <span>⚠️</span> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-slate-800 placeholder-slate-400 bg-slate-50/50"
                            placeholder="nombre@tucomercio.com">
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Contraseña</label>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00A88F] focus:border-transparent transition-all text-slate-800 bg-slate-50/50"
                            placeholder="••••••••">
                    </div>

                    <button type="submit"
                        class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-[#032B45]/20 hover:shadow-[#00A88F]/30 transition-all duration-300 text-base mt-2">
                        Ingresar a mi tienda &rarr;
                    </button>
                </form>

                <div class="text-center mt-8 pt-6 border-t border-slate-100">
                    <p class="text-slate-600 text-sm mb-2">
                        ¿Todavía no tenés una cuenta en Sinergia?
                    </p>
                    <a href="{{ route('registro.negocio') }}" class="inline-block text-[#00A88F] hover:text-[#032B45] font-bold text-sm no-underline transition-colors">
                        Registrar mi comercio ahora
                    </a>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
