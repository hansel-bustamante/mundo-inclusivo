@extends('layouts.main')

@section('title', 'Registrar Nueva Actividad')

@section('content')
    
    <div class="content-card">

        <h3 class="section-title">Registrar Nueva Actividad</h3> 
        <p class="section-subtitle">Complete los campos para planificar una actividad de intervención.</p>

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

        <form action="{{ route('actividad.store') }}" method="POST">
            @csrf

            <div class="form-grid-3-col">

                <div class="form-group form-span-2">
                    <label for="nombre" class="form-label">Nombre de la Actividad (*)</label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-input @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" 
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
                           value="{{ old('fecha', now()->format('Y-m-d')) }}" 
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
                           value="{{ old('lugar') }}" 
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
                                    {{ old('area_intervencion_id') == $area->codigo_area ? 'selected' : '' }}>
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
                                    {{ old('codigo_actividad_id') == $codigo->codigo_actividad ? 'selected' : '' }}>
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
                              rows="4">{{ old('descripcion') }}</textarea>
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
                    Guardar Actividad
                </button>
            </div>
        </form>

    </div>
@endsection