<div class="form-grid">

    {{-- Usuario oculto --}}
    <input type="hidden" name="usuario_id" value="{{ Auth::id() }}">

    {{-- Actividad --}}
    <div class="form-group form-span-2">
        <label for="actividad_id" class="form-label">Actividad a Evaluar *</label>
        @if (isset($actividad_seleccionada))
            <input type="text" 
                   value="{{ $actividad_seleccionada->nombre }} ({{ \Carbon\Carbon::parse($actividad_seleccionada->fecha)->format('d/m/Y') }})"
                   class="form-input bg-gray-100" readonly>
            <input type="hidden" name="actividad_id" value="{{ $actividad_seleccionada->id_actividad }}">
        @else
            <select name="actividad_id" id="actividad_id" class="form-input" required>
                <option value="">-- Seleccione una Actividad --</option>
                @foreach ($actividades as $actividad)
                    <option value="{{ $actividad->id_actividad }}"
                        {{ old('actividad_id', $evaluacion->actividad_id ?? '') == $actividad->id_actividad ? 'selected' : '' }}>
                        {{ $actividad->nombre }} ({{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }})
                    </option>
                @endforeach
            </select>
            @error('actividad_id')<p class="input-error-message">{{ $message }}</p>@enderror
        @endif
    </div>

    {{-- Fecha --}}
    <div class="form-group">
        <label for="fecha" class="form-label">Fecha de Evaluación *</label>
        <input type="date" name="fecha" id="fecha" class="form-input" required
               value="{{ old('fecha', $evaluacion->fecha ?? date('Y-m-d')) }}">
        @error('fecha')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- Resultado --}}
    <div class="form-group">
        <label for="resultado" class="form-label">Resultado *</label>
        <select name="resultado" id="resultado" class="form-input" required>
            @php $currentResultado = old('resultado', $evaluacion->resultado ?? ''); @endphp
            <option value="">-- Seleccione el Resultado --</option>
            <option value="Cumplido" {{ $currentResultado == 'Cumplido' ? 'selected' : '' }}>Cumplido</option>
            <option value="Parcial" {{ $currentResultado == 'Parcial' ? 'selected' : '' }}>Parcial</option>
            <option value="No cumplido" {{ $currentResultado == 'No cumplido' ? 'selected' : '' }}>No Cumplido</option>
        </select>
        @error('resultado')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- Ponderación --}}
    <div class="form-group">
        <label for="ponderacion" class="form-label">Ponderación (%)</label>
        <input type="number" name="ponderacion" id="ponderacion" class="form-input"
               value="{{ old('ponderacion', $evaluacion->ponderacion ?? '') }}" min="0" max="100" step="0.01">
        @error('ponderacion')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- Nivel de Aceptación --}}
    <div class="form-group">
        <label for="nivel_aceptacion" class="form-label">Nivel de Aceptación (%)</label>
        <input type="number" name="nivel_aceptacion" id="nivel_aceptacion" class="form-input"
               value="{{ old('nivel_aceptacion', $evaluacion->nivel_aceptacion ?? '') }}" min="0" max="100" step="0.01">
        @error('nivel_aceptacion')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- Expectativa Cumplida --}}
    <div class="form-group flex items-center form-span-2">
        <input type="hidden" name="expectativa_cumplida" value="0">
        <input type="checkbox" name="expectativa_cumplida" id="expectativa_cumplida" class="form-checkbox" value="1"
               {{ old('expectativa_cumplida', $evaluacion->expectativa_cumplida ?? false) ? 'checked' : '' }}>
        <label for="expectativa_cumplida" class="ml-2 pt-0">¿Se cumplió la expectativa inicial?</label>
        @error('expectativa_cumplida')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- Descripción --}}
    <div class="form-group form-span-2">
        <label for="descripcion" class="form-label">Observaciones Generales</label>
        <textarea name="descripcion" id="descripcion" rows="3" class="form-input">{{ old('descripcion', $evaluacion->descripcion ?? '') }}</textarea>
        @error('descripcion')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

    {{-- Actividades no cumplidas --}}
    <div class="form-group form-span-2">
        <label for="actividades_no_cumplidas" class="form-label">Actividades que no se lograron cumplir</label>
        <textarea name="actividades_no_cumplidas" id="actividades_no_cumplidas" rows="3" class="form-input">{{ old('actividades_no_cumplidas', $evaluacion->actividades_no_cumplidas ?? '') }}</textarea>
        @error('actividades_no_cumplidas')<p class="input-error-message">{{ $message }}</p>@enderror
    </div>

</div>
