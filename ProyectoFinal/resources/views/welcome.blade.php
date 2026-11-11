<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinergia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-[#222222] font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-[#00A88F] selection:text-white">


    <div id="splash-screen" class="fixed inset-0 z-[100] bg-slate-900 flex items-center justify-center transition-opacity duration-700 ease-in-out px-4">
        <div class="relative flex items-center justify-center p-6">
       
           <img src="{{ asset('storage/img/logo-sinergia.png') }}" alt="Logo Sinergia" class="w-64 h-64 sm:w-96 sm:h-96 rounded-full object-cover shadow-[0_0_90px_rgba(0,168,143,0.7)] ring-4 ring-[#00A88F]/60 drop-shadow-2xl animate-bounce">
        </div>
    </div>

    <header class="bg-white border-b border-slate-200 px-6 sm:px-12 py-5 flex flex-col sm:flex-row justify-between items-center gap-4 sticky top-0 z-50">
        <a href="/" class="flex items-center gap-3 no-underline group">
            <img src="{{ asset('storage/img/logo-sinergia.png') }}" alt="Logo Sinergia" class="h-16 sm:h-20 w-auto object-contain transition-transform group-hover:scale-105">
            <span class="text-2xl font-bold text-[#032B45] tracking-tight">Sinergia</span>
        </a>

        <nav class="flex flex-wrap items-center justify-center gap-2 sm:gap-4 text-sm sm:text-base">
            <a href="{{ route('comercios.index') }}" class="text-[#333333] hover:bg-slate-100 px-3.5 py-2 rounded-lg transition-colors no-underline font-medium">
                Soy cliente
            </a>

            <a href="{{ route('login') }}" class="text-[#333333] hover:bg-slate-100 px-3.5 py-2 rounded-lg transition-colors no-underline font-medium">
                Acceso comercios
            </a>

            <a href="{{ route('registro.negocio') }}" class="bg-[#00A88F] hover:bg-[#008f7a] text-white font-medium px-4 py-2 rounded-lg transition-colors no-underline shadow-sm">
                Registrar mi comercio
            </a>
        </nav>
    </header>

    <main class="flex-grow">
        <section class="min-h-[60vh] sm:min-h-[70vh] flex justify-center items-center text-center px-5 py-12 sm:py-16">
            <div class="max-w-[800px] w-full">
                <h1 class="text-3xl sm:text-5xl font-bold mb-5 leading-tight text-slate-900 tracking-tight">
                    Gestioná tu comercio con <span class="text-[#00A88F]">Sinergia</span>
                </h1>

                <p class="text-base sm:text-xl text-slate-600 leading-relaxed mb-9 max-w-2xl mx-auto">
                    Una plataforma pensada para pequeños comercios,
                    que permite gestionar productos, pedidos y clientes
                    desde un mismo lugar.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-block px-7 py-3.5 rounded-lg text-white font-bold text-base bg-[#032B45] hover:bg-[#021d30] transition-all no-underline shadow-sm hover:shadow">
                        Iniciar sesión
                    </a>

                    <a href="{{ route('registro.negocio') }}" class="w-full sm:w-auto inline-block px-7 py-3.5 rounded-lg text-[#00A88F] border-2 border-[#00A88F] bg-white hover:bg-[#E6F4F1] font-bold text-base transition-all no-underline">
                        Registrar mi comercio
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-white py-16 px-5 text-center border-y border-slate-100">
            <div class="max-w-[750px] mx-auto p-8 sm:p-10 bg-[#E6F4F1]/60 border border-[#00A88F]/30 rounded-2xl shadow-xs">
                <h2 class="text-2xl sm:text-3xl font-bold mb-4 text-[#032B45]">
                    ¿Sos <span class="text-[#00A88F]">cliente</span>?
                </h2>

                <p class="text-slate-600 text-base sm:text-lg leading-relaxed mb-6">
                    Creá tu cuenta en Sinergia y disfrutá de una experiencia
                    más sencilla al realizar pedidos en tus comercios favoritos.
                </p>

                <div class="flex justify-center">
                    <a href="{{ route('comercios.index') }}" class="w-full sm:w-auto inline-block px-7 py-3.5 rounded-lg text-white font-bold text-base bg-[#00A88F] hover:bg-[#008f7a] transition-all no-underline shadow-sm hover:shadow">
                        Elegir comercio
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-[#f7f7f7] py-16 px-6 sm:px-8">
            <div class="max-w-[1000px] mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-7 text-center border border-slate-200 rounded-xl shadow-xs hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold mb-3 text-[#032B45]">
                        Gestión de productos
                    </h3>
                    <p class="text-slate-600 text-sm leading-normal">
                        Administrá los productos y precios
                        disponibles para tus clientes.
                    </p>
                </div>

                <div class="bg-white p-7 text-center border border-slate-200 rounded-xl shadow-xs hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold mb-3 text-[#032B45]">
                        Gestión de pedidos
                    </h3>
                    <p class="text-slate-600 text-sm leading-normal">
                        Recibí y gestioná los pedidos
                        realizados desde tu tienda.
                    </p>
                </div>

                <div class="bg-white p-7 text-center border border-slate-200 rounded-xl shadow-xs hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold mb-3 text-[#032B45]">
                        Tienda online
                    </h3>
                    <p class="text-slate-600 text-sm leading-normal">
                        Ofrecé a tus clientes una tienda
                        accesible desde cualquier dispositivo.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#222222] text-white text-center p-6 border-t border-slate-800">
        <p class="text-sm">
            © {{ date('Y') }} Sinergia. Todos los derechos reservados.
        </p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const splash = document.getElementById('splash-screen');
            setTimeout(() => {
                splash.classList.add('opacity-0');
                setTimeout(() => {
                    splash.classList.add('hidden');
                }, 700);
            }, 4000);
        });
    </script>

</body>
</html>