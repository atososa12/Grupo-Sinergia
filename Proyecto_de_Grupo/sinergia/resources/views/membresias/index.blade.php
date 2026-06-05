<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Membresías
        </h2>
    </x-slot>

    <div class="py-4 px-4">

        <a href="{{ route('membresias.create') }}" class="btn-nuevo">
            Nueva Membresía
        </a>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <br><br>

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Precio</th>
                <th>Cantidad Clases</th>
                <th>Acciones</th>
            </tr>

            @foreach($membresias as $membresia)

            <tr>
                <td>{{ $membresia->MembresiaID }}</td>
                <td>{{ $membresia->Tipo }}</td>
                <td>{{ $membresia->Precio }}</td>
                <td>{{ $membresia->CantidadClases }}</td>

                <td>

                    <a href="{{ route('membresias.edit', $membresia->MembresiaID) }}" class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('membresias.destroy', $membresia->MembresiaID) }}"
                          method="POST"
                          style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-eliminar"
                            onclick="return confirm('¿Está seguro que desea eliminar esta membresía?')">
                            Eliminar
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</x-app-layout>