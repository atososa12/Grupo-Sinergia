<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
            <input type="text"
                   name="Nombre"
                   value="{{ old('Nombre', $socio->Nombre ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Documento</label>
            <input type="text"
                   name="Documento"
                   value="{{ old('Documento', $socio->Documento ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email"
                   name="Email"
                   value="{{ old('Email', $socio->Email ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
            <input type="text"
                   name="Telefono"
                   value="{{ old('Telefono', $socio->Telefono ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Nacimiento</label>
            <input type="date"
                   name="FechaNacimiento"
                   value="{{ old('FechaNacimiento', $socio->FechaNacimiento ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Activo</label>

            <select name="Activo"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                <option value="1" {{ old('Activo', $socio->Activo ?? 1) == 1 ? 'selected' : '' }}>🟢 Activo</option>
                <option value="0" {{ old('Activo', $socio->Activo ?? 1) == 0 ? 'selected' : '' }}>🔴 Inactivo</option>

            </select>
        </div>

    </div>

</div>