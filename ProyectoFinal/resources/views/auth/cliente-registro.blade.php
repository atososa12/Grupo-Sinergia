<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Sinergia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#032B45] text-slate-100 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[450px] sm:w-[600px] h-[450px] sm:h-[600px] bg-[#00A88F]/30 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-lg relative z-10 my-8">

        <div class="relative bg-[#021826]/85 backdrop-blur-xl p-6 sm:p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 text-center space-y-6">

            <div class="-mt-16 sm:-mt-20 flex justify-center">
                <div class="relative inline-block">
                    <img
                        src="{{ asset('storage/img/logo-sinergia.png') }}"
                        alt="Logo Sinergia"
                        class="h-24 w-24 sm:h-28 sm:w-28 rounded-full object-cover p-1 bg-[#021826] shadow-2xl ring-4 ring-[#00A88F] ring-offset-4 ring-offset-[#032B45]"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="h-24 w-24 sm:h-28 sm:w-28 bg-[#00A88F] text-white font-black rounded-full items-center justify-center text-4xl shadow-2xl ring-4 ring-[#00A88F] ring-offset-4 ring-offset-[#032B45] hidden">
                        S
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Crear cuenta
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 font-medium">
                    Unite a Sinergia y gestioná tus pedidos
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-500/15 border border-rose-500/30 text-rose-300 p-4 rounded-2xl text-xs sm:text-sm text-left space-y-1 backdrop-blur-md font-medium">
                    @foreach ($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('cliente.registro.submit') }}" method="POST" class="space-y-4 text-left">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-widest pl-1">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-[#00A88F] focus:ring-2 focus:ring-[#00A88F]/30 transition-all"
                            placeholder="Tu nombre"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-widest pl-1">Apellido</label>
                        <input
                            type="text"
                            name="apellido"
                            value="{{ old('apellido') }}"
                            required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-[#00A88F] focus:ring-2 focus:ring-[#00A88F]/30 transition-all"
                            placeholder="Tu apellido"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-widest pl-1">Teléfono</label>
                        <input
                            type="text"
                            name="telefono"
                            value="{{ old('telefono') }}"
                            required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-[#00A88F] focus:ring-2 focus:ring-[#00A88F]/30 transition-all"
                            placeholder="Ej: 1123456789"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-widest pl-1">Correo electrónico</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-[#00A88F] focus:ring-2 focus:ring-[#00A88F]/30 transition-all"
                            placeholder="tu@email.com"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-widest pl-1">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-[#00A88F] focus:ring-2 focus:ring-[#00A88F]/30 transition-all"
                            placeholder="••••••••"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-widest pl-1">Confirmar contraseña</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-[#00A88F] focus:ring-2 focus:ring-[#00A88F]/30 transition-all"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#00A88F] hover:bg-[#008f7a] text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-[#00A88F]/20 transition-all duration-300 text-base flex items-center justify-center gap-2 mt-4">
                    Registrarme &rarr;
                </button>
            </form>

            <div class="pt-4 border-t border-white/10 text-center">
                <p class="text-xs text-slate-300 font-medium">
                    ¿Ya tenés una cuenta?
                    <a href="{{ route('cliente.login', request()->only('comercio')) }}" class="font-bold text-[#00A88F] hover:underline">
                        Iniciá sesión
                    </a>
                </p>
            </div>

        </div>

    </div>

</body>
</html>