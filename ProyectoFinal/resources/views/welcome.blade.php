<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinergia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-[#222222] font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-[#00A88F] selection:text-white">

    <div id="splash-screen" class="fixed inset-0 z-[100] bg-[#032B45] flex items-center justify-center transition-opacity duration-700 ease-in-out px-4">
        <h1 class="text-6xl sm:text-8xl lg:text-9xl font-extrabold tracking-[0.14em] text-white animate-pulse">Sinergia</h1>
    </div>

    <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-3 flex flex-col sm:flex-row justify-between items-center gap-3 sticky top-0 z-50">
        <a href="/" class="flex items-center gap-3 no-underline group">
            <img src="{{ asset('storage/img/logo-sinergia.png') }}" alt="Logo Sinergia" class="h-16 sm:h-20 w-auto object-contain transition-transform group-hover:scale-105">
            <span class="text-2xl font-bold text-[#032B45] tracking-tight">Sinergia</span>
        </a>

        <nav class="flex flex-wrap items-center justify-center gap-2 text-sm sm:text-base">
            <a href="{{ route('comercios.index') }}" class="text-[#333333] hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors no-underline font-medium">
                Restaurantes
            </a>

            <a href="{{ route('login') }}" class="text-[#333333] hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors no-underline font-medium">
                Mi negocio
            </a>

            <a href="{{ route('admin.login') }}" class="text-[#032B45] bg-[#E6F4F1] hover:bg-[#00A88F] hover:text-white px-3 py-2 rounded-lg transition-colors no-underline font-semibold border border-[#00A88F]">
                Administrador
            </a>
        </nav>
    </header>

    <main class="flex-grow">

        <section class="relative w-full min-h-[560px] sm:min-h-[620px] flex items-center px-6 sm:px-16 py-16 text-white bg-cover bg-center shadow-lg" style="background-image: url('{{ asset('storage/img/banner1-sinergia.png') }}');">

            <div class="absolute inset-0 bg-[#032B45]/85 backdrop-blur-[2px]"></div>

            <div class="relative z-10 max-w-4xl mx-auto md:mx-0 space-y-6 text-center md:text-left">
                <span class="inline-block bg-[#00A88F]/25 border border-[#00A88F] text-[#E6F4F1] font-bold text-xs sm:text-sm px-4 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                    Impulsa tu Negocio Hoy
                </span>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                    Gestioná tu comercio con <span class="text-[#00A88F]">Sinergia</span>
                </h1>

                <div class="space-y-4 text-base sm:text-lg lg:text-xl text-[#f7f7f7] font-normal leading-relaxed max-w-2xl">
                    <p class="bg-white/5 border-l-4 border-[#00A88F] px-4 py-3 rounded-r-xl">
                        Una plataforma pensada para facilitar la gestión de productos y pedidos de tu negocio desde un mismo lugar.
                    </p>

                    <p class="bg-[#032B45]/90 border border-[#00A88F]/60 px-4 py-3.5 rounded-xl text-white font-medium shadow-md">
                        <span class="text-[#00A88F] font-bold uppercase tracking-wide text-sm block mb-1">Promoción exclusiva</span>
                        Solo <strong class="text-[#5eead4] text-xl sm:text-2xl font-extrabold">$5.000 / mes</strong> durante tus primeros 12 meses.
                    </p>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <a href="{{ route('registro.negocio') }}" class="inline-block px-8 py-4 rounded-xl text-white font-bold text-base bg-[#00A88F] hover:bg-[#008f7a] transition-all transform hover:-translate-y-0.5 no-underline shadow-xl shadow-[#00A88F]/30 text-center">
                        Registrar mi comercio gratis
                    </a>

                    <a href="{{ route('comercios.index') }}" class="inline-block px-7 py-4 rounded-xl text-white font-semibold text-base bg-white/10 hover:bg-white/20 border border-white/30 transition-all no-underline text-center">
                        Ver Restaurantes
                    </a>
                </div>
            </div>
        </section>

        <section class="py-20 px-4 sm:px-8 bg-gradient-to-b from-[#f7f7f7] to-[#E6F4F1]">
            <div class="max-w-7xl mx-auto">

                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="inline-block text-[#008f7a] font-bold text-xs sm:text-sm uppercase tracking-widest bg-white border border-[#00A88F] px-4 py-1.5 rounded-full shadow-sm">
                        Paso a paso
                    </span>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#032B45] mt-4 mb-3 tracking-tight">
                        ¿Cómo funciona <span class="text-[#00A88F]">Sinergia</span>?
                    </h2>

                    <p class="text-[#333333] text-base sm:text-lg leading-relaxed">
                        Digitalizá tu negocio y empezá a vender online en tres pasos sencillos.
                    </p>
                </div>

                <div class="flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-4">

                    <div class="w-full lg:flex-1 text-center px-4 py-6">
                        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#032B45] to-[#008f7a] text-white font-extrabold text-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-[#032B45]/25 border-2 border-white">
                            01
                        </div>

                        <h3 class="text-2xl font-extrabold text-[#032B45] mb-3">
                            Registrarse
                        </h3>

                        <p class="text-[#333333] text-base leading-relaxed max-w-sm mx-auto">
                            Completa el formulario en segundos y da el primer paso para digitalizar tu negocio.
                        </p>
                    </div>

                    <div class="flex items-center justify-center my-2 lg:my-0" aria-hidden="true">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#032B45] to-[#00A88F] flex items-center justify-center text-white shadow-lg shadow-[#00A88F]/30 rotate-90 lg:rotate-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="m13 6 6 6-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <div class="w-full lg:flex-1 text-center px-4 py-6">
                        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#008f7a] to-[#00A88F] text-white font-extrabold text-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-[#00A88F]/25 border-2 border-white">
                            02
                        </div>

                        <h3 class="text-2xl font-extrabold text-[#032B45] mb-3">
                            Configura tu comercio
                        </h3>

                        <p class="text-[#333333] text-base leading-relaxed max-w-sm mx-auto">
                            Carga tus productos y personaliza tu tienda a tu medida.
                        </p>
                    </div>

                    <div class="flex items-center justify-center my-2 lg:my-0" aria-hidden="true">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#00A88F] to-[#032B45] flex items-center justify-center text-white shadow-lg shadow-[#032B45]/25 rotate-90 lg:rotate-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="m13 6 6 6-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <div class="w-full lg:flex-1 text-center px-4 py-6">
                        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#00A88F] to-[#032B45] text-white font-extrabold text-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-[#032B45]/25 border-2 border-white">
                            03
                        </div>

                        <h3 class="text-2xl font-extrabold text-[#032B45] mb-3">
                            Recibí pedidos
                        </h3>

                        <p class="text-[#333333] text-base leading-relaxed max-w-sm mx-auto">
                            Gestiona tus ventas fácilmente desde el panel.
                        </p>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#222222] text-white text-center py-4 px-6 border-t border-slate-800">
        <p class="text-sm">© {{ date('Y') }} Sinergia. Todos los derechos reservados.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const splash = document.getElementById('splash-screen');

            if (splash) {
                setTimeout(() => {
                    splash.classList.add('opacity-0');
                    setTimeout(() => splash.classList.add('hidden'), 700);
                }, 1800);
            }
        });
    </script>

</body>
</html>
