<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Membresía
        </h2>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">

            <form action="{{ route('membresias.update', $membresia->MembresiaID) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="bg-white">
                    @include('membresias.form')
                </div>

                <div class="flex justify-end gap-3">

                    <a href="{{ route('membresias.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                        Cancelar
                    </a>

                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded">
                        Actualizar
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>