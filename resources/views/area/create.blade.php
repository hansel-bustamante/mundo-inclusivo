```html
@extends('layouts.main')

@section('title', 'Crear Nueva Área de Intervención')

@section('content')
    
    <div class="content-card">

        <div class="header-container">
            <div>
                <h3 class="section-title">Registrar Nueva Área</h3>
                <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                    Complete la información para agregar una nueva área de intervención al sistema
                </p>
            </div>
            <a href="{{ route('area.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
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

        <form action="{{ route('area.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="codigo_area" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Código del Área
                    </label>
                    <input type="text" 
                           id="codigo_area" 
                           name="codigo_area" 
                           class="form-input @error('codigo_area') is-invalid @enderror"
                           value="{{ old('codigo_area') }}" 
                           required 
                           maxlength="20"
                           placeholder="Ej: SAL-01, EDU-05">
                    @error('codigo_area')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nombre_area" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Nombre del Área
                    </label>
                    <input type="text" 
                           id="nombre_area" 
                           name="nombre_area" 
                           class="form-input @error('nombre_area') is-invalid @enderror"
                           value="{{ old('nombre_area') }}" 
                           required 
                           maxlength="100"
                           placeholder="Ej: Centro de Salud La Paz">
                    @error('nombre_area')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="municipio" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Municipio
                    </label>
                    <input type="text" 
                           id="municipio" 
                           name="municipio" 
                           class="form-input @error('municipio') is-invalid @enderror"
                           value="{{ old('municipio') }}" 
                           required 
                           maxlength="100"
                           placeholder="Ej: La Paz, El Alto">
                    @error('municipio')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="provincia" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Provincia
                    </label>
                    <input type="text" 
                           id="provincia" 
                           name="provincia" 
                           class="form-input @error('provincia') is-invalid @enderror"
                           value="{{ old('provincia') }}" 
                           required 
                           maxlength="100"
                           placeholder="Ej: Murillo, Cercado">
                    @error('provincia')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group-full">
                    <label for="departamento" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Departamento
                    </label>
                    <input type="text" 
                           id="departamento" 
                           name="departamento" 
                           class="form-input @error('departamento') is-invalid @enderror"
                           value="{{ old('departamento') }}" 
                           required 
                           maxlength="100"
                           placeholder="Ej: La Paz, Cochabamba, Santa Cruz">
                    @error('departamento')
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
                            El código del área debe ser único y será utilizado para identificar esta área en todo el sistema.
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
                <a href="{{ route('area.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Área
                </button>
            </div>
        </form>

    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .form-group-full {
            grid-column: 1 / -1;
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
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
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
            // Capitalizar automáticamente los campos al perder el foco
            const capitalizeInput = (input) => {
                if (input.value) {
                    input.value = input.value.replace(/\b\w/g, function(l) {
                        return l.toUpperCase();
                    });
                }
            };

            const inputsToCapitalize = ['nombre_area', 'municipio', 'provincia', 'departamento'];
            
            inputsToCapitalize.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                if (input) {
                    input.addEventListener('blur', function() {
                        capitalizeInput(this);
                    });
                }
            });

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

            // Formatear código del área en mayúsculas
            const codigoInput = document.getElementById('codigo_area');
            if (codigoInput) {
                codigoInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
                
                // Sugerencia automática de código basado en nombre
                const nombreInput = document.getElementById('nombre_area');
                if (nombreInput) {
                    nombreInput.addEventListener('blur', function() {
                        if (this.value && !codigoInput.value) {
                            // Crear código sugerido (primeras 3 letras en mayúscula + número)
                            const sugerencia = this.value.substring(0, 3).toUpperCase() + '-01';
                            codigoInput.value = sugerencia;
                            
                            // Mostrar sugerencia
                            const sugerenciaElement = document.createElement('small');
                            sugerenciaElement.style.color = 'var(--color-text-light)';
                            sugerenciaElement.style.fontSize = '0.75rem';
                            sugerenciaElement.style.marginTop = '0.5rem';
                            sugerenciaElement.style.display = 'block';
                            sugerenciaElement.textContent = `Sugerencia: ${sugerencia}`;
                            
                            // Eliminar sugerencia anterior si existe
                            const prevSugerencia = codigoInput.parentNode.querySelector('.codigo-sugerencia');
                            if (prevSugerencia) {
                                prevSugerencia.remove();
                            }
                            
                            sugerenciaElement.classList.add('codigo-sugerencia');
                            codigoInput.parentNode.appendChild(sugerenciaElement);
                        }
                    });
                }
            }
        });
    </script>
@endsection
```