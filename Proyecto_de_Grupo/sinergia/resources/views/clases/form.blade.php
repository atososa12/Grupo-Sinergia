<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>

            <input type="text"
                   name="Nombre"
                   value="{{ old('Nombre', $clase->Nombre ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo</label>

            <input type="text"
                   name="Tipo"
                   value="{{ old('Tipo', $clase->Tipo ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Instructor</label>

            <select name="InstructorID"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                @foreach($instructores as $instructor)

                    <option value="{{ $instructor->InstructorID }}"
                        {{ old('InstructorID', $clase->InstructorID ?? '') == $instructor->InstructorID ? 'selected' : '' }}>

                        {{ $instructor->Nombre }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Días</label>

            <input type="text"
                   name="Dias"
                   placeholder="Lunes, Miércoles"
                   value="{{ old('Dias', $clase->Dias ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Horario</label>

            <input type="text"
                   name="Horario"
                   placeholder="18:00 - 19:00"
                   value="{{ old('Horario', $clase->Horario ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cupo Máximo</label>

            <input type="number"
                   name="CupoMaximo"
                   value="{{ old('CupoMaximo', $clase->CupoMaximo ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

    </div>

</div>