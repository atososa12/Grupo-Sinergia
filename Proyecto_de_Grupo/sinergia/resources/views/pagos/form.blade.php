<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Inscripción</label>

            <select name="InscripcionID"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                @foreach($inscripciones as $inscripcion)

                    <option value="{{ $inscripcion->InscripcionID }}"
                        {{ old('InscripcionID', $pago->InscripcionID ?? '') == $inscripcion->InscripcionID ? 'selected' : '' }}>

                        {{ $inscripcion->socio->Nombre }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Monto</label>

            <input type="number"
                   step="0.01"
                   name="Monto"
                   value="{{ old('Monto', $pago->Monto ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Pago</label>

            <input type="date"
                   name="FechaPago"
                   value="{{ old('FechaPago', $pago->FechaPago ?? '') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                          focus:ring-2 focus:ring-green-600 focus:border-green-600">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Medio de Pago</label>

            <select name="MedioPago"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm
                           focus:ring-2 focus:ring-green-600 focus:border-green-600">

                <option value="efectivo" {{ old('MedioPago', $pago->MedioPago ?? '') == 'efectivo' ? 'selected' : '' }}>
                    Efectivo
                </option>

                <option value="transferencia" {{ old('MedioPago', $pago->MedioPago ?? '') == 'transferencia' ? 'selected' : '' }}>
                    Transferencia
                </option>

                <option value="tarjeta" {{ old('MedioPago', $pago->MedioPago ?? '') == 'tarjeta' ? 'selected' : '' }}>
                    Tarjeta Crédito
                </option>

            </select>
        </div>

    </div>

</div>