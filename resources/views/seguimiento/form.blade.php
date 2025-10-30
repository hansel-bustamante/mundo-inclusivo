{{-- 1. Se reemplaza el grid de utilidad por la clase semántica form-grid --}}
<div class="form-grid">

    {{-- 🟦 1. Actividad Asociada --}}
    <div class="form-group">
        {{-- Se añade la clase form-label --}}
        <label for="actividad_id" class="form-label">Actividad Asociada *</label>
        {{-- Se reemplaza form-control por form-select --}}
        <select name="actividad_id" id="actividad_id" class="form-select" required>
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
            {{-- Se reemplaza error-message por form-error-message --}}
            <p class="form-error-message">{{ $message }}</p>
        @enderror
    </div>

    {{-- 🟦 2. Fecha del Seguimiento --}}
    <div class="form-group">
        <label for="fecha" class="form-label">Fecha de Seguimiento *</label>
        <input
            type="date"
            name="fecha"
            id="fecha"
            {{-- Se reemplaza form-control por form-input --}}
            class="form-input"
            required
            value="{{ old('fecha', $seguimiento->fecha ?? date('Y-m-d')) }}"
        >
        @error('fecha')
            <p class="form-error-message">{{ $message }}</p>
        @enderror
    </div>

    {{-- 🟦 3. Tipo de Seguimiento (Ocupa ambas columnas) --}}
    {{-- Se reemplaza col-span-1 md:col-span-2 por form-group-full --}}
    <div class="form-group form-group-full">
        <label for="tipo" class="form-label">Tipo de Seguimiento *</label>
        {{-- Se reemplaza form-control por form-select --}}
        <select name="tipo" id="tipo" class="form-select" required>
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
            <p class="form-error-message">{{ $message }}</p>
        @enderror
    </div>

    {{-- 🟦 4. Observaciones (Ocupa ambas columnas) --}}
    {{-- Se reemplaza col-span-1 md:col-span-2 por form-group-full --}}
    <div class="form-group form-group-full">
        <label for="observaciones" class="form-label">Observaciones / Detalles del Seguimiento *</label>
        <textarea
            name="observaciones"
            id="observaciones"
            {{-- Se reemplaza form-control por form-textarea --}}
            class="form-textarea"
            rows="4"
            required
            maxlength="1000"
            placeholder="Describe brevemente las observaciones o detalles del seguimiento..."
        >{{ old('observaciones', $seguimiento->observaciones ?? '') }}</textarea>
        @error('observaciones')
            <p class="form-error-message">{{ $message }}</p>
        @enderror
    </div>

</div>