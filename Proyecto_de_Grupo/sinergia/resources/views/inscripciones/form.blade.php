<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Socio</label>

            <select name="SocioID"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                @foreach($socios as $socio)

                    <option value="{{ $socio->SocioID }}"
                        {{ old('SocioID', $inscripcion->SocioID ?? '') == $socio->SocioID ? 'selected' : '' }}>

                        {{ $socio->Nombre }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Membresía</label>

            <select name="MembresiaID"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                @foreach($membresias as $membresia)

                    <option value="{{ $membresia->MembresiaID }}"
                        {{ old('MembresiaID', $inscripcion->MembresiaID ?? '') == $membresia->MembresiaID ? 'selected' : '' }}>

                        {{ $membresia->Tipo }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Inicio</label>

            <input type="date"
                   name="FechaInicio"
                   value="{{ old('FechaInicio', $inscripcion->FechaInicio ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Vencimiento</label>

            <input type="date"
                   name="FechaVencimiento"
                   value="{{ old('FechaVencimiento', $inscripcion->FechaVencimiento ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>

            <select name="Estado"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                <option value="activa" {{ old('Estado', $inscripcion->Estado ?? '') == 'activa' ? 'selected' : '' }}>
                    🟢 Activa
                </option>

                <option value="vencida" {{ old('Estado', $inscripcion->Estado ?? '') == 'vencida' ? 'selected' : '' }}>
                    🟡 Vencida
                </option>

                <option value="cancelada" {{ old('Estado', $inscripcion->Estado ?? '') == 'cancelada' ? 'selected' : '' }}>
                    🔴 Cancelada
                </option>

            </select>
        </div>

    </div>

</div>