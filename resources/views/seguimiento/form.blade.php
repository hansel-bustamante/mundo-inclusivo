<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- 🟦 1. Actividad Asociada --}}
    <div class="form-group">
        <label for="actividad_id">Actividad Asociada *</label>
        <select name="actividad_id" id="actividad_id" class="form-control" required>
            <option value="">-- Seleccione una Actividad --</option>

            @php
                $currentActividad = old('actividad_id', $seguimiento->actividad_id ?? '');
            @endphp

            @foreach ($actividades as $actividad)
                <option value="{{ $actividad->id_actividad }}"
                        {{ $currentActividad == $actividad->id_actividad ? 'selected' : '' }}>
                    {{ $actividad->nombre }}
                    ({{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
        @error('actividad_id')
            <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    {{-- 🟦 2. Fecha del Seguimiento --}}
    <div class="form-group">
        <label for="fecha">Fecha de Seguimiento *</label>
        <input
            type="date"
            name="fecha"
            id="fecha"
            class="form-control"
            required
            value="{{ old('fecha', $seguimiento->fecha ?? date('Y-m-d')) }}"
        >
        @error('fecha')
            <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    {{-- 🟦 3. Tipo de Seguimiento --}}
    <div class="form-group col-span-1 md:col-span-2">
        <label for="tipo">Tipo de Seguimiento *</label>
        <select name="tipo" id="tipo" class="form-control" required>
            <option value="">-- Seleccione el Tipo --</option>

            @php
                $currentTipo = old('tipo', $seguimiento->tipo ?? '');
                $tipos = [
                    'Visita Domiciliaria',
                    'Llamada Telefónica',
                    'Reunión Presencial',
                    'Otro'
                ];
            @endphp

            @foreach ($tipos as $tipo)
                <option value="{{ $tipo }}" {{ $currentTipo == $tipo ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
        @error('tipo')
            <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    {{-- 🟦 4. Observaciones --}}
    <div class="form-group col-span-1 md:col-span-2">
        <label for="observaciones">Observaciones / Detalles del Seguimiento *</label>
        <textarea
            name="observaciones"
            id="observaciones"
            class="form-control"
            rows="4"
            required
            maxlength="1000"
            placeholder="Describe brevemente las observaciones o detalles del seguimiento..."
        >{{ old('observaciones', $seguimiento->observaciones ?? '') }}</textarea>
        @error('observaciones')
            <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

</div>
