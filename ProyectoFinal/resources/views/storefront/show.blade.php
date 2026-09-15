<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $comercio->nombre }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f6f7fb; color: #111827; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem 3rem; }
        .header { background: #fff; border-radius: 14px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(17,24,39,0.05); margin-bottom: 2rem; }
        .grid { display: grid; grid-template-columns: 1.6fr 0.9fr; gap: 1.5rem; }
        .card { background: #fff; border-radius: 14px; padding: 1rem 1.2rem; box-shadow: 0 10px 30px rgba(17,24,39,0.04); }
        .product-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
        .product { border: 1px solid #e5e7eb; border-radius: 10px; padding: 1rem; }
        .price { font-weight: bold; color: #065f46; }
        .qty { width: 60px; padding: .5rem; border-radius: 6px; border:1px solid #d1d5db; }
        .btn { background: #111827; color: white; border: none; border-radius: 8px; padding: .7rem 1rem; cursor: pointer; }
        .checkout input, .checkout select { width: 100%; box-sizing: border-box; padding: .7rem; border-radius: 8px; border: 1px solid #d1d5db; margin-top: .2rem; margin-bottom: .9rem; }
        .success { background: #dcfce7; color: #166534; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <header class="header">
            <h1>{{ $comercio->nombre }}</h1>
            <p>{{ $comercio->direccion ?? 'Dirección no especificada' }}</p>
            <p>{{ $comercio->telefono ?? 'Sin teléfono' }}</p>
        </header>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="grid">
            <section class="card">
                <h2>Catálogo</h2>
                <div class="product-list">
                    @forelse($comercio->productos as $producto)
                        @php $precio = $producto->precios->first(); @endphp
                        <div class="product">
                            <h3>{{ $producto->nombre }}</h3>
                            <p>{{ $producto->descripcion }}</p>
                            <p class="price">${{ number_format($precio?->precio ?? 0, 2, ',', '.') }}</p>
                            <label>
                                Cantidad:
                                <input type="number" class="qty" min="1" value="1" data-product-id="{{ $producto->id_producto }}">
                            </label>
                            <button type="button" class="btn add-to-cart" data-product-id="{{ $producto->id_producto }}" data-product-name="{{ $producto->nombre }}" data-price="{{ $precio?->precio ?? 0 }}">
                                Agregar
                            </button>
                        </div>
                    @empty
                        <p>No hay productos disponibles todavía.</p>
                    @endforelse
                </div>
            </section>

            <aside class="card checkout">
                <h2>Checkout</h2>
                <form method="POST" action="{{ route('comercio.pedido', $comercio->id_comercio) }}">
                    @csrf
                    <input type="hidden" name="items" id="items-input">

                    <label>
                        Nombre
                        <input type="text" name="nombre" required>
                    </label>

                    <label>
                        Teléfono
                        <input type="text" name="telefono">
                    </label>

                    <label>
                        Dirección de entrega
                        <input type="text" name="direccion">
                    </label>

                    <label>
                        Método de entrega
                        <select name="tipo_entrega">
                            <option value="1">Delivery</option>
                            <option value="2">Retiro</option>
                        </select>
                    </label>

                    <label>
                        Método de pago
                        <select name="metodo_pago">
                            <option value="1">Efectivo</option>
                            <option value="2">Transferencia</option>
                            <option value="3">Mercado Pago</option>
                        </select>
                    </label>

                    <div id="cart-summary" style="margin: 1rem 0; padding: .8rem; background:#f3f4f6; border-radius:8px; min-height:50px;">Tu carrito está vacío.</div>

                    <button type="submit" class="btn" style="width:100%;">Enviar pedido</button>
                </form>
            </aside>
        </div>
    </div>

    <script>
        const cart = {};
        const itemsInput = document.getElementById('items-input');
        const cartSummary = document.getElementById('cart-summary');

        document.querySelectorAll('.add-to-cart').forEach(function (button) {
            button.addEventListener('click', function () {
                const productId = Number(this.dataset.productId);
                const productName = this.dataset.productName;
                const price = Number(this.dataset.price);
                const qtyInput = document.querySelector('[data-product-id="' + productId + '"]');
                const qty = Number(qtyInput ? qtyInput.value : 1);

                if (!productId || qty < 1) return;

                if (!cart[productId]) {
                    cart[productId] = { id_producto: productId, cantidad: 0, nombre: productName, precio: price };
                }

                cart[productId].cantidad += qty;
                const lines = Object.values(cart).map(function (item) {
                    return item.nombre + ' x' + item.cantidad + ' - $' + (item.cantidad * item.precio).toFixed(2);
                });

                cartSummary.innerHTML = lines.join('<br>');
                itemsInput.value = JSON.stringify(Object.values(cart));
            });
        });
    </script>
</body>
</html>
