<div class="space-y-5">

    <div>
        <label class="block text-sm font-semibold text-gray-700">Tipo</label>
        <input type="text"
               name="Tipo"
               value="{{ old('Tipo', $membresia->Tipo ?? '') }}"
               class="mt-1 w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                      focus:ring-2 focus:ring-green-600 focus:border-green-600">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700">Precio</label>
        <input type="number"
               step="0.01"
               name="Precio"
               value="{{ old('Precio', $membresia->Precio ?? '') }}"
               class="mt-1 w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                      focus:ring-2 focus:ring-green-600 focus:border-green-600">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700">Cantidad de clases</label>
        <input type="number"
               name="CantidadClases"
               value="{{ old('CantidadClases', $membresia->CantidadClases ?? '') }}"
               class="mt-1 w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                      focus:ring-2 focus:ring-green-600 focus:border-green-600">
    </div>

</div>