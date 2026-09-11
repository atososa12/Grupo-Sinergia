<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; display:flex; align-items:center; justify-content:center; min-height:100vh;">
    <div style="background:white; padding: 2rem; border-radius: 12px; width: min(420px, 90vw); box-shadow: 0 8px 30px rgba(0,0,0,0.08);">
        <h1 style="margin-top:0;">Acceso al negocio</h1>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display:block; margin-bottom:0.4rem;">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required style="width:100%; padding:0.8rem; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="password" style="display:block; margin-bottom:0.4rem;">Contraseña</label>
                <input id="password" name="password" type="password" required style="width:100%; padding:0.8rem; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box;">
            </div>
            <button type="submit" style="width:100%; background:#111827; color:white; border:none; padding:0.9rem; border-radius:8px; font-weight:bold; cursor:pointer;">
                Ingresar
            </button>
        </form>
    </div>
</body>
</html>
