@extends('layouts.main')

@section('title', 'Editar Actividad: ' . $actividad->nombre)

@section('content')
    
    <div class="content-card">

        <h3 class="section-title">Editar Actividad: {{ $actividad->nombre }}</h3> 
        <p class="section-subtitle">Modifica la información de la actividad seleccionada.</p>

        @if ($errors->any())
            <div class="error-alert">
                <div class="error-header">
                    <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Hubo errores al procesar el formulario:</span>
                </div>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('actividad.update', $actividad->id_actividad) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid-3-col">

                <div class="form-group form-span-2">
                    <label for="nombre" class="form-label">Nombre de la Actividad (*)</label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-input @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $actividad->nombre) }}" 
                           required 
                           maxlength="150">
                    @error('nombre')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="fecha" class="form-label">Fecha (*)</label>
                    <input type="date" 
                           id="fecha" 
                           name="fecha" 
                           class="form-input @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha', $actividad->fecha) }}" 
                           required>
                    @error('fecha')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="lugar" class="form-label">Lugar (*)</label>
                    <input type="text" 
                           id="lugar" 
                           name="lugar" 
                           class="form-input @error('lugar') is-invalid @enderror"
                           value="{{ old('lugar', $actividad->lugar) }}" 
                           required 
                           maxlength="100">
                    @error('lugar')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="area_intervencion_id" class="form-label">Área de Intervención (*)</label>
                    <select id="area_intervencion_id" 
                            name="area_intervencion_id" 
                            class="form-input @error('area_intervencion_id') is-invalid @enderror"
                            required>
                        <option value="">-- Seleccione un Área --</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->codigo_area }}"
                                    {{ old('area_intervencion_id', $actividad->area_intervencion_id) == $area->codigo_area ? 'selected' : '' }}>
                                {{ $area->nombre_area }} ({{ $area->municipio }})
                            </option>
                        @endforeach
                    </select>
                    @error('area_intervencion_id')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="codigo_actividad_id" class="form-label">Código de Actividad (*)</label>
                    <select id="codigo_actividad_id" 
                            name="codigo_actividad_id" 
                            class="form-input @error('codigo_actividad_id') is-invalid @enderror"
                            required>
                        <option value="">-- Seleccione un Código --</option>
                        @foreach ($codigos as $codigo)
                            <option value="{{ $codigo->codigo_actividad }}"
                                    {{ old('codigo_actividad_id', $actividad->codigo_actividad_id) == $codigo->codigo_actividad ? 'selected' : '' }}>
                                {{ $codigo->codigo_actividad }} - {{ $codigo->nombre_actividad }}
                            </option>
                        @endforeach
                    </select>
                    @error('codigo_actividad_id')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group form-span-3">
                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              class="form-input @error('descripcion') is-invalid @enderror"
                              rows="4">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('actividad.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
@endsection