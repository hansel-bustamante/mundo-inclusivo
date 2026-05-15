```html
@extends('layouts.main')

@section('title', 'Editar Área de Intervención: ' . $area->nombre_area)

@section('content')
    
    <div class="content-card">

        <div class="header-container">
            <div>
                <h3 class="section-title">Editar Área de Intervención</h3>
                <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                        Código: {{ $area->codigo_area }}
                    </span>
                    <span style="color: var(--color-text-medium); font-weight: 500;">
                        {{ $area->nombre_area }}
                    </span>
                </div>
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

        {{-- INFORMACIÓN ACTUAL --}}
        <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.25rem;">Editando información de:</h5>
                    <p style="color: var(--color-text-medium); margin: 0; font-weight: 500;">
                        {{ $area->nombre_area }} • {{ $area->municipio }} • {{ $area->provincia }} • {{ $area->departamento }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('area.update', $area->codigo_area) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="codigo_area" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Código del Área
                    </label>
                    <input type="text" 
                           id="codigo_area" 
                           name="codigo_area" 
                           class="form-input @error('codigo_area') is-invalid @enderror"
                           value="{{ old('codigo_area', $area->codigo_area) }}" 
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
                           value="{{ old('nombre_area', $area->nombre_area) }}" 
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
                           value="{{ old('municipio', $area->municipio) }}" 
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
                           value="{{ old('provincia', $area->provincia) }}" 
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
                           value="{{ old('departamento', $area->departamento) }}" 
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

            {{-- ADVERTENCIA SOBRE ACTUALIZACIONES --}}
            <div style="background: #fff3cd; padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid #ffeaa7; margin: 2rem 0;">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <svg style="width: 1.5rem; height: 1.5rem; color: #856404; flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h5 style="font-family: var(--font-heading); font-weight: 600; color: #856404; margin-bottom: 0.5rem;">Importante</h5>
                        <p style="color: #856404; font-size: 0.875rem; margin: 0;">
                            Los cambios en el código del área podrían afectar a las fichas de beneficiarios asociadas. 
                            Asegúrese de que la información sea correcta antes de guardar.
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
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Guardar Cambios
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
            }
        });
    </script>
@endsection
```