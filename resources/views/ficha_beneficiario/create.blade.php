```html
@extends('layouts.main')

@section('title', 'Nueva Ficha de Beneficiario')

@section('content')
<div class="content-card">

    <div class="header-container">
        <div>
            <h3 class="section-title">Registrar Nueva Ficha</h3>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                Complete la información para crear una nueva ficha de beneficiario
            </p>
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

    <form action="{{ route('ficha_beneficiario.store') }}" method="POST">
        @csrf
        
        <div class="form-grid">
            
            {{-- COLUMNA 1 --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Datos del Beneficiario
                </h4>

                <div class="form-group">
                    <label class="form-label" for="id_persona">
                        <span style="color: var(--color-danger);">*</span> Buscar Persona
                    </label>
                    <select id="id_persona" name="id_persona" required class="form-input @error('id_persona') is-invalid @enderror">
                        @if(isset($personaPreseleccionada))
                            <option value="{{ $personaPreseleccionada->id_persona }}" selected>
                                {{ $personaPreseleccionada->nombre }} {{ $personaPreseleccionada->apellido_paterno }} ({{ $personaPreseleccionada->carnet_identidad }})
                            </option>
                        @else
                            <option value="" disabled selected>-- Escriba Nombre o C.I. --</option>
                        @endif
                    </select>
                    <small class="form-help-text" style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 0.5rem;">
                        Busque personas registradas en el sistema
                    </small>
                    @error('id_persona')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
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
                        @endphp

                        @foreach ($discapacidades as $opcion)
                            <option value="{{ $opcion }}" {{ old('tipo_discapacidad') == $opcion ? 'selected' : '' }}>
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
                        <span style="color: var(--color-danger);">*</span> Fecha Registro
                    </label>
                    <input type="date" name="fecha_registro" value="{{ date('Y-m-d') }}" required 
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
                    {{-- Si solo hay 1 área disponible, la bloqueamos visualmente --}}
                    @if($areas->count() == 1)
                        <input type="text" value="{{ $areas->first()->nombre_area }}" 
                               class="form-input form-disabled" disabled 
                               style="background-color: var(--color-bg-light); color: var(--color-text-medium);">
                        <input type="hidden" name="area_intervencion_id" value="{{ $areas->first()->codigo_area }}">
                    @else
                        <select name="area_intervencion_id" required class="form-input @error('area_intervencion_id') is-invalid @enderror">
                            <option value="">-- Seleccione un área --</option>
                            @foreach($areas as $a) 
                                <option value="{{ $a->codigo_area }}" {{ old('area_intervencion_id') == $a->codigo_area ? 'selected' : '' }}>
                                    {{ $a->nombre_area }}
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
                                <input type="radio" name="retraso_en_desarrollo" value="1" {{ old('retraso_en_desarrollo') == '1' ? 'checked' : '' }}>
                                <span>¿Presenta retraso en desarrollo?</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="retraso_en_desarrollo" value="0" {{ old('retraso_en_desarrollo') != '1' ? 'checked' : '' }}>
                                <span>No presenta retraso en desarrollo</span>
                            </label>
                        </div>
                        
                        <div>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="incluido_en_educacion_2025" value="1" {{ old('incluido_en_educacion_2025') == '1' ? 'checked' : '' }}>
                                <span>¿Incluido en educación 2025?</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; color: var(--color-text-dark);">
                                <input type="radio" name="incluido_en_educacion_2025" value="0" {{ old('incluido_en_educacion_2025') != '1' ? 'checked' : '' }}>
                                <span>No incluido en educación 2025</span>
                            </label>
                        </div>
                    </div>
                </div>
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
                        Asegúrese de seleccionar una persona que no tenga ya una ficha de beneficiario activa.
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
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Guardar Ficha
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

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#id_persona').select2({
            placeholder: "Buscar por Nombre o C.I...",
            width: '100%',
            theme: 'bootstrap-5',
            ajax: {
                url: "{{ route('api.personas.search') }}", 
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return { results: data.results };
                },
                cache: true
            },
            minimumInputLength: 1
        });

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
```