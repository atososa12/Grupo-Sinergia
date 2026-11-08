
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercios disponibles - Sinergia</title>
</head>
<body>

    <header>
        <h1>Comercios disponibles</h1>
        <p>Elegí el comercio donde querés realizar tu pedido.</p>
        <a href="{{ url('/') }}">Volver al inicio</a>
    </header>

    <main>
        @forelse ($comercios as $comercio)

            <section>
                <h2>{{ $comercio->nombre }}</h2>

                <p>
                    Dirección:
                    {{ $comercio->direccion ?? 'No especificada' }}
                </p>

                <p>
                    Teléfono:
                    {{ $comercio->telefono ?? 'No especificado' }}
                </p>

                <a href="{{ route('comercio.show', $comercio->id_comercio) }}">
                    Entrar a la tienda
                </a>
            </section>

            <hr>

        @empty
            <p>Actualmente no hay comercios disponibles.</p>
        @endforelse
    </main>

</body>
</html>
