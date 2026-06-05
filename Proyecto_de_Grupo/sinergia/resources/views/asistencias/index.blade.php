<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Asistencias
        </h2>
    </x-slot>

    <div class="py-4 px-4">

        <a href="{{ route('asistencias.create') }}" class="btn-nuevo">
            Nueva Asistencia
        </a>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <br><br>

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Socio</th>
                <th>Clase</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Acciones</th>
            </tr>

            @foreach($asistencias as $asistencia)

            <tr>

                <td>{{ $asistencia->AsistenciaID }}</td>

                <td>{{ $asistencia->socio->Nombre }}</td>

                <td>{{ $asistencia->clase->Nombre }}</td>

                <td>{{ $asistencia->FechaEntrada }}</td>

                <td>{{ $asistencia->FechaSalida }}</td>

                <td>

                    <a href="{{ route('asistencias.edit', $asistencia->AsistenciaID) }}"
                       class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('asistencias.destroy', $asistencia->AsistenciaID) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-eliminar"
                            onclick="return confirm('¿Está seguro que desea eliminar esta asistencia?')">
                            Eliminar
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</x-app-layout>