
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis pedidos - Sinergia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen py-10 px-4 sm:px-6">

    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-[#00A88F]/20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] bg-[#00A88F]/20 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-5xl mx-auto relative z-10 space-y-8">

        <header class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div>
                    <h1 class="text-3xl sm:text-4xl font-black text-[#032B45]">
                        Mis pedidos
                    </h1>

                    <p class="text-slate-500 mt-2 text-sm">
                        Consultá tus compras y seguí el estado de tus pedidos.
                    </p>
                </div>

                <a href="{{ $urlVolver }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#032B45] hover:bg-[#00A88F] text-white font-bold text-sm py-3 px-5 rounded-xl transition-all duration-300">
                    &larr; Volver al menú
                </a>

            </div>

        </header>

        @if(session('success'))
            <div class="bg-emerald-500/15 border border-emerald-400/30 text-emerald-100 p-4 rounded-2xl font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-500/15 border border-rose-400/30 text-rose-100 p-4 rounded-2xl font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-500/15 border border-rose-400/30 text-rose-100 p-4 rounded-2xl space-y-1">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @forelse($pedidos as $pedido)

            @php
                $estados = [
                    1 => ['Pendiente', 'bg-amber-100 text-amber-700'],
                    2 => ['En preparación', 'bg-blue-100 text-blue-700'],
                    3 => ['Listo para entregar', 'bg-emerald-100 text-emerald-700'],
                    4 => ['Entregado', 'bg-green-100 text-green-700'],
                    5 => ['Cancelado', 'bg-rose-100 text-rose-700'],
                    6 => ['Aceptado', 'bg-cyan-100 text-cyan-700'],
                    7 => ['En camino', 'bg-indigo-100 text-indigo-700'],
                ];

                $estado = $estados[(int) $pedido->id_estado] ?? ['Sin estado', 'bg-slate-100 text-slate-700'];
            @endphp

            <article class="bg-white rounded-3xl shadow-xl overflow-hidden">

                <div class="p-6 sm:p-8 border-b border-slate-100">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        <div>
                            <p class="text-xs uppercase tracking-wider font-bold text-slate-400">
                                Pedido #{{ $pedido->id_pedido }}
                            </p>

                            <h2 class="text-2xl font-black text-[#032B45] mt-1">
                                {{ $pedido->comercio->nombre ?? 'Comercio' }}
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $pedido->fecha?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <span class="inline-flex self-start sm:self-center px-4 py-2 rounded-full text-xs font-black {{ $estado[1] }}">
                            {{ $estado[0] }}
                        </span>

                    </div>

                </div>

                <div class="p-6 sm:p-8 space-y-6">

                    <div>
                        <h3 class="text-sm font-black text-[#032B45] uppercase tracking-wider mb-4">
                            Productos del pedido
                        </h3>

                        <div class="space-y-3">

                            @foreach($pedido->detalles as $detalle)

                                <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3 last:border-0">

                                    <div>
                                        <p class="font-bold text-slate-800">
                                            {{ $detalle->producto->nombre ?? 'Producto' }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            Cantidad: {{ $detalle->cantidad }}
                                        </p>
                                    </div>

                                    <p class="font-bold text-[#032B45] whitespace-nowrap">
                                        ${{ number_format($detalle->subtotal ?? ($detalle->cantidad * $detalle->precio_unitario), 2, ',', '.') }}
                                    </p>

                                </div>

                            @endforeach

                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                Tipo de entrega
                            </p>

                            <p class="font-bold text-[#032B45] mt-1">
                                {{ (int) $pedido->id_tipo_entrega === 1 ? 'Delivery' : ((int) $pedido->id_tipo_entrega === 2 ? 'Retiro en el local' : 'No especificado') }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                Dirección de entrega
                            </p>

                            <p class="font-bold text-[#032B45] mt-1">
                                {{ $pedido->direccion_entrega ?? 'No especificada' }}
                            </p>
                        </div>

                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-slate-100 pt-5">

                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                Total del pedido
                            </p>

                            <p class="text-3xl font-black text-[#00A88F] mt-1">
                                ${{ number_format($pedido->total, 2, ',', '.') }}
                            </p>
                        </div>

                        @if(in_array((int) $pedido->id_estado, [1, 6], true))

                            <form method="POST"
                                  action="{{ route('cliente.pedidos.cancelar', $pedido->id_pedido) }}"
                                  onsubmit="return confirm('¿Estás seguro de que querés cancelar este pedido?');">

                                @csrf

                                <button type="submit"
                                        class="bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm py-3 px-6 rounded-xl transition-all duration-300">
                                    Cancelar pedido
                                </button>

                            </form>

                        @elseif((int) $pedido->id_estado === 5)

                            <span class="text-rose-600 font-bold text-sm">
                                Este pedido fue cancelado.
                            </span>

                        @endif

                    </div>

                </div>

            </article>

        @empty

            <div class="bg-white rounded-3xl shadow-xl p-10 text-center">

                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-black text-[#032B45]">
                    Todavía no tenés pedidos
                </h2>

                <p class="text-slate-500 mt-2">
                    Cuando realices una compra, vas a poder consultar su historial desde acá.
                </p>

                <a href="{{ $urlVolver }}"
                   class="inline-flex mt-6 bg-[#00A88F] hover:bg-[#032B45] text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                    Volver al menú
                </a>

            </div>

        @endforelse

        <footer class="text-center text-white/60 text-xs py-4">
            Sinergia - Todos los derechos reservados
        </footer>

    </div>

</body>
</html>
