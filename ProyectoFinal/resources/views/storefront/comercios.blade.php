<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercios disponibles - Sinergia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen py-12 px-6 flex flex-col items-center justify-start relative overflow-x-hidden">

    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#00A88F]/30 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] bg-[#00A88F]/20 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="w-full max-w-5xl relative z-10">
        
        <header class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-6">
            <div class="space-y-3">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-[#00A88F] text-white text-sm font-bold py-2.5 px-5 rounded-xl backdrop-blur-md border border-white/20 transition-all duration-300">
                    &larr; Volver al inicio
                </a>
                <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight">Comercios disponibles</h1>
                <p class="text-slate-300 text-base">Elegí el comercio donde querés realizar tu pedido.</p>
            </div>
            
            <div class="flex items-center gap-3 self-start md:self-auto">
                <img src="{{ asset('img/logo-sinergia.png') }}" alt="Logo Sinergia" class="h-32 w-32 object-cover rounded-full shadow-2xl border-2 border-white/20">
            </div>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse ($comercios as $comercio)

                <section class="bg-white/95 backdrop-blur-md p-8 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/20 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                    <div>
                        <div class="flex items-center gap-6 mb-6">
                            
                            <div class="relative h-36 w-36 flex-shrink-0">
                                <img src="{{ asset('img/' . \Illuminate\Support\Str::slug($comercio->nombre) . '/logo-' . \Illuminate\Support\Str::slug($comercio->nombre) . '.png') }}" 
                                     alt="{{ ucwords(mb_strtolower($comercio->nombre)) }}" 
                                     class="h-36 w-36 object-contain p-0 bg-white rounded-full shadow-lg border-2 border-slate-200 absolute inset-0 scale-100"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                
                                <div class="h-36 w-36 bg-[#032B45] text-white font-black rounded-full items-center justify-center shadow-lg border-2 border-slate-200 text-4xl absolute inset-0" style="display: none;">
                                    {{ strtoupper(substr($comercio->nombre, 0, 2)) }}
                                </div>
                            </div>

                            <div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#032B45] tracking-tight">{{ ucwords(mb_strtolower($comercio->nombre)) }}</h2>
                            </div>
                        </div>
                        
                        <div class="space-y-2.5 text-base text-slate-600 mb-8 bg-slate-50/80 p-5 rounded-2xl border border-slate-100">
                            <p class="flex items-center gap-2">
                                <span class="font-bold text-slate-700">Dirección:</span>
                                {{ $comercio->direccion ?? 'No especificada' }}
                            </p>
                            <p class="flex items-center gap-2">
                                <span class="font-bold text-slate-700">Teléfono:</span>
                                {{ $comercio->telefono ?? 'No especificado' }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('comercio.show', $comercio->id_comercio) }}" 
                       class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-4 px-6 rounded-2xl text-center shadow-lg transition-all duration-300 text-base flex items-center justify-center gap-2">
                        Entrar a la tienda &rarr;
                    </a>
                </section>

            @empty
                <div class="col-span-full bg-white/95 backdrop-blur-md p-10 rounded-3xl text-center shadow-xl border border-white/20">
                    <p class="text-slate-600 font-semibold text-lg">Actualmente no hay comercios disponibles.</p>
                </div>
            @endforelse
        </main>

    </div>

</body>
</html>