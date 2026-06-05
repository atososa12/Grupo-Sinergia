<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Socio</label>

            <select name="SocioID"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                @foreach($socios as $socio)

                    <option value="{{ $socio->SocioID }}"
                        {{ old('SocioID', $asistencia->SocioID ?? '') == $socio->SocioID ? 'selected' : '' }}>

                        {{ $socio->Nombre }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Clase</label>

            <select name="ClaseID"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                @foreach($clases as $clase)

                    <option value="{{ $clase->ClaseID }}"
                        {{ old('ClaseID', $asistencia->ClaseID ?? '') == $clase->ClaseID ? 'selected' : '' }}>

                        {{ $clase->Nombre }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Entrada</label>

            <input type="datetime-local"
                   name="FechaEntrada"
                   value="{{ old('FechaEntrada', $asistencia->FechaEntrada ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Salida</label>

            <input type="datetime-local"
                   name="FechaSalida"
                   value="{{ old('FechaSalida', $asistencia->FechaSalida ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

    </div>

</div>