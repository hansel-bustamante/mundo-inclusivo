```html
@extends('layouts.main')

@section('title', 'Crear Nuevo Código de Actividad')

@section('content')
    
    <div class="content-card">

        <div class="header-container">
            <div>
                <h3 class="section-title">Registrar Nuevo Código de Actividad</h3>
                <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                    Define un código único de 2 caracteres y el nombre de la actividad que representa
                </p>
            </div>
            <a href="{{ route('codigo.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
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

        <form action="{{ route('codigo.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="codigo_actividad" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Código (1 o 2 caracteres)
                    </label>
                    <div style="position: relative;">
                        <input type="text" 
                               id="codigo_actividad" 
                               name="codigo_actividad" 
                               class="form-input @error('codigo_actividad') is-invalid @enderror"
                               value="{{ old('codigo_actividad') }}" 
                               required 
                               maxlength="2"
                               style="text-transform: uppercase; font-weight: 700; letter-spacing: 0.1em; padding-right: 3rem;"
                               placeholder="Ej: A, A1">
                        <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light); font-size: 0.75rem; font-weight: 600;">
                            1 o 2 letras
                        </div>
                    </div>
                    @error('codigo_actividad')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group-full">
                    <label for="nombre_actividad" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Nombre de la Actividad
                    </label>
                    <input type="text" 
                           id="nombre_actividad" 
                           name="nombre_actividad" 
                           class="form-input @error('nombre_actividad') is-invalid @enderror"
                           value="{{ old('nombre_actividad') }}" 
                           required 
                           maxlength="100"
                           placeholder="Ej: Taller de Inclusión Social, Capacitación Docente">
                    @error('nombre_actividad')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-full">
                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              class="form-input @error('descripcion') is-invalid @enderror"
                              rows="4"
                              placeholder="Describa el propósito y alcance de esta actividad...">{{ old('descripcion') }}</textarea>
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
                            El código debe ser único, de máximo 2 caracteres (letras o números).
                            Los códigos serán utilizados para identificar actividades en el sistema.
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
                <a href="{{ route('codigo.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Código
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
        
        textarea.form-input {
            resize: vertical;
            min-height: 100px;
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
            // Convertir automáticamente a mayúsculas y limitar a 2 caracteres
            const codigoInput = document.getElementById('codigo_actividad');
            codigoInput.addEventListener('input', function(e) {
                let value = e.target.value.toUpperCase();
                // Permitir solo letras y números
                value = value.replace(/[^A-Z0-9]/g, '');
                // Limitar a 2 caracteres
                value = value.substring(0, 2);
                e.target.value = value;
                
                // Validación visual
                if (value.length === 2) {
                    this.style.borderColor = 'var(--color-green)';
                    this.style.backgroundColor = 'var(--color-primary-light)';
                } else {
                    this.style.borderColor = '';
                    this.style.backgroundColor = '';
                }
            });

            // Capitalizar automáticamente el nombre de la actividad
            const nombreInput = document.getElementById('nombre_actividad');
            nombreInput.addEventListener('blur', function() {
                if (this.value) {
                    // Capitalizar la primera letra de cada palabra
                    this.value = this.value.replace(/\b\w/g, function(l) {
                        return l.toUpperCase();
                    });
                }
            });

            // Validación en tiempo real para campos requeridos
            const requiredInputs = document.querySelectorAll('input[required], textarea[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Contador de caracteres para descripción
            const descripcionInput = document.getElementById('descripcion');
            descripcionInput.addEventListener('input', function() {
                const maxLength = 500;
                const currentLength = this.value.length;
                const counter = document.getElementById('descripcionCounter') || createCounter();
                
                counter.textContent = `${currentLength}/${maxLength} caracteres`;
                counter.style.color = currentLength > maxLength * 0.9 ? 'var(--color-danger)' : 'var(--color-text-light)';
                
                if (currentLength > maxLength) {
                    this.value = this.value.substring(0, maxLength);
                }
            });

            function createCounter() {
                const counter = document.createElement('div');
                counter.id = 'descripcionCounter';
                counter.style.fontSize = '0.75rem';
                counter.style.color = 'var(--color-text-light)';
                counter.style.marginTop = '0.5rem';
                counter.style.textAlign = 'right';
                descripcionInput.parentNode.appendChild(counter);
                return counter;
            }
        });
    </script>

@endsection