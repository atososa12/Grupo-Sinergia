<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de negocio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: white;
            width: min(760px, 92vw);
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        h1 {
            margin-top: 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem 1.25rem;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .field.full {
            grid-column: 1 / -1;
        }
        label {
            font-weight: 600;
        }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 0.8rem 0.9rem;
            border-radius: 10px;
            border: 1px solid #d1d5db;
        }
        button {
            margin-top: 1rem;
            width: 100%;
            border: none;
            background: #111827;
            color: white;
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
        }
        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .small-link {
            display: inline-block;
            margin-top: 1rem;
            color: #2563eb;
            text-decoration: none;
        }
        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Registrar mi negocio</h1>

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('registro.negocio.submit') }}">
            @csrf
            <div class="grid">
                <div class="field full">
                    <label for="nombre_comercio">Nombre del comercio</label>
                    <input id="nombre_comercio" name="nombre_comercio" type="text" value="{{ old('nombre_comercio') }}" required>
                </div>

                <div class="field full">
                    <label for="direccion">Dirección</label>
                    <input id="direccion" name="direccion" type="text" value="{{ old('direccion') }}">
                </div>

                <div class="field">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" type="text" value="{{ old('telefono') }}">
                </div>

                <div class="field">
                    <label for="email_comercio">Email del comercio</label>
                    <input id="email_comercio" name="email_comercio" type="email" value="{{ old('email_comercio') }}">
                </div>

                <div class="field">
                    <label for="nombre">Nombre del dueño</label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required>
                </div>

                <div class="field">
                    <label for="apellido">Apellido</label>
                    <input id="apellido" name="apellido" type="text" value="{{ old('apellido') }}" required>
                </div>

                <div class="field">
                    <label for="email">Email de acceso</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="telefono_owner">Teléfono del dueño</label>
                    <input id="telefono_owner" name="telefono" type="text" value="{{ old('telefono') }}">
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>

            <button type="submit">Crear negocio y entrar al panel</button>
        </form>

        <a class="small-link" href="{{ route('login') }}">Ya tengo una cuenta</a>
    </div>
</body>
</html>
