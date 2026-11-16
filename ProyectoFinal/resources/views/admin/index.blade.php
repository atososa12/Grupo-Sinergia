
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Sinergia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">

    <header class="bg-[#032B45] text-white shadow-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
            <div>
                <h1 class="text-2xl font-bold">SINERGIA</h1>
                <p class="text-sm text-slate-300">Panel de administración</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="rounded-lg bg-white/10 px-5 py-2 text-sm font-semibold hover:bg-white/20">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#032B45]">Administración de Sinergia</h2>
            <p class="mt-2 text-slate-500">
                Gestioná las suscripciones, renovaciones y pagos de los comercios.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="mb-8 grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total de pagos</p>
                <p class="mt-2 text-3xl font-bold text-[#032B45]">
                    {{ $pagos->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Pendientes de revisión</p>
                <p class="mt-2 text-3xl font-bold text-amber-500">
                    {{ $pagos->whereIn('estado', ['pendiente', 'en_revision'])->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Pagos aprobados</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ $pagos->where('estado', 'aprobado')->count() }}
                </p>
            </div>
        </div>

        <section class="mb-10 rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-[#032B45]">
                    Suscripciones y renovaciones
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Generá los pagos mensuales de los comercios registrados.
                </p>
            </div>

            <div class="space-y-4">
                @forelse($comercios as $comercio)
                    @php
                        $suscripcion = $comercio->suscripciones
                            ->sortByDesc('id_suscripcion')
                            ->first();

                        $tienePendiente = $suscripcion
                            ? $pagos->contains(function ($pago) use ($suscripcion) {
                                return $pago->id_suscripcion == $suscripcion->id_suscripcion
                                    && in_array($pago->estado, ['pendiente', 'en_revision']);
                            })
                            : false;
                    @endphp

                    <div class="flex flex-col justify-between gap-5 rounded-xl border border-slate-200 p-5 md:flex-row md:items-center">
                        <div>
                            <h4 class="text-lg font-bold text-[#032B45]">
                                {{ $comercio->nombre }}
                            </h4>

                            @if($suscripcion)
                                <p class="mt-1 text-sm text-slate-500">
                                    Suscripción #{{ $suscripcion->id_suscripcion }}
                                </p>

                                <p class="mt-1 text-sm text-slate-600">
                                    Estado:
                                    <span class="font-semibold">
                                        {{ ucfirst(str_replace('_', ' ', $suscripcion->estado)) }}
                                    </span>
                                </p>

                                <p class="mt-1 text-sm text-slate-600">
                                    Vencimiento:
                                    <span class="font-semibold">
                                        {{ $suscripcion->fecha_vencimiento
                                            ? \Carbon\Carbon::parse($suscripcion->fecha_vencimiento)->format('d/m/Y')
                                            : 'Sin fecha de vencimiento' }}
                                    </span>
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#032B45]">
                                    ${{ number_format((float) ($suscripcion->monto_renovacion ?? $suscripcion->monto), 2, ',', '.') }} UYU
                                </p>
                            @else
                                <p class="mt-2 text-sm text-red-500">
                                    Este comercio no tiene una suscripción registrada.
                                </p>
                            @endif
                        </div>

                        <div>
                            @if($suscripcion)
                                @if($tienePendiente)
                                    <span class="inline-flex rounded-lg bg-amber-100 px-4 py-3 text-sm font-semibold text-amber-700">
                                        Tiene un pago pendiente o en revisión
                                    </span>
                                @else
                                    <form
                                        action="{{ route('admin.comercios.renovacion', $comercio->id_comercio) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            onclick="return confirm('¿Querés generar un nuevo pago de renovación para este comercio?')"
                                            class="rounded-lg bg-[#032B45] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#064568]"
                                        >
                                            Generar renovación
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-slate-500">
                        Todavía no hay comercios registrados.
                    </div>
                @endforelse
            </div>
        </section>

        <section>
            <div class="mb-5">
                <h3 class="text-2xl font-bold text-[#032B45]">
                    Pagos de suscripciones
                </h3>
                <p class="mt-1 text-slate-500">
                    Revisá los comprobantes enviados por los comercios.
                </p>
            </div>

            <div class="space-y-5">
                @forelse($pagos as $pago)
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#032B45]">
                                    {{ $pago->suscripcion?->comercio?->nombre ?? 'Comercio no disponible' }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Pago #{{ $pago->id_pago_suscripcion }}
                                    · Suscripción #{{ $pago->id_suscripcion }}
                                </p>
                            </div>

                            @php
                                $colores = [
                                    'pendiente' => 'bg-amber-100 text-amber-700',
                                    'en_revision' => 'bg-blue-100 text-blue-700',
                                    'aprobado' => 'bg-emerald-100 text-emerald-700',
                                    'rechazado' => 'bg-red-100 text-red-700',
                                ];

                                $estados = [
                                    'pendiente' => 'Pendiente de comprobante',
                                    'en_revision' => 'En revisión',
                                    'aprobado' => 'Aprobado',
                                    'rechazado' => 'Rechazado',
                                ];
                            @endphp

                            <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $colores[$pago->estado] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $estados[$pago->estado] ?? $pago->estado }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <p class="text-xs text-slate-400">Monto</p>
                                <p class="mt-1 font-bold text-[#032B45]">
                                    ${{ number_format((float) $pago->monto, 2, ',', '.') }} UYU
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Banco receptor</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ $pago->cuenta?->banco ?? 'Aún no seleccionado' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Fecha de registro</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ $pago->fecha_creacion?->format('d/m/Y H:i') ?? 'No disponible' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Período</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($pago->periodo_desde)->format('d/m/Y') }}
                                    al
                                    {{ \Carbon\Carbon::parse($pago->periodo_hasta)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            @if($pago->comprobante)
                                <a
                                    href="{{ asset('storage/' . $pago->comprobante) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-lg border border-[#032B45] px-5 py-2.5 text-sm font-semibold text-[#032B45] hover:bg-slate-50"
                                >
                                    Ver comprobante
                                </a>
                            @else
                                <span class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm text-slate-500">
                                    Esperando comprobante del comercio
                                </span>
                            @endif

                            @if(in_array($pago->estado, ['pendiente', 'en_revision']) && $pago->comprobante)
                                <form
                                    action="{{ route('admin.pagos.aprobar', $pago->id_pago_suscripcion) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Confirmás que verificaste la transferencia y querés aprobarla?')"
                                        class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                                    >
                                        Aprobar pago
                                    </button>
                                </form>

                                <form
                                    action="{{ route('admin.pagos.rechazar', $pago->id_pago_suscripcion) }}"
                                    method="POST"
                                    class="flex flex-wrap gap-2"
                                >
                                    @csrf

                                    <input
                                        type="text"
                                        name="observaciones"
                                        required
                                        maxlength="500"
                                        placeholder="Motivo del rechazo"
                                        class="min-w-48 rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-red-500"
                                    >

                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Confirmás que querés rechazar este comprobante?')"
                                        class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
                                    >
                                        Rechazar
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($pago->observaciones)
                            <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                                <strong>Observaciones:</strong> {{ $pago->observaciones }}
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-12 text-center shadow-sm">
                        <h3 class="text-lg font-bold text-[#032B45]">
                            Todavía no hay pagos registrados
                        </h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Cuando se genere una renovación o un comercio envíe un comprobante, aparecerá acá.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

    </main>
</body>
</html>
