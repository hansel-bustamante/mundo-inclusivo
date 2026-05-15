@extends('layouts.main')

@section('title', 'Crear Usuario')

@section('content')
<div class="content-card">

    <div class="header-container">
        <div>
            <h3 class="section-title">Registrar Nuevo Usuario</h3>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                Asigne una cuenta de acceso a una persona existente en el sistema
            </p>
        </div>
        <a href="{{ route('usuario.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
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

    <form action="{{ route('usuario.store') }}" method="POST">
        @csrf
        
        <div class="form-grid">
            
            {{-- Columna 1: Datos de Acceso --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Datos de Acceso
                </h4>

                {{-- PERSONA A ASIGNAR --}}
                <div class="form-group">
                    <label for="id_persona" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Persona a Asignar
                    </label>
                    <select id="id_persona" name="id_persona" class="form-input @error('id_persona') is-invalid @enderror" required>
                        <option value="">-- Seleccione una Persona --</option>
                        @foreach ($personas as $persona)
                            <option value="{{ $persona->id_persona }}" 
                                    @if(old('id_persona') == $persona->id_persona)
                                        selected
                                    @elseif(isset($idPersonaPrecargada) && $idPersonaPrecargada == $persona->id_persona)
                                        selected
                                    @endif
                                    >
                                {{ $persona->nombre }} {{ $persona->apellido_paterno }} (CI: {{ $persona->carnet_identidad ?? 'Sin CI' }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_persona')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- NOMBRE DE USUARIO --}}
                <div class="form-group">
                    <label for="nombre_usuario" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Nombre de Usuario
                    </label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" 
                           value="{{ old('nombre_usuario') }}" required maxlength="50"
                           class="form-input @error('nombre_usuario') is-invalid @enderror"
                           placeholder="Ej: juan.perez">
                    @error('nombre_usuario')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- CONTRASEÑA --}}
                <div class="form-group">
                    <label for="contrasena" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Contraseña Temporal
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="contrasena" name="contrasena" 
                               required minlength="6"
                               class="form-input @error('contrasena') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        <button type="button" 
                                onclick="togglePassword('contrasena')"
                                style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--color-text-light); cursor: pointer;">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('contrasena')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <small style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 0.5rem; display: block;">
                        El usuario deberá cambiar esta contraseña en su primer inicio de sesión
                    </small>
                </div>
            </div>
            
            {{-- Columna 2: Permisos y Área --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-info); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Permisos y Configuración
                </h4>
                
                {{-- ROL --}}
                <div class="form-group">
                    <label for="rol" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Rol / Tipo de Usuario
                    </label>
                    <select id="rol" name="rol" class="form-input @error('rol') is-invalid @enderror" required>
                        <option value="">-- Seleccione un Rol --</option>
                        <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="coordinador" {{ old('rol') == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="registrador" {{ old('rol') == 'registrador' ? 'selected' : '' }}>Registrador</option>
                    </select>
                    @error('rol')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- ÁREA DE INTERVENCIÓN --}}
                <div class="form-group">
                    <label for="area_intervencion_id" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Área de Intervención
                    </label>
                    <select id="area_intervencion_id" name="area_intervencion_id" class="form-input @error('area_intervencion_id') is-invalid @enderror" required>
                        <option value="">-- Seleccione un Área --</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->codigo_area }}" {{ old('area_intervencion_id') == $area->codigo_area ? 'selected' : '' }}>
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
                </div>
                
                {{-- CORREO --}}
                <div class="form-group">
                    <label for="correo" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Correo Electrónico
                    </label>
                    <input type="email" id="correo" name="correo" 
                           value="{{ old('correo') }}" required 
                           class="form-input @error('correo') is-invalid @enderror"
                           placeholder="Ej: usuario@dominio.com">
                    @error('correo')
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
                        Seleccione solo personas que no tengan ya una cuenta de usuario asignada.
                        El usuario recibirá instrucciones para cambiar su contraseña en el primer inicio de sesión.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
            <a href="{{ route('usuario.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Guardar Usuario
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
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const button = input.nextElementSibling;
        const icon = button.querySelector('svg');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Generar nombre de usuario automático basado en el nombre de la persona
        const personaSelect = document.getElementById('id_persona');
        const usuarioInput = document.getElementById('nombre_usuario');
        const correoInput = document.getElementById('correo');
        
        personaSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const nombreCompleto = selectedOption.text.split('(')[0].trim();
                
                // Generar nombre de usuario (ej: juan.perez)
                const partes = nombreCompleto.toLowerCase().split(' ');
                if (partes.length >= 2) {
                    const usuario = partes[0] + '.' + partes[1];
                    if (!usuarioInput.value) {
                        usuarioInput.value = usuario;
                    }
                }
                
                // Sugerir correo basado en el nombre de usuario
                if (!correoInput.value && usuarioInput.value) {
                    correoInput.value = usuarioInput.value + '@dominio.com';
                }
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

        // Validar formato de correo
        correoInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endsection