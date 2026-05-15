@extends('layouts.main')

@section('title', 'Registrar Nueva Actividad')

@section('content')
    
    <div class="content-card">

        <div class="header-container">
            <div>
                <h3 class="section-title">Registrar Nueva Actividad</h3>
                <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                    Complete los campos para planificar una actividad de intervención
                </p>
            </div>
            <a href="{{ route('actividad.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Listado
            </a>
        </div>

        @if ($errors->any())
            <div class="error-alert" style="margin-bottom: 2rem;">
                <div class="error-header">
                    <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="error-title">Errores en el formulario</span>
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

            <div class="form-grid">
                
                {{-- COLUMNA 1 --}}
                <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                    <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Información Básica
                    </h4>

                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Nombre de la Actividad
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               class="form-input @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" 
                               placeholder="Ej: Taller de Inclusión Educativa"
                               required 
                               maxlength="150">
                        @error('nombre')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Fecha
                        </label>
                        <input type="date" 
                               id="fecha" 
                               name="fecha" 
                               class="form-input @error('fecha') is-invalid @enderror"
                               value="{{ old('fecha', now()->format('Y-m-d')) }}" 
                               required>
                        @error('fecha')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="lugar" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Lugar
                        </label>
                        <input type="text" 
                               id="lugar" 
                               name="lugar" 
                               class="form-input @error('lugar') is-invalid @enderror"
                               value="{{ old('lugar') }}" 
                               placeholder="Ej: Centro Comunitario Central"
                               required 
                               maxlength="100">
                        @error('lugar')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- COLUMNA 2 --}}
                <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                    <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-info); display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Clasificación
                    </h4>

                    <div class="form-group">
                        <label for="area_intervencion_id" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Área de Intervención
                        </label>
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
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="codigo_actividad_id" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Código de Actividad
                        </label>
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
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin: 2rem 0;">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-warning); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Descripción Adicional
                </h4>

                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              class="form-input @error('descripcion') is-invalid @enderror"
                              placeholder="Describa los objetivos, metodología y detalles importantes de la actividad..."
                              rows="4">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- INFORMACIÓN ADICIONAL --}}
            <div style="background: var(--color-primary-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-primary); margin: 2rem 0;">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-primary); flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-primary-dark); margin-bottom: 0.5rem;">Información Importante</h5>
                        <p style="color: var(--color-text-medium); font-size: 0.875rem; margin: 0;">
                            Los campos marcados con <span style="color: var(--color-danger);">*</span> son obligatorios. 
                            Una vez creada la actividad, podrá gestionar participantes y sesiones desde el listado principal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
                <a href="{{ route('actividad.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Actividad
                </button>
            </div>
        </form>

    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .form-column {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--color-text-dark);
            font-size: 0.875rem;
        }
        
        .form-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.1);
            transform: translateY(-1px);
        }
        
        .form-input.is-invalid {
            border-color: var(--color-danger);
            background-color: #fef2f2;
        }
        
        .form-error-message {
            display: flex;
            align-items: center;
            color: var(--color-danger);
            font-size: 0.8125rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        textarea.form-input {
            resize: vertical;
            min-height: 120px;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .form-column {
                padding: 1rem;
            }
            
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer fecha mínima como hoy
            const fechaInput = document.getElementById('fecha');
            const today = new Date().toISOString().split('T')[0];
            fechaInput.min = today;

            // Validación en tiempo real para campos requeridos
            const requiredInputs = document.querySelectorAll('input[required], select[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Capitalizar automáticamente el nombre de la actividad
            const nombreInput = document.getElementById('nombre');
            nombreInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = this.value.replace(/\b\w/g, function(l) {
                        return l.toUpperCase();
                    });
                }
            });

            // Capitalizar automáticamente el lugar
            const lugarInput = document.getElementById('lugar');
            lugarInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = this.value.replace(/\b\w/g, function(l) {
                        return l.toUpperCase();
                    });
                }
            });
        });
    </script>
@endsection