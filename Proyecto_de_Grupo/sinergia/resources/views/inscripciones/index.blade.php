<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Inscripciones
        </h2>
    </x-slot>

    <div class="py-4 px-4">

        <a href="{{ route('inscripciones.create') }}" class="btn-nuevo">
            Nueva Inscripción
        </a>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <br><br>

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Socio</th>
                <th>Membresía</th>
                <th>Inicio</th>
                <th>Vencimiento</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            @foreach($inscripciones as $inscripcion)

            <tr>

                <td>{{ $inscripcion->InscripcionID }}</td>

                <td>{{ $inscripcion->socio->Nombre }}</td>

                <td>{{ $inscripcion->membresia->Tipo }}</td>

                <td>{{ $inscripcion->FechaInicio }}</td>

                <td>{{ $inscripcion->FechaVencimiento }}</td>

                <td>
                    @if($inscripcion->Estado == 'activa')
                        <span style="color: green; font-weight: bold;">
                            Activa
                        </span>
                    @elseif($inscripcion->Estado == 'vencida')
                        <span style="color: orange; font-weight: bold;">
                            Vencida
                        </span>
                    @else
                        <span style="color: red; font-weight: bold;">
                            Cancelada
                        </span>
                    @endif
                </td>

                <td>

                    <a href="{{ route('inscripciones.edit', $inscripcion->InscripcionID) }}"
                       class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('inscripciones.destroy', $inscripcion->InscripcionID) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-eliminar"
                            onclick="return confirm('¿Está seguro que desea eliminar esta inscripción?')">
                            Eliminar
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</x-app-layout>