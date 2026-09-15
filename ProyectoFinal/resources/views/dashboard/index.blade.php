<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f3f4f6; color: #111827; }
        .wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1rem; }
        .topbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 1rem 1.2rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .grid { display: grid; grid-template-columns: 1.1fr 1.4fr; gap: 1.5rem; }
        .card { background: white; border-radius: 12px; padding: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.04); }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border-bottom: 1px solid #e5e7eb; padding: .75rem .5rem; text-align: left; }
        .btn { background: #111827; color: white; border: none; border-radius: 8px; padding: 0.7rem 1rem; cursor: pointer; }
        .alert { background: #dcfce7; color: #166534; padding:.8rem 1rem; border-radius:8px; margin-bottom:1rem; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: .7rem; border-radius:8px; border: 1px solid #d1d5db; margin-bottom:.8rem; }
    </style>
</head>
<body>
    <div class="wrap">
       <div class="topbar">
    <div>
        <strong>Panel de {{ $comercio->nombre }}</strong>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">

        <a href="{{ route('comercio.show', $comercio->id_comercio) }}"
           class="btn"
           style="text-decoration:none; display:inline-block;">
            Ver mi tienda
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn">
                Salir
            </button>
        </form>

    </div>
</div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <div class="grid">
            <section class="card">
                <h2>Agregar producto</h2>
                <form method="POST" action="{{ route('dashboard.producto.store') }}">
                    @csrf
                    <input type="text" name="nombre" placeholder="Nombre del producto" required>
                    <textarea name="descripcion" placeholder="Descripción"></textarea>
                    <input type="number" name="stock" min="0" placeholder="Stock" required>
                    <input type="number" step="0.01" name="precio" min="0" placeholder="Precio" required>
                    <button type="submit" class="btn">Guardar</button>
                </form>
                <hr>
                <h3>Productos actuales</h3>
                <ul>
                    @forelse($productos as $producto)
                        @php $precio = $producto->precios->first(); @endphp
                        <li>
                            <strong>{{ $producto->nombre }}</strong>
                            - ${{ number_format($precio?->precio ?? 0, 2, ',', '.') }}
                            - Stock: {{ $producto->stock }}
                        </li>
                    @empty
                        <li>No hay productos aún.</li>
                    @endforelse
                </ul>
            </section>

            <section class="card">
                <h2>Configuración del negocio</h2>

                <div style="margin-bottom: 1.5rem;">
                    <h3>Agregar tipo de precio</h3>
                    <form method="POST" action="{{ route('dashboard.tipos.precio.store') }}">
                        @csrf
                        <input type="text" name="nombre" placeholder="Ej: Mayorista, Promo, Familias" required>
                        <input type="text" name="descripcion" placeholder="Descripción opcional">
                        <button type="submit" class="btn">Guardar tipo de precio</button>
                    </form>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h3>Agregar tipo de entrega</h3>
                    <form method="POST" action="{{ route('dashboard.tipos.entrega.store') }}">
                        @csrf
                        <input type="text" name="nombre" placeholder="Ej: Delivery, Retiro, Express" required>
                        <button type="submit" class="btn">Guardar tipo de entrega</button>
                    </form>
                </div>

                <div>
                    <h3>Agregar método de pago</h3>
                    <form method="POST" action="{{ route('dashboard.metodos.pago.store') }}">
                        @csrf
                        <input type="text" name="nombre" placeholder="Ej: Efectivo, Transferencia, Tarjeta" required>
                        <button type="submit" class="btn">Guardar método de pago</button>
                    </form>
                </div>
            </section>

            <section class="card">
                <h2>Pedidos recibidos</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td>
                                    <strong>#{{ $pedido->id_pedido }}</strong><br>
                                    {{ $pedido->fecha?->format('d/m/Y H:i') }}
                                </td>
                                <td>${{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td>{{ $pedido->estadoPedido?->nombre ?? 'Sin estado' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('dashboard.pedido.estado', $pedido->id_pedido) }}">
                                        @csrf
                                        <select name="id_estado">
                                            <option value="1">Pendiente</option>
                                            <option value="2">Confirmado</option>
                                            <option value="3">En preparación</option>
                                            <option value="4">Enviado</option>
                                            <option value="5">Entregado</option>
                                        </select>
                                        <button type="submit" class="btn" style="margin-top: .5rem;">Actualizar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Todavía no hay pedidos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>
