{{-- Input oculto para el usuario que registra. Se pasa desde el Controller. --}}
<input type="hidden" name="usuario_id" value="{{ $usuario_id ?? $ficha_registro->usuario_id ?? '' }}">

{{-- Se reemplaza el grid de utilidad por la clase semántica form-grid --}}
<div class="form-grid">
    
    {{-- 1. Beneficiario (Solo editable al CREAR) --}}
    <div class="form-group">
        {{-- Se reemplaza 'label' por 'form-label' --}}
        <label for="beneficiario_id" class="form-label">Beneficiario *</label>
        {{-- Se reemplaza 'form-control' por 'form-select' --}}
        <select name="beneficiario_id" id="beneficiario_id" class="form-select" required
                {{ isset($ficha_registro) ? 'disabled' : '' }}> 
            <option value="">-- Seleccione un Beneficiario --</option>
            @php 
                // Define el beneficiario seleccionado. Se asume que id_persona es la clave en $beneficiarios
                $currentBeneficiario = old('beneficiario_id', $ficha_registro->beneficiario_id ?? '');
            @endphp
            @foreach ($beneficiarios as $beneficiario)
                {{-- Muestra el nombre completo de la Persona relacionada --}}
                <option value="{{ $beneficiario->id_persona }}" 
                        {{ $currentBeneficiario == $beneficiario->id_persona ? 'selected' : '' }}>
                    {{ $beneficiario->persona->nombre }} {{ $beneficiario->persona->apellido_paterno }} (CI: {{ $beneficiario->persona->carnet_identidad ?? 'N/A' }})
                </option>
            @endforeach
        </select>
        @if (isset($ficha_registro))
             {{-- Campo oculto para enviar el valor del select deshabilitado al UPDATE --}}
             <input type="hidden" name="beneficiario_id" value="{{ $ficha_registro->beneficiario_id }}">
        @endif
        {{-- Se reemplaza 'error-message' por 'form-error-message' --}}
        @error('beneficiario_id')<p class="form-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 2. Fecha de Registro --}}
    <div class="form-group">
        <label for="fecha_registro" class="form-label">Fecha de Registro *</label>
        {{-- Se reemplaza 'form-control' por 'form-input' --}}
        <input type="date" name="fecha_registro" id="fecha_registro" class="form-input" required
               value="{{ old('fecha_registro', $ficha_registro->fecha_registro ?? date('Y-m-d')) }}">
        @error('fecha_registro')<p class="form-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 3. Área de Intervención --}}
    <div class="form-group">
        <label for="area_intervencion_id" class="form-label">Área de Intervención Principal *</label>
        <select name="area_intervencion_id" id="area_intervencion_id" class="form-select" required>
            <option value="">-- Seleccione un Área --</option>
            @php 
                $currentArea = old('area_intervencion_id', $ficha_registro->area_intervencion_id ?? '');
            @endphp
            @foreach ($areas as $area)
                <option value="{{ $area->codigo_area }}" 
                        {{ $currentArea == $area->codigo_area ? 'selected' : '' }}>
                    {{ $area->nombre_area }}
                </option>
            @endforeach
        </select>
        @error('area_intervencion_id')<p class="form-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 4. Retraso en Desarrollo --}}
    <div class="form-group">
        <label for="retraso_en_desarrollo" class="form-label">Retraso en el Desarrollo *</label>
        <select name="retraso_en_desarrollo" id="retraso_en_desarrollo" class="form-select" required>
            @php 
                // Usamos la lógica de tipo para asegurar que la comparación sea correcta
                $currentRetraso = old('retraso_en_desarrollo', isset($ficha_registro) ? (int)$ficha_registro->retraso_en_desarrollo : null);
            @endphp
            <option value="1" {{ $currentRetraso === 1 ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ $currentRetraso === 0 ? 'selected' : '' }}>No</option>
        </select>
        @error('retraso_en_desarrollo')<p class="form-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 5. Incluido en Educación 2025 (Ocupa ambas columnas) --}}
    {{-- Se usa la clase form-group-full para que ocupe todo el ancho, reemplazando 'col-span-1 md:col-span-2' --}}
    <div class="form-group form-group-full">
        <label for="incluido_en_educacion_2025" class="form-label">Incluido en Programa de Educación 2025 *</label>
        <select name="incluido_en_educacion_2025" id="incluido_en_educacion_2025" class="form-select" required>
            @php 
                $currentInclusion = old('incluido_en_educacion_2025', isset($ficha_registro) ? (int)$ficha_registro->incluido_en_educacion_2025 : null);
            @endphp
            <option value="1" {{ $currentInclusion === 1 ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ $currentInclusion === 0 ? 'selected' : '' }}>No</option>
        </select>
        @error('incluido_en_educacion_2025')<p class="form-error-message">{{ $message }}</p>@enderror
    </div>
</div>