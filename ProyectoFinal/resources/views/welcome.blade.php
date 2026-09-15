
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sinergia</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #222;
        }

        header {
            background: white;
            border-bottom: 1px solid #e5e5e5;
            padding: 20px 60px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #d35400;
        }

        nav {
            display: flex;
            gap: 15px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            padding: 10px 18px;
            border-radius: 6px;
        }

        nav a:hover {
            background: #f1f1f1;
        }

        .btn {
            background: #d35400;
            color: white !important;
        }

        .btn:hover {
            background: #b84300 !important;
        }

        .hero {
            min-height: 70vh;

            display: flex;
            justify-content: center;
            align-items: center;

            text-align: center;
            padding: 40px 20px;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 52px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #d35400;
        }

        .hero p {
            font-size: 20px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 35px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .main-button {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .primary {
            background: #d35400;
            color: white;
        }

        .primary:hover {
            background: #b84300;
        }

        .secondary {
            background: white;
            color: #d35400;
            border: 2px solid #d35400;
        }

        .secondary:hover {
            background: #fff3ec;
        }

        .features {
            background: white;
            padding: 60px 30px;
        }

        .features-container {
            max-width: 1000px;
            margin: auto;

            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .feature {
            text-align: center;
            padding: 30px 20px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fafafa;
        }

        .feature h3 {
            margin-bottom: 12px;
        }

        .feature p {
            color: #666;
            line-height: 1.5;
        }

        footer {
            text-align: center;
            padding: 25px;
            background: #222;
            color: white;
        }

        @media (max-width: 700px) {

            header {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
            }

            .hero h1 {
                font-size: 38px;
            }

            .hero p {
                font-size: 17px;
            }

            .features-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header>

    <div class="logo">
        Sinergia
    </div>

    <nav>

        <a href="{{ route('login') }}">
            Iniciar sesión
        </a>

        <a href="{{ route('registro.negocio') }}" class="btn">
            Registrar mi comercio
        </a>

    </nav>

</header>


<main>

    <section class="hero">

        <div class="hero-content">

            <h1>
                Gestioná tu comercio con
                <span>Sinergia</span>
            </h1>

            <p>
                Una plataforma pensada para pequeños comercios,
                que permite gestionar productos, pedidos y clientes
                desde un mismo lugar.
            </p>

            <div class="buttons">

                <a href="{{ route('login') }}" class="main-button primary">
                    Iniciar sesión
                </a>

                <a href="{{ route('registro.negocio') }}" class="main-button secondary">
                    Registrar mi comercio
                </a>

            </div>

        </div>

    </section>


    <section class="features">

        <div class="features-container">

            <div class="feature">

                <h3>
                    Gestión de productos
                </h3>

                <p>
                    Administrá los productos y precios
                    disponibles para tus clientes.
                </p>

            </div>


            <div class="feature">

                <h3>
                    Gestión de pedidos
                </h3>

                <p>
                    Recibí y gestioná los pedidos
                    realizados desde tu tienda.
                </p>

            </div>


            <div class="feature">

                <h3>
                    Tienda online
                </h3>

                <p>
                    Ofrecé a tus clientes una tienda
                    accesible desde cualquier dispositivo.
                </p>

            </div>

        </div>

    </section>

</main>


<footer>

    <p>
        © {{ date('Y') }} Sinergia. Todos los derechos reservados.
    </p>

</footer>

</body>
</html>
```
