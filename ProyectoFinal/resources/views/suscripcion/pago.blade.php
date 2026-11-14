
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suscripción - Sinergia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: #f3f6f8;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen">

    @php
        $estadoSuscripcion = $suscripcion->estado;

        $estadosSuscripcion = [
            'pendiente_pago' => ['Pendiente de pago', 'bg-amber-100 text-amber-800'],
            'activa' => ['Activa', 'bg-green-100 text-green-800'],
            'vencida' => ['Vencida', 'bg-red-100 text-red-800'],
            'suspendida' => ['Suspendida', 'bg-red-100 text-red-800'],
            'cancelada' => ['Cancelada', 'bg-gray-100 text-gray-700'],
        ];

        $estadoActual = $estadosSuscripcion[$estadoSuscripcion] ?? ['Sin estado', 'bg-gray-100 text-gray-700'];

        $tienePagoPendiente = $pagos->contains(function ($pago) {
            return in_array($pago->estado, ['pendiente', 'en_revision']);
        });
    @endphp

    <header class="bg-[#032B45] text-white px-6 py-5">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">SINERGIA</h1>
                <p class="text-sm text-gray-300">Administración de tu suscripción</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg text-sm">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-10">

        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Bienvenido a Sinergia</p>
                    <h2 class="text-2xl font-bold text-[#032B45]">
                        {{ $comercio->nombre }}
                    </h2>

                    @if($estadoSuscripcion === 'activa')
                        <p class="text-gray-600 mt-2">
                            Tu suscripción está activa. Podés consultar tus pagos y gestionar tus próximas renovaciones.
                        </p>
                    @else
                        <p class="text-gray-600 mt-2">
                            Para comenzar a utilizar tu tienda, necesitamos verificar el pago de tu suscripción.
                        </p>
                    @endif
                </div>

                <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $estadoActual[1] }}">
                    {{ $estadoActual[0] }}
                </span>
            </div>

            @if($suscripcion->fecha_vencimiento)
                <div class="mt-5 border-t pt-4">
                    <p class="text-sm text-gray-500">Vencimiento de tu suscripción</p>
                    <p class="text-lg font-bold text-[#032B45]">
                        {{ $suscripcion->fecha_vencimiento->format('d/m/Y') }}
                    </p>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-xl p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 rounded-xl p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 rounded-xl p-4 mb-6">
                <p class="font-semibold mb-2">Revisá los siguientes errores:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            <section class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-[#032B45] mb-4">
                    Tu plan
                </h3>

                <p class="text-sm text-gray-500">Precio promocional</p>

                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-bold text-[#00A88F]">
                        ${{ number_format((float) $suscripcion->monto, 0, ',', '.') }}
                    </span>
                    <span class="text-gray-500">UYU / mes</span>
                </div>

                <div class="mt-5 space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between border-b pb-3">
                        <span>Duración de la promoción</span>
                        <strong>{{ $suscripcion->meses_promocion }} meses</strong>
                    </div>

                    <div class="flex justify-between border-b pb-3">
                        <span>Periodicidad</span>
                        <strong>Mensual</strong>
                    </div>

                    <div class="flex justify-between">
                        <span>Estado</span>
                        <strong>{{ $estadoActual[0] }}</strong>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-5">
                    El precio promocional de $5.000 UYU mensuales corresponde al primer año de servicio.
                </p>
            </section>

            <section class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-[#032B45] mb-4">
                    Cómo funciona tu suscripción
                </h3>

                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#00A88F] text-white flex items-center justify-center font-bold">1</div>
                        <div>
                            <p class="font-semibold text-gray-800">Realizá la transferencia</p>
                            <p class="text-sm text-gray-500">Transferí el importe mensual a una de las cuentas de Sinergia.</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#00A88F] text-white flex items-center justify-center font-bold">2</div>
                        <div>
                            <p class="font-semibold text-gray-800">Subí el comprobante</p>
                            <p class="text-sm text-gray-500">Adjuntá una imagen o PDF de la transferencia realizada.</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#00A88F] text-white flex items-center justify-center font-bold">3</div>
                        <div>
                            <p class="font-semibold text-gray-800">Esperá la verificación</p>
                            <p class="text-sm text-gray-500">Nuestro equipo revisará el pago y actualizará tu suscripción.</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <section class="bg-white rounded-2xl shadow-sm p-6 md:p-8 mb-6">
            <h3 class="text-xl font-bold text-[#032B45] mb-2">
                Cuentas bancarias de Sinergia
            </h3>

            <p class="text-sm text-gray-500 mb-6">
                Elegí una cuenta y realizá la transferencia por el importe de tu suscripción.
            </p>

            @forelse($cuentas as $cuenta)
                <div class="border border-gray-200 rounded-xl p-5 mb-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-3">
                        <div>
                            <h4 class="font-bold text-[#032B45] text-lg">{{ $cuenta->banco }}</h4>
                            <p class="text-sm text-gray-500">Titular: {{ $cuenta->titular }}</p>
                        </div>

                        <span class="text-xs font-semibold bg-green-100 text-green-700 rounded-full px-3 py-1 h-fit">
                            Cuenta activa
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 text-sm">
                        <div>
                            <p class="text-gray-500">Número de cuenta</p>
                            <p class="font-semibold text-gray-800 break-all">{{ $cuenta->numero_cuenta }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500">Alias</p>
                            <p class="font-semibold text-gray-800">{{ $cuenta->alias ?: 'No disponible' }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500">Moneda</p>
                            <p class="font-semibold text-gray-800">{{ $cuenta->moneda }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4">
                    Todavía no hay cuentas bancarias disponibles. Contactá con Sinergia para obtener los datos de pago.
                </div>
            @endforelse
        </section>

        <section class="bg-white rounded-2xl shadow-sm p-6 md:p-8 mb-6">
            <h3 class="text-xl font-bold text-[#032B45] mb-2">
                Enviar comprobante de pago
            </h3>

            <p class="text-sm text-gray-500 mb-6">
                Una vez realizada la transferencia, completá los siguientes datos.
            </p>

            @if($tienePagoPendiente)
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4">
                    Ya recibimos tu comprobante. Estamos verificando tu transferencia. No necesitás enviarlo nuevamente.
                </div>
            @elseif($cuentas->isNotEmpty())
                <form action="{{ route('suscripcion.comprobante') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="id_cuenta" class="block text-sm font-semibold text-gray-700 mb-2">
                            Cuenta bancaria a la que transferiste
                        </label>

                        <select name="id_cuenta" id="id_cuenta" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A88F] focus:outline-none">
                            <option value="">Seleccioná una cuenta</option>
                            @foreach($cuentas as $cuenta)
                                <option value="{{ $cuenta->id_cuenta }}" {{ old('id_cuenta') == $cuenta->id_cuenta ? 'selected' : '' }}>
                                    {{ $cuenta->banco }} - {{ $cuenta->titular }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="comprobante" class="block text-sm font-semibold text-gray-700 mb-2">
                            Comprobante de transferencia
                        </label>

                        <input type="file" name="comprobante" id="comprobante" accept=".jpg,.jpeg,.png,.pdf" required class="w-full border border-gray-300 rounded-xl px-4 py-3">

                        <p class="text-xs text-gray-500 mt-2">
                            Formatos permitidos: JPG, PNG o PDF. Tamaño máximo: 5 MB.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Total a transferir</span>
                        <strong class="text-2xl text-[#00A88F]">
                            ${{ number_format((float) $suscripcion->monto, 0, ',', '.') }} UYU
                        </strong>
                    </div>

                    <button type="submit" class="w-full bg-[#00A88F] hover:bg-[#008f79] text-white font-bold rounded-xl py-4 transition">
                        Enviar comprobante
                    </button>
                </form>
            @else
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4">
                    No hay cuentas bancarias disponibles para realizar el pago.
                </div>
            @endif
        </section>

        <section class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-[#032B45]">
                        Historial de pagos
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Consultá todas las transferencias que enviaste a Sinergia.
                    </p>
                </div>

                <span class="text-sm text-gray-500">
                    {{ $pagos->count() }} {{ $pagos->count() === 1 ? 'pago registrado' : 'pagos registrados' }}
                </span>
            </div>

            @forelse($pagos as $pago)
                <div class="border border-gray-200 rounded-xl p-5 mb-4 last:mb-0">

                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <p class="text-xl font-bold text-[#032B45]">
                                ${{ number_format((float) $pago->monto, 2, ',', '.') }} UYU
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Pago #{{ $pago->id_pago_suscripcion }}
                            </p>
                        </div>

                        @if($pago->estado === 'aprobado')
                            <span class="bg-green-100 text-green-800 rounded-full px-3 py-2 text-xs font-semibold w-fit">
                                Aprobado
                            </span>
                        @elseif($pago->estado === 'rechazado')
                            <span class="bg-red-100 text-red-800 rounded-full px-3 py-2 text-xs font-semibold w-fit">
                                Rechazado
                            </span>
                        @elseif($pago->estado === 'en_revision')
                            <span class="bg-amber-100 text-amber-800 rounded-full px-3 py-2 text-xs font-semibold w-fit">
                                En revisión
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-2 text-xs font-semibold w-fit">
                                Pendiente
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-5 text-sm">
                        <div>
                            <p class="text-gray-500">Fecha de envío</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $pago->fecha_creacion ? \Illuminate\Support\Carbon::parse($pago->fecha_creacion)->format('d/m/Y H:i') : 'Sin fecha' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Fecha de revisión</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $pago->fecha_revision ? $pago->fecha_revision->format('d/m/Y H:i') : 'Pendiente de revisión' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Período abonado</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $pago->periodo_desde?->format('d/m/Y') ?? 'Sin definir' }}
                                al
                                {{ $pago->periodo_hasta?->format('d/m/Y') ?? 'Sin definir' }}
                            </p>
                        </div>
                    </div>

                    @if($pago->estado === 'rechazado' && $pago->observaciones)
                        <div class="mt-5 bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-sm font-bold text-red-800">
                                Motivo del rechazo
                            </p>
                            <p class="text-sm text-red-700 mt-1">
                                {{ $pago->observaciones }}
                            </p>
                        </div>
                    @endif

                    @if($pago->comprobante)
                        <div class="mt-5">
                            <a
                                href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pago->comprobante) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center rounded-lg border border-[#032B45] px-4 py-2 text-sm font-semibold text-[#032B45] hover:bg-gray-50"
                            >
                                Ver comprobante enviado
                            </a>
                        </div>
                    @endif

                </div>
            @empty
                <div class="bg-gray-50 rounded-xl p-8 text-center">
                    <h4 class="font-bold text-[#032B45]">
                        Todavía no tenés pagos registrados
                    </h4>
                    <p class="text-sm text-gray-500 mt-2">
                        Cuando envíes tu primer comprobante, aparecerá en esta sección.
                    </p>
                </div>
            @endforelse
        </section>

    </main>

    <footer class="text-center text-sm text-gray-400 py-8">
        Sinergia - Plataforma para comercios
    </footer>

</body>
</html>
