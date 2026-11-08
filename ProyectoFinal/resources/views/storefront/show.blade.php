
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $comercio->nombre }}</title>
</head>
<body>

    <header>
        <h1>{{ $comercio->nombre }}</h1>
        <p>{{ $comercio->direccion ?? 'Dirección no especificada' }}</p>
        <p>{{ $comercio->telefono ?? 'Sin teléfono' }}</p>

        @if(Auth::guard('clientes')->check())
            <p>
                Sesión iniciada como:
                {{ $cliente->nombre }}
                {{ $cliente->apellido }}
            </p>

            <form method="POST" action="{{ route('cliente.logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('cliente.login', ['comercio' => $comercio->id_comercio]) }}">
                Iniciar sesión
            </a>

            <a href="{{ route('cliente.registro', ['comercio' => $comercio->id_comercio]) }}">
                Registrarse
            </a>
        @endif
    </header>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('info'))
        <p>{{ session('info') }}</p>
    @endif

    @if($errors->any())
        <div>
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <main>
        <section>
            <h2>Catálogo</h2>

            <div>
                @forelse($comercio->productos as $producto)
                    @php
                        $precio = $producto->precios->first();
                    @endphp

                    <div>
                        <h3>{{ $producto->nombre }}</h3>
                        <p>{{ $producto->descripcion }}</p>
                        <p>${{ number_format($precio?->precio ?? 0, 2, ',', '.') }}</p>

                        <label>
                            Cantidad:
                            <input
                                type="number"
                                min="1"
                                value="1"
                                data-product-id="{{ $producto->id_producto }}"
                            >
                        </label>

                        <button
                            type="button"
                            class="add-to-cart"
                            data-product-id="{{ $producto->id_producto }}"
                            data-product-name="{{ $producto->nombre }}"
                            data-price="{{ $precio?->precio ?? 0 }}"
                        >
                            Agregar
                        </button>
                    </div>
                @empty
                    <p>No hay productos disponibles todavía.</p>
                @endforelse
            </div>
        </section>

        <aside>
            <h2>Checkout</h2>

            <form method="POST" action="{{ route('comercio.pedido', $comercio->id_comercio) }}">
                @csrf

                <input type="hidden" name="items" id="items-input">

                <label>
                    Nombre:
                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $cliente ? $cliente->nombre . ' ' . $cliente->apellido : '') }}"
                        required
                    >
                </label>

                <label>
                    Teléfono:
                    <input
                        type="text"
                        name="telefono"
                        value="{{ old('telefono', $cliente?->telefono ?? '') }}"
                    >
                </label>

                <label>
                    Dirección de entrega:
                    <input
                        type="text"
                        name="direccion"
                        value="{{ old('direccion', $ultimaDireccion ?? '') }}"
                    >
                </label>

                <label>
                    Método de entrega:
                    <select name="tipo_entrega">
                        <option value="1" {{ old('tipo_entrega', '1') == '1' ? 'selected' : '' }}>
                            Delivery
                        </option>
                        <option value="2" {{ old('tipo_entrega') == '2' ? 'selected' : '' }}>
                            Retiro
                        </option>
                    </select>
                </label>

                <label>
                    Método de pago:
                    <select name="metodo_pago">
                        <option value="1" {{ old('metodo_pago', '1') == '1' ? 'selected' : '' }}>
                            Efectivo
                        </option>
                        <option value="2" {{ old('metodo_pago') == '2' ? 'selected' : '' }}>
                            POS
                        </option>
                    </select>
                </label>

                <div id="cart-summary">
                    Tu carrito está vacío.
                </div>

                <button type="submit">
                    Enviar pedido
                </button>
            </form>
        </aside>
    </main>

    <script>
        const cart = {};
        const itemsInput = document.getElementById('items-input');
        const cartSummary = document.getElementById('cart-summary');

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

                const lines = Object.values(cart).map(function (item) {
                    return item.nombre + ' x' + item.cantidad + ' - $' +
                        (item.cantidad * item.precio).toFixed(2);
                });

                cartSummary.innerHTML = lines.join('<br>');
                itemsInput.value = JSON.stringify(Object.values(cart));
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
