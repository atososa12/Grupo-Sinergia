<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Asistencia
        </h2>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('asistencias.store') }}" method="POST" class="space-y-4">

                @csrf

                <div class="bg-white">
                    @include('asistencias.form')
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