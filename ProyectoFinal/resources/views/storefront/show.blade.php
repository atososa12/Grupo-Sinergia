
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucwords(mb_strtolower($comercio->nombre)) }} Sinergia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen py-10 px-4 sm:px-6 flex flex-col items-center justify-start relative overflow-x-hidden">

    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#00A88F]/30 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] bg-[#00A88F]/20 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="w-full max-w-4xl relative z-10 space-y-8">

        <header class="bg-white/95 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-2xl border border-white/20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <a href="{{ route('comercios.index') }}" class="inline-flex items-center gap-2 bg-[#032B45]/10 hover:bg-[#00A88F] hover:text-white text-[#032B45] text-sm font-bold py-2.5 px-5 rounded-xl transition-all duration-300 self-start">
                    &larr; Volver a comercios
                </a>

                <div class="flex items-center gap-3 flex-wrap">
                    @if(Auth::guard('clientes')->check())
                        <div class="text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 px-4 py-2 rounded-xl">
                            Hola, <span class="font-bold text-[#032B45]">{{ ucwords(mb_strtolower($cliente->nombre)) }}</span>
                        </div>

                        <a href="{{ route('cliente.pedidos') }}" class="bg-[#00A88F] hover:bg-[#032B45] text-white text-xs font-bold py-2 px-4 rounded-xl transition-all duration-300">
                            Mis pedidos
                        </a>

                        <form method="POST" action="{{ route('cliente.logout') }}">
                            @csrf
                            <button type="submit" class="bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white text-xs font-bold py-2 px-4 rounded-xl transition-all duration-300">
                                Salir
                            </button>
                        </form>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('cliente.login', ['comercio' => $comercio->id_comercio]) }}" class="bg-[#032B45]/10 hover:bg-[#032B45] hover:text-white text-[#032B45] text-xs font-bold py-2 px-4 rounded-xl transition-all duration-300">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('cliente.registro', ['comercio' => $comercio->id_comercio]) }}" class="bg-[#00A88F] hover:bg-[#032B45] text-white text-xs font-bold py-2 px-4 rounded-xl shadow-md transition-all duration-300">
                                Registrarse
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                @php
                    $nombreSlug = \Illuminate\Support\Str::slug($comercio->nombre);
                    $rutaLogo = 'img/' . $nombreSlug . '/logo-' . $nombreSlug . '.png';
                @endphp

                <div class="relative h-32 w-32 flex-shrink-0">
                    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($rutaLogo))
                        <img src="{{ asset('storage/' . $rutaLogo) }}"
                             alt="{{ ucwords(mb_strtolower($comercio->nombre)) }}"
                             class="h-32 w-32 object-contain p-1 bg-white rounded-full shadow-lg border-2 border-slate-200 absolute inset-0"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @elseif(file_exists(public_path($rutaLogo)))
                        <img src="{{ asset($rutaLogo) }}"
                             alt="{{ ucwords(mb_strtolower($comercio->nombre)) }}"
                             class="h-32 w-32 object-contain p-1 bg-white rounded-full shadow-lg border-2 border-slate-200 absolute inset-0"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @else
                        <div class="h-32 w-32 bg-[#032B45] text-white font-black rounded-full items-center justify-center shadow-lg border-2 border-slate-200 text-3xl absolute inset-0 flex">
                            {{ strtoupper(substr($comercio->nombre, 0, 2)) }}
                        </div>
                    @endif

                    <div class="h-32 w-32 bg-[#032B45] text-white font-black rounded-full items-center justify-center shadow-lg border-2 border-slate-200 text-3xl absolute inset-0" style="display: none;">
                        {{ strtoupper(substr($comercio->nombre, 0, 2)) }}
                    </div>
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl sm:text-4xl font-black text-[#032B45] tracking-tight">
                        {{ ucwords(mb_strtolower($comercio->nombre)) }}
                    </h1>
                    <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                        <p class="flex items-center gap-1.5">
                            <span class="font-bold text-slate-800">Dirección:</span>
                            {{ $comercio->direccion ?? 'No especificada' }}
                        </p>
                        <p class="flex items-center gap-1.5">
                            <span class="font-bold text-slate-800">Teléfono:</span>
                            {{ $comercio->telefono ?? 'No especificado' }}
                        </p>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-200 p-4 rounded-2xl text-sm font-medium backdrop-blur-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-sky-500/10 border border-sky-500/30 text-sky-200 p-4 rounded-2xl text-sm font-medium backdrop-blur-md">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-200 p-4 rounded-2xl text-sm space-y-1 backdrop-blur-md">
                @foreach($errors->all() as $error)
                    <p class="font-medium">&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="flex bg-white/10 backdrop-blur-md p-1.5 rounded-2xl border border-white/20 gap-2">
            <button type="button" id="tab-catalogo-btn" onclick="switchTab('catalogo')" class="flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 bg-[#00A88F] text-white shadow-lg">
                Catálogo de productos
            </button>
            <button type="button" id="tab-pedido-btn" onclick="switchTab('pedido')" class="flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 text-white/80 hover:text-white hover:bg-white/10">
                Mi Pedido / Checkout
                <span id="cart-badge" class="ml-2 bg-rose-500 text-white text-xs px-2 py-0.5 rounded-full font-black hidden">0</span>
            </button>
        </div>

        <main>

            <section id="tab-catalogo" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($comercio->productos as $producto)
                        @php
                            $precio = $producto->precios->first();
                        @endphp

                        <article class="bg-white/95 backdrop-blur-md rounded-3xl p-5 shadow-xl border border-white/20 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                            <div>
                                <div class="w-full h-52 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200 mb-4 shadow-inner relative group">
                                    @if($producto->imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($producto->imagen))
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @elseif($producto->imagen && file_exists(public_path($producto->imagen)))
                                        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="text-slate-400 text-xs font-bold flex flex-col items-center gap-1.5">
                                            <svg class="w-10 h-10 stroke-current opacity-50" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="tracking-wider uppercase text-[10px]">Sin imagen</span>
                                        </div>
                                    @endif
                                </div>

                                <h3 class="text-xl font-black text-[#032B45] mb-1.5 tracking-tight">
                                    {{ $producto->nombre }}
                                </h3>
                                <p class="text-slate-600 text-sm line-clamp-2 mb-4">
                                    {{ $producto->descripcion ?? 'Sin descripción disponible.' }}
                                </p>
                            </div>

                            <div class="space-y-4 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-[#032B45]">
                                        ${{ number_format($precio?->precio ?? 0, 2, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <input
                                        type="number"
                                        min="1"
                                        value="1"
                                        class="w-20 bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-center font-bold text-slate-700 text-sm focus:outline-none focus:border-[#00A88F]"
                                        data-product-id="{{ $producto->id_producto }}"
                                    >

                                    <button
                                        type="button"
                                        class="add-to-cart flex-1 bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition-all duration-300 text-sm flex items-center justify-center gap-2"
                                        data-product-id="{{ $producto->id_producto }}"
                                        data-product-name="{{ $producto->nombre }}"
                                        data-price="{{ $precio?->precio ?? 0 }}"
                                    >
                                        + Agregar
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full bg-white/95 backdrop-blur-md p-10 rounded-3xl text-center shadow-xl border border-white/20">
                            <p class="text-slate-600 font-semibold text-lg">No hay productos disponibles todavía.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section id="tab-pedido" class="space-y-6 hidden">
                <div class="bg-white/95 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-2xl border border-white/20 space-y-6 max-w-xl mx-auto">
                    <h2 class="text-2xl font-black text-[#032B45] tracking-tight">Finalizar tu pedido</h2>

                    <form method="POST" action="{{ route('comercio.pedido', $comercio->id_comercio) }}" class="space-y-4">
                        @csrf

                        <input type="hidden" name="items" id="items-input">

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nombre:</label>
                            <input
                                type="text"
                                name="nombre"
                                value="{{ old('nombre', $cliente ? $cliente->nombre . ' ' . $cliente->apellido : '') }}"
                                required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]"
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Teléfono:</label>
                            <input
                                type="text"
                                name="telefono"
                                value="{{ old('telefono', $cliente?->telefono ?? '') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]"
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Dirección de entrega:</label>
                            <input
                                type="text"
                                name="direccion"
                                value="{{ old('direccion', $ultimaDireccion ?? '') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Entrega:</label>
                                <select name="tipo_entrega" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                                    <option value="1" {{ old('tipo_entrega', '1') == '1' ? 'selected' : '' }}>Delivery</option>
                                    <option value="2" {{ old('tipo_entrega') == '2' ? 'selected' : '' }}>Retiro</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pago:</label>
                                <select name="metodo_pago" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                                    <option value="1" {{ old('metodo_pago', '1') == '1' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="2" {{ old('metodo_pago') == '2' ? 'selected' : '' }}>POS</option>
                                </select>
                            </div>
                        </div>

                        <div id="cart-summary" class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-sm text-slate-600 font-medium">
                            Tu carrito está vacío.
                        </div>

                        <button type="submit" class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-4 px-6 rounded-2xl shadow-lg transition-all duration-300 text-base flex items-center justify-center gap-2">
                            Enviar pedido &rarr;
                        </button>
                    </form>
                </div>
            </section>

        </main>

    </div>

    <script>
        function switchTab(tab) {
            const catalogoSection = document.getElementById('tab-catalogo');
            const pedidoSection = document.getElementById('tab-pedido');
            const catalogoBtn = document.getElementById('tab-catalogo-btn');
            const pedidoBtn = document.getElementById('tab-pedido-btn');

            if (tab === 'catalogo') {
                catalogoSection.classList.remove('hidden');
                pedidoSection.classList.add('hidden');
                catalogoBtn.className = "flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 bg-[#00A88F] text-white shadow-lg";
                pedidoBtn.className = "flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 text-white/80 hover:text-white hover:bg-white/10";
            } else {
                pedidoSection.classList.remove('hidden');
                catalogoSection.classList.add('hidden');
                pedidoBtn.className = "flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 bg-[#00A88F] text-white shadow-lg";
                catalogoBtn.className = "flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 text-white/80 hover:text-white hover:bg-white/10";
            }
        }

        const cart = {};
        const itemsInput = document.getElementById('items-input');
        const cartSummary = document.getElementById('cart-summary');
        const cartBadge = document.getElementById('cart-badge');

        document.querySelectorAll('.add-to-cart').forEach(function (button) {
            button.addEventListener('click', function () {
                const productId = Number(this.dataset.productId);
                const productName = this.dataset.productName;
                const price = Number(this.dataset.price);
                const qtyInput = document.querySelector('input[data-product-id="' + productId + '"]');
                const qty = Number(qtyInput ? qtyInput.value : 1);

                if (!productId || !Number.isInteger(qty) || qty < 1) return;

                if (!cart[productId]) {
                    cart[productId] = {
                        id_producto: productId,
                        cantidad: 0,
                        nombre: productName,
                        precio: price
                    };
                }

                cart[productId].cantidad += qty;

                let totalItemsCount = 0;
                const lines = Object.values(cart).map(function (item) {
                    totalItemsCount += item.cantidad;
                    return '<div class="flex justify-between py-1.5 border-b border-slate-200/60 last:border-0">' +
                           '<span class="font-bold text-slate-700">' + item.nombre + ' <span class="text-xs text-slate-500 font-normal">x' + item.cantidad + '</span></span>' +
                           '<span class="font-black text-[#032B45]">$' + (item.cantidad * item.precio).toFixed(2) + '</span>' +
                           '</div>';
                });

                cartSummary.innerHTML = lines.join('');
                itemsInput.value = JSON.stringify(Object.values(cart));

                if (totalItemsCount > 0) {
                    cartBadge.textContent = totalItemsCount;
                    cartBadge.classList.remove('hidden');
                } else {
                    cartBadge.classList.add('hidden');
                }

                const originalText = this.innerHTML;
                this.innerHTML = '¡Agregado! ✓';
                this.classList.remove('bg-[#032B45]');
                this.classList.add('bg-emerald-600');
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('bg-emerald-600');
                    this.classList.add('bg-[#032B45]');
                }, 1000);
            });
        });
    </script>

    @if(Auth::guard('clientes')->check())
        <script>
            function consultarNotificaciones() {
                fetch("{{ route('cliente.notificaciones.nuevas') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al consultar las notificaciones');
                    }
                    return response.json();
                })
                .then(notificaciones => {
                    notificaciones.forEach(notificacion => {
                        alert(notificacion.mensaje);
                    });
                })
                .catch(error => {
                    console.error('Notificaciones:', error);
                });
            }

            consultarNotificaciones();
            setInterval(consultarNotificaciones, 10000);
        </script>
    @endif

</body>
</html>
