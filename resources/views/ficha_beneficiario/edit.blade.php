@extends('layouts.main')

@section('title', 'Editar Ficha de Beneficiario')

@section('content')
<div class="content-card">

    <div class="header-container">
        <div>
            <h3 class="section-title">Editar Ficha de Beneficiario</h3>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap;">
                <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                    ID: {{ $ficha->id_ficha }}
                </span>
                <span style="color: var(--color-text-medium); font-weight: 500;">
                    {{ $ficha->beneficiario->persona->nombre }} {{ $ficha->beneficiario->persona->apellido_paterno }}
                </span>
            </div>
        </div>
        <a href="{{ route('ficha_beneficiario.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
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

    {{-- INFORMACIÓN ACTUAL --}}
    <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.25rem;">Editando ficha de:</h5>
                <p style="color: var(--color-text-medium); margin: 0; font-weight: 500;">
                    {{ $ficha->beneficiario->persona->nombre }} {{ $ficha->beneficiario->persona->apellido_paterno }} 
                    • C.I.: {{ $ficha->beneficiario->persona->carnet_identidad ?? 'N/A' }}
                    • Área: {{ $ficha->areaIntervencion->nombre_area ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('ficha_beneficiario.update', $ficha->id_ficha) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            
            {{-- COLUMNA 1 --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Datos del Beneficiario
                </h4>

                <div class="form-group">
                    <label class="form-label">Persona Asociada</label>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--border-radius);">
                        <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                            {{ substr($ficha->beneficiario->persona->nombre, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--color-text-dark);">
                                {{ $ficha->beneficiario->persona->nombre }} {{ $ficha->beneficiario->persona->apellido_paterno }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--color-text-light);">
                                C.I.: {{ $ficha->beneficiario->persona->carnet_identidad ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <small style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 0.5rem; display: block;">
                        La persona no se puede cambiar una vez creada la ficha
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipo de Discapacidad</label>
                    <select name="tipo_discapacidad" class="form-input @error('tipo_discapacidad') is-invalid @enderror">
                        <option value="">-- Ninguna / Sin selección --</option>
                        
                        @php
                            $discapacidades = [
                                'Discapacidad Intelectual',
                                'Discapacidad Física/Motora',
                                'Discapacidad Múltiple',
                                'Discapacidad Auditiva',
                                'TEA (Trastorno del Espectro Autista)',
                                'Discapacidad Visual',
                                'Discapacidad Psicosocial/Mental'
                            ];
                            $valorActual = old('tipo_discapacidad', $ficha->beneficiario->tipo_discapacidad);
                        @endphp

                        @foreach ($discapacidades as $opcion)
                            <option value="{{ $opcion }}" {{ $valorActual == $opcion ? 'selected' : '' }}>
                                {{ $opcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_discapacidad')
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
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Diagnóstico Inicial
                </h4>

                <div class="form-group">
                    <label class="form-label">
                        <span style="color: var(--color-danger);">*</span> Fecha de Registro
                    </label>
                    <input type="date" name="fecha_registro" value="{{ old('fecha_registro', $ficha->fecha_registro) }}" required 
                           class="form-input @error('fecha_registro') is-invalid @enderror">
                    @error('fecha_registro')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span style="color: var(--color-danger);">*</span> Área de Intervención
                    </label>
                    
                    @if($areas->count() == 1)
                        <input type="text" value="{{ $areas->first()->nombre_area }}" 
                               class="form-input form-disabled" disabled 
                               style="background-color: var(--color-bg-light); color: var(--color-text-medium);">
                        <input type="hidden" name="area_intervencion_id" value="{{ $areas->first()->codigo_area }}">
                        <small style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 0.5rem; display: block;">
                            Su rol de usuario solo permite trabajar con esta área
                        </small>
                    @else
                        <select name="area_intervencion_id" required class="form-input @error('area_intervencion_id') is-invalid @enderror">
                            @foreach ($areas as $area)
                                <option value="{{ $area->codigo_area }}" {{ old('area_intervencion_id', $ficha->area_intervencion_id) == $area->codigo_area ? 'selected' : '' }}>
                                    {{ $area->nombre_area }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_intervencion_id')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Indicadores de Desarrollo</label>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="retraso_en_desarrollo" value="1" {{ old('retraso_en_desarrollo', $ficha->retraso_en_desarrollo) == '1' ? 'checked' : '' }}>
                                <span>¿Presenta retraso en desarrollo?</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="retraso_en_desarrollo" value="0" {{ old('retraso_en_desarrollo', $ficha->retraso_en_desarrollo) != '1' ? 'checked' : '' }}>
                                <span>No presenta retraso en desarrollo</span>
                            </label>
                        </div>
                        
                        <div>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="incluido_en_educacion_2025" value="1" {{ old('incluido_en_educacion_2025', $ficha->incluido_en_educacion_2025) == '1' ? 'checked' : '' }}>
                                <span>¿Incluido en educación 2025?</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="incluido_en_educacion_2025" value="0" {{ old('incluido_en_educacion_2025', $ficha->incluido_en_educacion_2025) != '1' ? 'checked' : '' }}>
                                <span>No incluido en educación 2025</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ADVERTENCIA SOBRE ACTUALIZACIONES --}}
        <div style="background: #fff3cd; padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid #ffeaa7; margin: 2rem 0;">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #856404; flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <h5 style="font-family: var(--font-heading); font-weight: 600; color: #856404; margin-bottom: 0.5rem;">Importante</h5>
                    <p style="color: #856404; font-size: 0.875rem; margin: 0;">
                        Los cambios realizados aquí se reflejarán inmediatamente en el sistema. 
                        La persona asociada no se puede modificar una vez creada la ficha.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
            <a href="{{ route('ficha_beneficiario.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Actualizar Ficha
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
    
    .form-disabled {
        background-color: var(--color-bg-light) !important;
        color: var(--color-text-medium) !important;
        cursor: not-allowed;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
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
        // Establecer fecha máxima como hoy
        const fechaInput = document.querySelector('input[name="fecha_registro"]');
        const today = new Date().toISOString().split('T')[0];
        fechaInput.max = today;

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
    });
</script>
@endsection