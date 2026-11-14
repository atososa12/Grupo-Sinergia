
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
            <h2 class="text-3xl font-bold text-[#032B45]">Pagos de suscripciones</h2>
            <p class="mt-2 text-slate-500">
                Revisá los comprobantes enviados por los comercios.
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

        <div class="mb-8 grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total de pagos</p>
                <p class="mt-2 text-3xl font-bold text-[#032B45]">{{ $pagos->count() }}</p>
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
                                'pendiente' => 'Pendiente',
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
                                {{ $pago->cuenta?->banco ?? 'No disponible' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Fecha de envío</p>
                            <p class="mt-1 font-semibold text-slate-700">
                                {{ $pago->fecha_creacion?->format('d/m/Y H:i') ?? 'No disponible' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Titular</p>
                            <p class="mt-1 font-semibold text-slate-700">
                                {{ $pago->cuenta?->titular ?? 'No disponible' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @if($pago->comprobante)
                            <a
                                href="{{ asset('storage/' . $pago->comprobante) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="rounded-lg border border-[#032B45] px-5 py-2.5 text-sm font-semibold text-[#032B45] hover:bg-slate-50"
                            >
                                Ver comprobante
                            </a>
                        @endif

                        @if(in_array($pago->estado, ['pendiente', 'en_revision']))
                            <form action="{{ route('admin.pagos.aprobar', $pago->id_pago_suscripcion) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('¿Confirmás que verificaste la transferencia y querés aprobarla?')"
                                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                                >
                                    Aprobar pago
                                </button>
                            </form>

                            <form action="{{ route('admin.pagos.rechazar', $pago->id_pago_suscripcion) }}" method="POST" class="flex flex-wrap gap-2">
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

                </div>
            @empty
                <div class="rounded-2xl bg-white p-12 text-center shadow-sm">
                    <h3 class="text-lg font-bold text-[#032B45]">Todavía no hay pagos registrados</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        Cuando un comercio envíe un comprobante, aparecerá acá.
                    </p>
                </div>
            @endforelse
        </div>

    </main>
</body>
</html>
