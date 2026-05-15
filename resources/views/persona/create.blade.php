@extends('layouts.main')

@section('title', 'Crear Persona')

@section('content')
<div class="content-card">

    {{-- ENCABEZADO MEJORADO --}}
    <div class="header-container">
        <div>
            <h3 class="section-title">Registrar Nueva Persona</h3>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                Datos base para Beneficiarios y Usuarios del sistema
            </p>
        </div>
        <a href="{{ route('persona.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Listado
        </a>
    </div>

    {{-- BLOQUE DE ERRORES MEJORADO --}}
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

    <form action="{{ route('persona.store') }}" method="POST">
        @csrf
        
        <div class="form-grid">
            
            {{-- Columna 1: Nombres --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Información Personal
                </h4>

                {{-- NOMBRE --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Nombre(s)
                    </label>
                    <input type="text" id="nombre" name="nombre" 
                           value="{{ old('nombre') }}" required 
                           class="form-input @error('nombre') is-invalid @enderror"
                           placeholder="Ingrese el nombre completo">
                    @error('nombre')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- APELLIDO_PATERNO --}}
                <div class="form-group">
                    <label for="apellido_paterno" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Apellido Paterno
                    </label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" 
                           value="{{ old('apellido_paterno') }}" required 
                           class="form-input @error('apellido_paterno') is-invalid @enderror"
                           placeholder="Ingrese apellido paterno">
                    @error('apellido_paterno')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- APELLIDO_MATERNO --}}
                <div class="form-group">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno" 
                           value="{{ old('apellido_materno') }}" 
                           class="form-input @error('apellido_materno') is-invalid @enderror"
                           placeholder="Ingrese apellido materno (opcional)">
                    @error('apellido_materno')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Columna 2: Otros Datos --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-info); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Información de Contacto
                </h4>
                
                {{-- FECHA_NACIMIENTO --}}
                <div class="form-group">
                    <label for="fecha_nacimiento" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Fecha de Nacimiento
                    </label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento') }}" required 
                           class="form-input @error('fecha_nacimiento') is-invalid @enderror">
                    @error('fecha_nacimiento')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- CARNET_IDENTIDAD --}}
                <div class="form-group">
                    <label for="carnet_identidad" class="form-label">Carnet de Identidad (C.I.)</label>
                    <input type="text" id="carnet_identidad" name="carnet_identidad" 
                           value="{{ old('carnet_identidad') }}" 
                           class="form-input @error('carnet_identidad') is-invalid @enderror"
                           placeholder="Ej: 1234567 LP">
                    @error('carnet_identidad')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- CELULAR --}}
                <div class="form-group">
                    <label for="celular" class="form-label">Celular</label>
                    <input type="text" id="celular" name="celular" 
                           value="{{ old('celular') }}" 
                           class="form-input @error('celular') is-invalid @enderror"
                           placeholder="Ej: 71234567">
                    @error('celular')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- PROCEDENCIA --}}
                <div class="form-group">
                    <label for="procedencia" class="form-label">Lugar de Procedencia</label>
                    <input type="text" id="procedencia" name="procedencia" 
                           value="{{ old('procedencia') }}" 
                           class="form-input @error('procedencia') is-invalid @enderror"
                           placeholder="Ej: La Paz, El Alto, etc.">
                    @error('procedencia')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- GENERO --}}
                <div class="form-group">
                    <label for="genero" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Género
                    </label>
                    <select id="genero" name="genero" required class="form-input @error('genero') is-invalid @enderror">
                        <option value="">-- Seleccione Género --</option>
                        <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('genero')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
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
                        Esta información servirá como base para crear posteriormente usuarios del sistema o fichas de beneficiarios.
                    </p>
                </div>
            </div>
        </div>

        {{-- ACCIONES DEL FORMULARIO MEJORADAS --}}
        <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
            <a href="{{ route('persona.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Guardar Persona
            </button>
        </div>
    </form>
</div>

<style>
    /* Estilos específicos para el formulario de creación */
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
    
    .form-group {
        display: flex;
        flex-direction: column;
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
    // Mejorar la experiencia del usuario
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer fecha máxima para fecha de nacimiento (hoy)
        const fechaInput = document.getElementById('fecha_nacimiento');
        const today = new Date().toISOString().split('T')[0];
        fechaInput.max = today;
        
        // Formatear automáticamente el campo de celular
        const celularInput = document.getElementById('celular');
        celularInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.substring(0, 8);
            }
            e.target.value = value;
        });
        
        // Formatear automáticamente el campo de carnet de identidad
        const ciInput = document.getElementById('carnet_identidad');
        ciInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            // Permitir solo números y letras
            value = value.replace(/[^0-9A-Z\s]/g, '');
            e.target.value = value;
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
    });
</script>
@endsection