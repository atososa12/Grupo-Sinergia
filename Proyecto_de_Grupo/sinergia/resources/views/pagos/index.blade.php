<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Pagos
        </h2>
    </x-slot>

    <div class="py-4 px-4">

        <a href="{{ route('pagos.create') }}" class="btn-nuevo">
            Nuevo Pago
        </a>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <br><br>

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Socio</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Medio de Pago</th>
                <th>Acciones</th>
            </tr>

            @foreach($pagos as $pago)

            <tr>

                <td>{{ $pago->PagoID }}</td>

                <td>{{ $pago->inscripcion->socio->Nombre }}</td>

                <td>${{ $pago->Monto }}</td>

                <td>{{ $pago->FechaPago }}</td>

                <td>{{ $pago->MedioPago }}</td>

                <td>

                    <a href="{{ route('pagos.edit', $pago->PagoID) }}" class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('pagos.destroy', $pago->PagoID) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-eliminar"
                            onclick="return confirm('¿Está seguro que desea eliminar este pago?')">
                            Eliminar
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</x-app-layout>