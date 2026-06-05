<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Clases
        </h2>
    </x-slot>

    <div class="py-4 px-4">

        <a href="{{ route('clases.create') }}" class="btn-nuevo">
            Nueva Clase
        </a>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <br><br>

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Instructor</th>
                <th>Días</th>
                <th>Horario</th>
                <th>Cupo</th>
                <th>Acciones</th>
            </tr>

            @foreach($clases as $clase)

            <tr>

                <td>{{ $clase->ClaseID }}</td>

                <td>{{ $clase->Nombre }}</td>

                <td>{{ $clase->Tipo }}</td>

                <td>{{ $clase->instructor->Nombre }}</td>

                <td>{{ $clase->Dias }}</td>

                <td>{{ $clase->Horario }}</td>

                <td>{{ $clase->CupoMaximo }}</td>

                <td>

                    <a href="{{ route('clases.edit', $clase->ClaseID) }}"
                       class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('clases.destroy', $clase->ClaseID) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-eliminar"
                            onclick="return confirm('¿Está seguro que desea eliminar esta clase?')">
                            Eliminar
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</x-app-layout>