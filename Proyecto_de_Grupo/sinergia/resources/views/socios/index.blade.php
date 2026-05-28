 <x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Socios
    </h2>
</x-slot>

<div class="py-4">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            <table class="table-auto w-full border">

                <thead>

                    <tr class="bg-gray-200">

                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nombre</th>
                        <th class="border px-4 py-2">Documento</th>
                        <th class="border px-4 py-2">Email</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($socios as $socio)

                    <tr>

                        <td class="border px-4 py-2">
                            {{ $socio->SocioID }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $socio->Nombre }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $socio->Documento }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $socio->Email }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

</x-app-layout>