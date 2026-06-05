<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Instructor
        </h2>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">

            <form action="{{ route('instructores.store') }}" method="POST" class="space-y-4">

                @csrf

                <div class="bg-white">
                    @include('instructores.form')
                </div>

                <div class="flex justify-end">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                        Guardar
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>