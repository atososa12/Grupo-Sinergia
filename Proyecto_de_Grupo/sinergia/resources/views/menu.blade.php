<div class="bg-white shadow-sm rounded-lg p-4 mb-6">

    <div class="flex flex-wrap gap-2">

        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
            Inicio
        </a>

        <a href="{{ route('socios.index') }}"
           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Socios
        </a>

        <a href="{{ route('membresias.index') }}"
           class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            Membresías
        </a>

        <a href="{{ route('inscripciones.index') }}"
           class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
            Inscripciones
        </a>

        <a href="{{ route('pagos.index') }}"
           class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            Pagos
        </a>

        <a href="{{ route('instructores.index') }}"
           class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            Instructores
        </a>

        <a href="{{ route('clases.index') }}"
           class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">
            Clases
        </a>

        <a href="{{ route('asistencias.index') }}"
           class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600">
            Asistencias
        </a>

    </div>

</div>