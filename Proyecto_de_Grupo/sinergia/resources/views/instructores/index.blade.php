<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Instructores
        </h2>
    </x-slot>

    <div class="py-4 px-4">

        <a href="{{ route('instructores.create') }}" class="btn-nuevo">
            Nuevo Instructor
        </a>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <br><br>

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>

            @foreach($instructores as $instructor)

            <tr>

                <td>{{ $instructor->InstructorID }}</td>

                <td>{{ $instructor->Nombre }}</td>

                <td>{{ $instructor->Especialidad }}</td>

                <td>{{ $instructor->Email }}</td>

                <td>{{ $instructor->Telefono }}</td>

                <td>
                    @if($instructor->Activo)
                        <span style="color: green; font-weight: bold;">
                            Activo
                        </span>
                    @else
                        <span style="color: red; font-weight: bold;">
                            Inactivo
                        </span>
                    @endif
                </td>

                <td>

                    <a href="{{ route('instructores.edit', $instructor->InstructorID) }}"
                       class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('instructores.destroy', $instructor->InstructorID) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-eliminar"
                            onclick="return confirm('¿Está seguro que desea eliminar este instructor?')">
                            Eliminar
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</x-app-layout>