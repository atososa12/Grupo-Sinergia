
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - {{ $comercio->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen p-4 sm:p-6 lg:p-10 relative overflow-x-hidden" x-data="{ tab: localStorage.getItem('admin_tab') || 'pedidos' }" x-init="$watch('tab', val => localStorage.setItem('admin_tab', val))">

<div class="max-w-7xl mx-auto space-y-6 relative z-10">

    <header class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-2xl flex flex-col sm:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="relative flex-shrink-0">
                @php
                    $nombreLimpio = strtolower($comercio->nombre);
                    $logoRuta = asset('storage/img/logo-sinergia.png');

                    if (str_contains($nombreLimpio, 'cuarta')) {
                        $logoRuta = asset('storage/img/la-cuarta-masa/logo-la-cuarta-masa.png');
                    } elseif (str_contains($nombreLimpio, 'don pepe')) {
                        $logoRuta = asset('storage/img/don-pepe/logo-don-pepe.png');
                    }
                @endphp

                <img src="{{ $logoRuta }}" alt="Logo {{ $comercio->nombre }}" class="h-24 w-24 sm:h-28 sm:w-28 rounded-full object-cover p-1 bg-white shadow-xl ring-4 ring-[#00A88F]" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="h-24 w-24 sm:h-28 sm:w-28 bg-[#00A88F] text-white font-black rounded-full items-center justify-center text-3xl shadow-xl ring-4 ring-[#032B45] hidden">
                    {{ strtoupper(substr($comercio->nombre, 0, 2)) }}
                </div>
            </div>

            <div class="space-y-1 text-center sm:text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-[#00A88F]">Panel de Administración</span>
                <h1 class="text-2xl sm:text-3xl font-black text-[#032B45] tracking-tight">{{ $comercio->nombre }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('comercio.show', $comercio->id_comercio) }}" class="flex-1 sm:flex-none bg-[#00A88F] hover:bg-[#008f7a] text-white font-extrabold px-6 py-3.5 rounded-2xl shadow-lg shadow-[#00A88F]/20 transition-all text-sm text-center">
                Ver mi tienda &rarr;
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1 sm:flex-none">
                @csrf
                <button type="submit" class="w-full bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-bold px-5 py-3.5 rounded-2xl border border-slate-200 transition-all text-sm">
                    Salir
                </button>
            </form>
        </div>
    </header>

    <nav class="bg-white p-2.5 rounded-2xl shadow-xl grid grid-cols-2 md:grid-cols-5 gap-2">
        <button @click="tab = 'pedidos'" :class="tab === 'pedidos' ? 'bg-[#00A88F] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="py-3.5 px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all text-center">
            Pedidos recibidos
        </button>

        <button @click="tab = 'productos'" :class="tab === 'productos' ? 'bg-[#00A88F] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="py-3.5 px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all text-center">
            Productos actuales
        </button>

        <button @click="tab = 'agregar'" :class="tab === 'agregar' ? 'bg-[#00A88F] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="py-3.5 px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all text-center">
            Agregar productos
        </button>

        <button @click="tab = 'configuracion'" :class="tab === 'configuracion' ? 'bg-[#00A88F] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="py-3.5 px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all text-center">
            Configuración
        </button>

        <button @click="tab = 'notificaciones'" :class="tab === 'notificaciones' ? 'bg-[#00A88F] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="py-3.5 px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all text-center flex items-center justify-center gap-2">
            Notificaciones
            @if($notificacionesNuevas->count() > 0)
                <span class="bg-rose-500 text-white text-[10px] font-black rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center">
                    {{ $notificacionesNuevas->count() }}
                </span>
            @endif
        </button>
    </nav>

    @if(session('success'))
        <div class="bg-[#00A88F]/15 backdrop-blur-md border border-[#00A88F]/40 text-emerald-100 p-4 rounded-2xl text-sm font-bold shadow-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-[#00A88F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-500/20 backdrop-blur-md border border-rose-500/40 text-rose-100 p-4 rounded-2xl text-sm font-bold shadow-xl">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/20 backdrop-blur-md border border-rose-500/40 text-rose-100 p-4 rounded-2xl text-sm font-bold space-y-1 shadow-xl">
            <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="bg-white p-6 sm:p-10 rounded-[2.5rem] shadow-2xl min-h-[450px]">

        <section x-show="tab === 'notificaciones'" x-cloak class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Notificaciones</h2>
                    <p class="text-sm text-slate-500 font-medium mt-1">Avisos sobre los pagos y la suscripción de tu comercio.</p>
                </div>

                <div class="bg-[#00A88F]/10 text-[#008f7a] border border-[#00A88F]/30 rounded-xl px-4 py-2 text-xs font-extrabold">
                    {{ $notificacionesNuevas->count() }} nuevas
                </div>
            </div>

            @forelse($notificaciones as $notificacion)
                @php
                    $esAprobada = $notificacion->tipo === 'pago_aprobado';

                    $claseNotificacion = $esAprobada
                        ? 'border-emerald-300 bg-emerald-50'
                        : 'border-rose-300 bg-rose-50';

                    $colorIcono = $esAprobada
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-rose-100 text-rose-700';
                @endphp

                <div class="border {{ $claseNotificacion }} rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                        <div class="w-12 h-12 rounded-2xl {{ $colorIcono }} flex items-center justify-center flex-shrink-0">
                            @if($esAprobada)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-black text-[#032B45]">
                                    {{ $notificacion->titulo }}
                                </h3>

                                @if(!$notificacion->leida)
                                    <span class="bg-[#00A88F] text-white text-[10px] uppercase tracking-wider font-black px-2.5 py-1 rounded-full">
                                        Nueva
                                    </span>
                                @else
                                    <span class="bg-slate-200 text-slate-600 text-[10px] uppercase tracking-wider font-black px-2.5 py-1 rounded-full">
                                        Leída
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-slate-700 leading-relaxed">
                                {{ $notificacion->mensaje }}
                            </p>

                            <p class="text-xs text-slate-500 font-semibold">
                                {{ $notificacion->fecha_creacion?->format('d/m/Y H:i') ?? 'Fecha no disponible' }}
                            </p>

                            @if(!$notificacion->leida)
                                <form method="POST" action="{{ route('dashboard.notificacion.leida', $notificacion->id_notificacion) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 bg-[#032B45] hover:bg-[#00A88F] text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-sm mt-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Marcar como leída
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-slate-50 border border-slate-200 rounded-[2rem]">
                    <div class="w-16 h-16 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-black text-[#032B45]">Todavía no tenés notificaciones</h3>
                    <p class="text-sm text-slate-500 mt-2">Acá vas a recibir los avisos sobre tus transferencias y suscripción.</p>
                </div>
            @endforelse
        </section>

        <section x-data="{ fechaSeleccionada: '{{ now()->toDateString() }}', historialAbierto: false, etiquetaPedidos: 'Pedidos del día: {{ $pedidos->count() }}' }" x-show="tab === 'pedidos'" class="space-y-6">
            @php
                $hoy = now()->toDateString();
                $pedidosPorFecha = collect([$hoy => $pedidos])->merge($pedidosPorDia);
            @endphp

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Pedidos recibidos</h2>

                <div class="relative">
                    <button type="button" @click="historialAbierto = !historialAbierto" class="w-full sm:w-auto flex items-center justify-between gap-4 bg-[#032B45] hover:bg-[#064363] text-white px-5 py-3 rounded-xl shadow-md transition-all">
                        <span class="text-xs font-bold" x-text="etiquetaPedidos"></span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="historialAbierto ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="historialAbierto" @click.outside="historialAbierto = false" x-transition style="display:none;" class="absolute right-0 top-full mt-2 w-full sm:w-72 bg-white border border-slate-200 rounded-2xl shadow-xl z-30 p-3 space-y-2">
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 px-3 py-2">Seleccionar fecha</p>

                        <button type="button" @click="fechaSeleccionada = '{{ $hoy }}'; etiquetaPedidos = 'Pedidos de hoy: {{ $pedidos->count() }}'; historialAbierto = false" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-left transition-colors" :class="fechaSeleccionada === '{{ $hoy }}' ? 'bg-[#00A88F]/10 text-[#032B45]' : 'hover:bg-slate-50 text-slate-700'">
                            <span class="text-xs font-bold">Hoy</span>
                            <span class="text-[10px] font-extrabold bg-white border border-slate-200 px-2.5 py-1 rounded-lg">{{ $pedidos->count() }} {{ $pedidos->count() === 1 ? 'pedido' : 'pedidos' }}</span>
                        </button>

                        @forelse($pedidosPorDia as $fechaDia => $pedidosDelDia)
                            <button type="button" @click="fechaSeleccionada = '{{ $fechaDia }}'; etiquetaPedidos = 'Pedidos del {{ \Carbon\Carbon::parse($fechaDia)->format('d/m/Y') }}: {{ $pedidosDelDia->count() }}'; historialAbierto = false" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-left transition-colors" :class="fechaSeleccionada === '{{ $fechaDia }}' ? 'bg-[#00A88F]/10 text-[#032B45]' : 'hover:bg-slate-50 text-slate-700'">
                                <span class="text-xs font-bold">{{ \Carbon\Carbon::parse($fechaDia)->format('d/m/Y') }}</span>
                                <span class="text-[10px] font-extrabold bg-white border border-slate-200 px-2.5 py-1 rounded-lg">{{ $pedidosDelDia->count() }} {{ $pedidosDelDia->count() === 1 ? 'pedido' : 'pedidos' }}</span>
                            </button>
                        @empty
                            <p class="text-xs text-slate-400 px-3 py-2">Todavía no hay pedidos anteriores.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="hidden lg:grid grid-cols-5 gap-4 px-6 text-xs font-black text-slate-400 uppercase tracking-wider">
                <div>Pedido</div>
                <div>Total</div>
                <div>Estado</div>
                <div>Información del cliente</div>
                <div>Acción</div>
            </div>

            @foreach($pedidosPorFecha as $fechaDia => $pedidosDelDia)
                <div x-show="fechaSeleccionada === '{{ $fechaDia }}'" x-transition class="space-y-4">
                    <div class="flex items-center justify-between bg-[#00A88F]/10 border border-[#00A88F]/30 rounded-2xl px-5 py-3">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#00A88F]"></span>
                            <h3 class="text-sm font-black text-[#032B45]">
                                {{ $fechaDia === $hoy ? 'Pedidos de hoy' : 'Pedidos del ' . \Carbon\Carbon::parse($fechaDia)->format('d/m/Y') }}
                            </h3>
                        </div>
                        <span class="text-xs font-extrabold text-[#008f7a] bg-white px-3 py-1.5 rounded-lg">
                            {{ $pedidosDelDia->count() }} {{ $pedidosDelDia->count() === 1 ? 'pedido' : 'pedidos' }}
                        </span>
                    </div>

                    @forelse($pedidosDelDia as $pedido)
                        @php
                            $estadoId = (int) $pedido->id_estado;

                            $estadoClase = match($estadoId) {
                                1 => 'bg-amber-100 text-amber-800 border-amber-300',
                                2 => 'bg-violet-100 text-violet-800 border-violet-300',
                                3 => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                4 => 'bg-green-700 text-white border-green-800',
                                5 => 'bg-rose-100 text-rose-800 border-rose-300',
                                6 => 'bg-blue-100 text-blue-800 border-blue-300',
                                7 => 'bg-orange-100 text-orange-800 border-orange-300',
                                default => 'bg-slate-100 text-slate-800 border-slate-300'
                            };
                        @endphp

                        <div class="bg-slate-300/95 border border-slate-400/80 p-6 rounded-[2rem] shadow-md grid grid-cols-1 lg:grid-cols-5 gap-6 items-center">

                            <div class="space-y-1">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Pedido</span>
                                <span class="text-lg font-black text-[#032B45]">#{{ $pedido->id_pedido }}</span>
                                <p class="text-xs font-bold text-slate-700">{{ $pedido->fecha?->format('d/m/Y H:i') }}</p>
                            </div>

                            <div class="space-y-1">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Total</span>
                                <span class="text-base font-extrabold text-slate-900">${{ number_format($pedido->total, 2, ',', '.') }}</span>
                            </div>

                            <div class="space-y-1">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Estado</span>
                                <span class="inline-block px-3 py-1.5 border rounded-xl text-xs font-extrabold shadow-2xs {{ $estadoClase }}">
                                    {{ $pedido->estadoPedido?->nombre ?? 'Sin estado' }}
                                </span>
                            </div>

                            <div class="space-y-2 lg:col-span-1" x-data="{ open: false }">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Información del cliente</span>

                                <button @click="open = !open" type="button" class="w-full text-xs font-bold text-[#032B45] hover:text-[#00A88F] bg-white px-4 py-2.5 rounded-xl border border-slate-300 shadow-2xs inline-flex items-center justify-between transition-colors">
                                    <span>Ver datos</span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition style="display:none;" class="w-full bg-white rounded-2xl p-3.5 border border-slate-300 shadow-inner text-xs space-y-1.5 text-slate-800">
                                    <div class="flex items-center justify-between pb-1 border-b border-slate-100 font-black text-[#032B45] text-[11px] uppercase">
                                        <span>Cliente</span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#00A88F]"></span>
                                    </div>
                                    <p><strong>Nombre:</strong> @if($pedido->cliente) {{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }} @else No vinculado @endif</p>
                                    <p><strong>Teléfono:</strong> {{ $pedido->cliente?->telefono ?? 'No disponible' }}</p>
                                    <p><strong>Email:</strong> {{ $pedido->cliente?->email ?? 'No disponible' }}</p>
                                    <p><strong>Dirección:</strong> {{ $pedido->direccion_entrega ?? 'Sin dirección' }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Acción</span>
                                <form method="POST" action="{{ route('dashboard.pedido.estado', $pedido->id_pedido) }}" class="space-y-2 w-full">
                                    @csrf
                                    <select name="id_estado" required class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-[#00A88F] shadow-2xs">
                                        <option value="1" {{ $estadoId === 1 ? 'selected' : '' }}>Pendiente</option>
                                        <option value="6" {{ $estadoId === 6 ? 'selected' : '' }}>Aceptado</option>
                                        <option value="2" {{ $estadoId === 2 ? 'selected' : '' }}>En preparación</option>
                                        <option value="3" {{ $estadoId === 3 ? 'selected' : '' }}>Listo para entregar</option>
                                        <option value="7" {{ $estadoId === 7 ? 'selected' : '' }}>En camino</option>
                                        <option value="4" {{ $estadoId === 4 ? 'selected' : '' }}>Entregado</option>
                                        <option value="5" {{ $estadoId === 5 ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    <button type="submit" class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-extrabold py-2 px-3 rounded-xl text-xs transition-colors shadow-2xs text-center">
                                        Actualizar estado
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50 border border-slate-200 rounded-[2rem]">
                            <p class="text-sm font-bold text-slate-600">No hay pedidos para esta fecha.</p>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </section>

        <section x-show="tab === 'productos'" class="space-y-6">
            <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Productos actuales</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($productos as $producto)
                    @php
                        $precio = $producto->precios->first();
                        $imagenProducto = $producto->imagen
                            ? asset('storage/' . ltrim($producto->imagen, '/'))
                            : null;
                    @endphp

                    <div class="bg-slate-300/95 border border-slate-400/80 p-5 rounded-[2rem] flex flex-col justify-between shadow-md space-y-4">
                        <div class="flex items-start gap-4">
                            @if($imagenProducto)
                                <img src="{{ $imagenProducto }}" alt="{{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-2xl border border-slate-300 shadow-sm flex-shrink-0 bg-white" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-16 h-16 bg-white border border-slate-300 rounded-2xl items-center justify-center text-[10px] font-bold text-slate-500 flex-shrink-0 hidden">
                                    Sin img
                                </div>
                            @else
                                <div class="w-16 h-16 bg-white border border-slate-300 rounded-2xl flex items-center justify-center text-[10px] font-bold text-slate-500 flex-shrink-0">
                                    Sin img
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-[#032B45] truncate text-base">{{ $producto->nombre }}</h4>
                                <p class="text-xs font-semibold text-slate-700 mt-1">
                                    Precio: <span class="text-[#00A88F] font-black">${{ number_format($precio?->precio ?? 0, 2, ',', '.') }}</span>
                                </p>
                                <p class="text-xs font-semibold text-slate-700">
                                    Stock: <span class="font-bold text-slate-900">{{ $producto->stock }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-400/60 space-y-2">
                            <details>
                                <summary class="text-xs font-bold text-[#00A88F] cursor-pointer hover:underline">Editar producto</summary>

                                <form method="POST" action="{{ route('dashboard.producto.update', $producto->id_producto) }}" enctype="multipart/form-data" class="mt-3 space-y-3 bg-white p-4 rounded-2xl border border-slate-300 text-left shadow-sm" x-data="{ fileName: '' }">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label class="text-[11px] font-bold text-slate-700 uppercase">Nombre</label>
                                        <input type="text" name="nombre" value="{{ $producto->nombre }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800">
                                    </div>

                                    <div>
                                        <label class="text-[11px] font-bold text-slate-700 uppercase">Descripción</label>
                                        <textarea name="descripcion" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800">{{ $producto->descripcion }}</textarea>
                                    </div>

                                    <div>
                                        <label class="text-[11px] font-bold text-slate-700 uppercase block mb-1">Cambiar imagen</label>
                                        <label class="flex items-center justify-center px-4 py-2 bg-[#032B45] hover:bg-[#00A88F] text-white rounded-xl text-xs font-bold cursor-pointer transition-colors shadow-2xs">
                                            <span>Seleccionar nueva foto</span>
                                            <input type="file" name="imagen" accept="image/*" class="hidden" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                        </label>
                                        <p class="text-[10px] text-slate-500 mt-1 italic truncate" x-text="fileName ? 'Archivo: ' + fileName : 'Ningún archivo nuevo seleccionado'"></p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-[11px] font-bold text-slate-700 uppercase">Stock</label>
                                            <input type="number" name="stock" min="0" value="{{ $producto->stock }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800">
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-slate-700 uppercase">Precio</label>
                                            <input type="number" name="precio" min="0" step="0.01" value="{{ $precio?->precio ?? 0 }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800">
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-2 px-4 rounded-xl text-xs transition-colors shadow-sm">
                                        Guardar cambios
                                    </button>
                                </form>
                            </details>

                            <form method="POST" action="{{ route('dashboard.producto.destroy', $producto->id_producto) }}" onsubmit="return confirm('¿Seguro que querés eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 hover:underline">
                                    Eliminar producto
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 font-medium col-span-full text-center py-8">No hay productos cargados aún.</p>
                @endforelse
            </div>
        </section>

        <section x-show="tab === 'agregar'" class="space-y-6 max-w-xl mx-auto">
            <div class="bg-slate-300/95 p-6 sm:p-8 rounded-[2.5rem] shadow-md border border-slate-400/80 space-y-6">
                <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Agregar producto nuevo</h2>

                <form method="POST" action="{{ route('dashboard.producto.store') }}" enctype="multipart/form-data" class="space-y-4" x-data="{ fileName: '' }">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Nombre del producto</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Descripción</label>
                        <textarea name="descripcion" rows="3" class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider pl-1 block mb-1">Imagen del producto</label>
                        <label class="flex items-center justify-center w-full px-4 py-3 bg-white hover:bg-slate-50 border border-slate-300 text-[#032B45] rounded-2xl text-sm font-bold cursor-pointer transition-all shadow-2xs">
                            <span>Elegir imagen del producto</span>
                            <input type="file" name="imagen" accept="image/*" class="hidden" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                        <p class="text-xs text-slate-600 mt-1.5 pl-1 italic" x-text="fileName ? 'Archivo seleccionado: ' + fileName : 'Ningún archivo seleccionado'"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Stock</label>
                            <input type="number" name="stock" min="0" value="{{ old('stock') }}" required class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Precio</label>
                            <input type="number" step="0.01" name="precio" min="0" value="{{ old('precio') }}" required class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#00A88F] hover:bg-[#008f7a] text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-[#00A88F]/20 transition-all text-base">
                        Guardar producto &rarr;
                    </button>
                </form>
            </div>
        </section>

        <section x-show="tab === 'configuracion'" class="space-y-6 max-w-xl mx-auto">
            <div class="bg-slate-300/95 p-6 sm:p-8 rounded-[2.5rem] shadow-md border border-slate-400/80 space-y-6">
                <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Configuración del negocio</h2>

                <div class="space-y-4 pb-4 border-b border-slate-400/60">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">Agregar tipo de precio</h3>
                    <form method="POST" action="{{ route('dashboard.tipos.precio.store') }}" class="space-y-3">
                        @csrf
                        <input type="text" name="nombre" placeholder="Ej: Mayorista, Promo, Familias" required class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                        <input type="text" name="descripcion" placeholder="Descripción opcional" class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                        <button type="submit" class="bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-sm">Guardar tipo de precio</button>
                    </form>
                </div>

                <div class="space-y-4 pb-4 border-b border-slate-400/60">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">Agregar tipo de entrega</h3>
                    <form method="POST" action="{{ route('dashboard.tipos.entrega.store') }}" class="space-y-3">
                        @csrf
                        <input type="text" name="nombre" placeholder="Ej: Delivery, Retiro, Express" required class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                        <button type="submit" class="bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-sm">Guardar tipo de entrega</button>
                    </form>
                </div>

                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">Agregar método de pago</h3>
                    <form method="POST" action="{{ route('dashboard.metodos.pago.store') }}" class="space-y-3">
                        @csrf
                        <input type="text" name="nombre" placeholder="Ej: Efectivo, POS" required class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:border-[#00A88F]">
                        <button type="submit" class="bg-[#032B45] hover:bg-[#00A88F] text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-sm">Guardar método de pago</button>
                    </form>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>
