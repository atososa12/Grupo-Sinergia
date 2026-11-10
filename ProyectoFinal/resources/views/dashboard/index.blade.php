<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - {{ $comercio->nombre }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#032B45] text-slate-800 font-sans antialiased min-h-screen p-4 sm:p-6 lg:p-10 relative overflow-x-hidden" x-data="{ tab: 'pedidos' }">

<div class="max-w-7xl mx-auto space-y-6 relative z-10">

    <header class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-2xl flex flex-col sm:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="relative flex-shrink-0">
                @php
                    $nombreLimpio = strtolower($comercio->nombre);
                    $logoRuta = asset('img/logo-sinergia.png');

                    if (str_contains($nombreLimpio, 'cuarta')) {
                        $logoRuta = asset('img/la-cuarta-masa/logo-la-cuarta-masa.png');
                    } elseif (str_contains($nombreLimpio, 'don pepe')) {
                        $logoRuta = asset('img/don-pepe/logo-don-pepe.png');
                    }
                @endphp

                <img 
                    src="{{ $logoRuta }}" 
                    alt="Logo {{ $comercio->nombre }}" 
                    class="h-24 w-24 sm:h-28 sm:w-28 rounded-full object-cover p-1 bg-white shadow-xl ring-4 ring-[#00A88F]"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >
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

    <nav class="bg-white p-2.5 rounded-2xl shadow-xl grid grid-cols-2 md:grid-cols-4 gap-2">
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

        <section x-show="tab === 'pedidos'" class="space-y-6">
            <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Pedidos recibidos</h2>

            <div class="hidden lg:grid grid-cols-5 gap-4 px-6 text-xs font-black text-slate-400 uppercase tracking-wider">
                <div>Pedido</div>
                <div>Total</div>
                <div>Estado</div>
                <div>Información del cliente</div>
                <div>Acción</div>
            </div>

            <div class="space-y-4">
                @forelse($pedidos as $pedido)
                    @php
                        $estadoId = $pedido->id_estado;
                        $estadoClase = match((int)$estadoId) {
                            1 => 'bg-amber-100 text-amber-800 border-amber-300',      // Pendiente: Amarillo
                            2 => 'bg-blue-100 text-blue-800 border-blue-300',          // En preparación: Azul
                            3 => 'bg-emerald-100 text-emerald-800 border-emerald-300', // Listo para entregar: Verde claro
                            4 => 'bg-green-600 text-white border-green-700',           // Entregado: Verde fuerte
                            5 => 'bg-rose-100 text-rose-800 border-rose-300',          // Cancelado: Rojo
                            default => 'bg-white text-slate-900 border-slate-300'
                        };
                    @endphp

           
                    <div class="bg-slate-300/95 border border-slate-400/80 p-6 rounded-[2rem] shadow-md grid grid-cols-1 lg:grid-cols-5 gap-6 items-center">

                        <div class="space-y-1">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Pedido</span>
                            <span class="text-lg font-black text-[#032B45]">#{{ $pedido->id_pedido }}</span>
                            <p class="text-xs font-bold text-slate-700">
                                {{ $pedido->fecha?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Total</span>
                            <span class="text-base font-extrabold text-slate-900">
                                ${{ number_format($pedido->total, 2, ',', '.') }}
                            </span>
                        </div>

                        <div class="space-y-1">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Estado</span>
                            <span class="inline-block px-3 py-1.5 border rounded-xl text-xs font-extrabold shadow-2xs {{ $estadoClase }}">
                                {{ $pedido->estadoPedido?->nombre ?? 'Sin estado' }}
                            </span>
                        </div>

                        <div class="space-y-2 lg:col-span-1" x-data="{ open: false }">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider block lg:hidden">Información del cliente</span>
                            
                            <button 
                                @click="open = !open" 
                                type="button"
                                class="w-full text-xs font-bold text-[#032B45] hover:text-[#00A88F] bg-white px-4 py-2.5 rounded-xl border border-slate-300 shadow-2xs inline-flex items-center justify-between transition-colors"
                            >
                                <span>Ver datos</span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div 
                                x-show="open" 
                                x-transition 
                                style="display: none;"
                                class="w-full bg-white rounded-2xl p-3.5 border border-slate-300 shadow-inner text-xs space-y-1.5 text-slate-800"
                            >
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
                                    <option value="1" {{ $pedido->id_estado == 1 ? 'selected' : '' }}>Pendiente</option>
                                    <option value="2" {{ $pedido->id_estado == 2 ? 'selected' : '' }}>En preparación</option>
                                    <option value="3" {{ $pedido->id_estado == 3 ? 'selected' : '' }}>Listo para entregar</option>
                                    <option value="4" {{ $pedido->id_estado == 4 ? 'selected' : '' }}>Entregado</option>
                                    <option value="5" {{ $pedido->id_estado == 5 ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                <button type="submit" class="w-full bg-[#032B45] hover:bg-[#00A88F] text-white font-extrabold py-2 px-3 rounded-xl text-xs transition-colors shadow-2xs text-center">
                                    Actualizar estado
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <p class="text-sm text-slate-400 font-medium text-center py-8">Todavía no hay pedidos registrados.</p>
                @endforelse
            </div>
        </section>

        <section x-show="tab === 'productos'" class="space-y-6" style="display: none;">
            <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Productos actuales</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($productos as $producto)
                    @php
                        $precio = $producto->precios->first();
                    @endphp

                    <div class="bg-slate-300/95 border border-slate-400/80 p-5 rounded-[2rem] flex flex-col justify-between shadow-md space-y-4">
                        <div class="flex items-start gap-4">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-2xl border border-slate-300 shadow-sm flex-shrink-0 bg-white">
                            @else
                                <div class="w-16 h-16 bg-white border border-slate-300 rounded-2xl flex items-center justify-center text-[10px] font-bold text-slate-500 flex-shrink-0">Sin img</div>
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

                                <form method="POST" action="{{ route('dashboard.producto.update', $producto->id_producto) }}" enctype="multipart/form-data" class="mt-3 space-y-3 bg-white p-4 rounded-2xl border border-slate-300 text-left shadow-sm">
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
                                        <label class="text-[11px] font-bold text-slate-700 uppercase">Cambiar imagen</label>
                                        <input type="file" name="imagen" accept="image/*" class="w-full text-xs text-slate-500">
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

        <section x-show="tab === 'agregar'" class="space-y-6 max-w-xl mx-auto" style="display: none;">
            <div class="bg-slate-300/95 p-6 sm:p-8 rounded-[2.5rem] shadow-md border border-slate-400/80 space-y-6">
                <h2 class="text-xl sm:text-2xl font-black text-[#032B45] tracking-tight">Agregar producto nuevo</h2>

                <form method="POST" action="{{ route('dashboard.producto.store') }}" enctype="multipart/form-data" class="space-y-4">
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
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Imagen del producto</label>
                        <input type="file" name="imagen" accept="image/*" class="w-full bg-white border border-slate-300 rounded-2xl px-4 py-2 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#032B45] file:text-white hover:file:bg-[#00A88F]">
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

        <section x-show="tab === 'configuracion'" class="space-y-6 max-w-xl mx-auto" style="display: none;">
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