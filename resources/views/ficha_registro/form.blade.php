{{-- Input oculto para el usuario que registra. Se pasa desde el Controller. --}}
<input type="hidden" name="usuario_id" value="{{ $usuario_id ?? $ficha_registro->usuario_id }}">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    {{-- 1. Beneficiario (Solo editable al CREAR) --}}
    <div class="form-group">
        <label for="beneficiario_id">Beneficiario *</label>
        <select name="beneficiario_id" id="beneficiario_id" class="form-control" required
                {{ isset($ficha_registro) ? 'disabled' : '' }}> 
            <option value="">-- Seleccione un Beneficiario --</option>
            @php 
                // Define el beneficiario seleccionado
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
        @error('beneficiario_id')<p class="error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 2. Fecha de Registro --}}
    <div class="form-group">
        <label for="fecha_registro">Fecha de Registro *</label>
        <input type="date" name="fecha_registro" id="fecha_registro" class="form-control" required
               value="{{ old('fecha_registro', $ficha_registro->fecha_registro ?? date('Y-m-d')) }}">
        @error('fecha_registro')<p class="error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 3. Área de Intervención --}}
    <div class="form-group">
        <label for="area_intervencion_id">Área de Intervención Principal *</label>
        <select name="area_intervencion_id" id="area_intervencion_id" class="form-control" required>
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
        @error('area_intervencion_id')<p class="error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 4. Retraso en Desarrollo --}}
    <div class="form-group">
        <label for="retraso_en_desarrollo">Retraso en el Desarrollo *</label>
        <select name="retraso_en_desarrollo" id="retraso_en_desarrollo" class="form-control" required>
            @php 
                // Usamos === '1' o === true para manejar valores de base de datos o de old()
                $currentRetraso = old('retraso_en_desarrollo', isset($ficha_registro) ? (int)$ficha_registro->retraso_en_desarrollo : null);
            @endphp
            <option value="1" {{ $currentRetraso === 1 ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ $currentRetraso === 0 ? 'selected' : '' }}>No</option>
        </select>
        @error('retraso_en_desarrollo')<p class="error-message">{{ $message }}</p>@enderror
    </div>

    {{-- 5. Incluido en Educación 2025 --}}
    <div class="form-group col-span-1 md:col-span-2">
        <label for="incluido_en_educacion_2025">Incluido en Programa de Educación 2025 *</label>
        <select name="incluido_en_educacion_2025" id="incluido_en_educacion_2025" class="form-control" required>
            @php 
                $currentInclusion = old('incluido_en_educacion_2025', isset($ficha_registro) ? (int)$ficha_registro->incluido_en_educacion_2025 : null);
            @endphp
            <option value="1" {{ $currentInclusion === 1 ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ $currentInclusion === 0 ? 'selected' : '' }}>No</option>
        </select>
        @error('incluido_en_educacion_2025')<p class="error-message">{{ $message }}</p>@enderror
    </div>
</div>