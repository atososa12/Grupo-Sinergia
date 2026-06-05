<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>

            <input type="text"
                   name="Nombre"
                   value="{{ old('Nombre', $instructor->Nombre ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Especialidad</label>

            <input type="text"
                   name="Especialidad"
                   value="{{ old('Especialidad', $instructor->Especialidad ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>

            <input type="email"
                   name="Email"
                   value="{{ old('Email', $instructor->Email ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>

            <input type="text"
                   name="Telefono"
                   value="{{ old('Telefono', $instructor->Telefono ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Activo</label>

            <select name="Activo"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                <option value="1" {{ old('Activo', $instructor->Activo ?? 1) == 1 ? 'selected' : '' }}>
                    🟢 Sí
                </option>

                <option value="0" {{ old('Activo', $instructor->Activo ?? 1) == 0 ? 'selected' : '' }}>
                    🔴 No
                </option>

            </select>
        </div>

    </div>

</div>