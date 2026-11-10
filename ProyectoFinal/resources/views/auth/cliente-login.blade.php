<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sinergia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden">

    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#00A88F]/35 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] bg-[#00A88F]/25 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-sky-500/10 rounded-full blur-[180px] pointer-events-none"></div>

    <div class="w-full max-w-4xl bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[520px] relative z-10 border border-white/20">
        
        <div class="bg-[#011624] text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            
            <div class="relative z-10 space-y-6">
                <div class="flex items-center gap-4">
                    <img 
                        src="{{ asset('img/logo-sinergia.png') }}" 
                        alt="Logo Sinergia" 
                        class="h-20 w-20 rounded-full object-cover p-1 bg-white shadow-xl border-2 border-[#00A88F]"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="h-20 w-20 bg-[#00A88F] text-white font-black rounded-full items-center justify-center text-3xl shadow-xl border-2 border-white/20 hidden">
                        S
                    </div>
                    <span class="text-2xl font-black tracking-tight text-white">Sinergia</span>
                </div>

                <div class="space-y-2 pt-4">
                    <h2 class="text-3xl font-black tracking-tight leading-tight text-white">
                        ¡Qué bueno verte de nuevo!
                    </h2>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Ingresá a tu cuenta para realizar tus pedidos de forma rápida y seguir tu historial.
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-6 border-t border-white/10 text-xs text-slate-400">
                &copy; {{ date('Y') }} Sinergia. Todos los derechos reservados.
            </div>
        </div>

        <div class="p-8 sm:p-10 flex flex-col justify-center space-y-6 bg-slate-50/50">
            <h1 class="text-2xl sm:text-3xl font-black text-[#032B45] tracking-tight">
                Iniciar sesión
            </h1>

            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-600 p-4 rounded-2xl text-sm space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('cliente.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Correo electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]"
                        placeholder="tu@email.com"
                    >
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]"
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg transition-all duration-300 text-base flex items-center justify-center gap-2">
                    Ingresar &rarr;
                </button>
            </form>

            <div class="text-center pt-2 border-t border-slate-200">
                <p class="text-xs text-slate-600 font-medium">
                    ¿Todavía no tenés cuenta? 
                    <a href="{{ route('cliente.registro', request()->only('comercio')) }}" class="font-bold text-[#00A88F] hover:underline">
                        Registrate
                    </a>
                </p>
            </div>
        </div>

    </div>

</body>
</html>