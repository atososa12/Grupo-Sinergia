<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Sinergia</title>
    
</head>
<body>

    <h1>Crear cuenta</h1>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('cliente.registro.submit') }}" method="POST">
        @csrf

        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required>

        <label>Apellido</label>
        <input type="text" name="apellido" value="{{ old('apellido') }}" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono') }}" required>

        <label>Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Confirmar contraseña</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Registrarme</button>
    </form>

    <a href="{{ route('cliente.login', request()->only('comercio')) }}">Iniciá sesión</a>

</body>
</html>
