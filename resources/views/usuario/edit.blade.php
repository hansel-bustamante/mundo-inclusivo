@extends('layouts.main')

@section('title', 'Editar Usuario')

@section('content')
<div class="content-card">

    <div class="header-container">
        <div>
            <h3 class="section-title">Editar Usuario del Sistema</h3>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap;">
                <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                    ID: {{ $usuario->id_persona }}
                </span>
                <span style="color: var(--color-text-medium); font-weight: 500;">
                    {{ $usuario->persona->nombre ?? 'N/A' }} {{ $usuario->persona->apellido_paterno ?? '' }}
                </span>
            </div>
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

    {{-- INFORMACIÓN ACTUAL --}}
    <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.25rem;">Editando usuario:</h5>
                <p style="color: var(--color-text-medium); margin: 0; font-weight: 500;">
                    {{ $usuario->persona->nombre ?? 'N/A' }} {{ $usuario->persona->apellido_paterno ?? '' }}
                    • Usuario: {{ $usuario->nombre_usuario }}
                    • Rol: {{ ucfirst($usuario->rol) }}
                    • Área: {{ $usuario->areaIntervencion->nombre_area ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('usuario.update', $usuario->id_persona) }}" method="POST">
        @csrf
        @method('PUT')
        
        {{-- Campo oculto para mantener la referencia al ID, necesario para el update --}}
        <input type="hidden" name="id_persona" value="{{ $usuario->id_persona }}">

        <div class="form-grid">
            
            {{-- Columna 1: Datos de Acceso --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Datos de Acceso
                </h4>
                
                {{-- CARNET IDENTIDAD (Mostrar, no editable) --}}
                <div class="form-group">
                    <label for="carnet_identidad" class="form-label">Carnet de Identidad</label>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--border-radius);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span style="font-weight: 600; color: var(--color-text-dark);">
                            {{ $usuario->persona->carnet_identidad ?? 'N/A' }}
                        </span>
                    </div>
                    <small style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 0.5rem; display: block;">
                        El C.I. identifica a la persona asociada y no puede cambiarse aquí
                    </small>
                </div>
                
                {{-- NOMBRE_USUARIO --}}
                <div class="form-group">
                    <label for="nombre_usuario" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Nombre de Usuario
                    </label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" 
                           value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" required 
                           class="form-input @error('nombre_usuario') is-invalid @enderror"
                           placeholder="Ingrese nombre de usuario">
                    @error('nombre_usuario')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- CONTRASENA --}}
                <div class="form-group">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" 
                           class="form-input @error('contrasena') is-invalid @enderror"
                           placeholder="Dejar vacío para mantener la actual"
                           autocomplete="new-password"> {{-- Autocomplete off para evitar que el navegador rellene --}}
                    @error('contrasena')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <small style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 0.5rem; display: block;">
                        Dejar vacío para no cambiar la contraseña actual
                    </small>
                </div>
            </div>

            {{-- Columna 2: Datos de Rol y Contacto --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-info); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Rol y Permisos
                </h4>
                
                {{-- ROL --}}
                <div class="form-group">
                    <label for="rol" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Rol del Usuario
                    </label>
                    <select id="rol" name="rol" required class="form-input @error('rol') is-invalid @enderror">
                        <option value="">-- Seleccione un Rol --</option>
                        @php $currentRol = old('rol', $usuario->rol); @endphp
                        <option value="admin" {{ $currentRol == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="coordinador" {{ $currentRol == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="registrador" {{ $currentRol == 'registrador' ? 'selected' : '' }}>Registrador</option>
                    </select>
                    @error('rol')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                {{-- AREA_INTERVENCION_ID --}}
                <div class="form-group">
                    <label for="area_intervencion_id" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Área de Intervención
                    </label>
                    <select id="area_intervencion_id" name="area_intervencion_id" required 
                            class="form-input @error('area_intervencion_id') is-invalid @enderror">
                        <option value="">-- Seleccione Área --</option>
                        @php $currentArea = old('area_intervencion_id', $usuario->area_intervencion_id); @endphp
                        @foreach ($areas as $area)
                            <option value="{{ $area->codigo_area }}" 
                                    {{ $currentArea == $area->codigo_area ? 'selected' : '' }}>
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
                           value="{{ old('correo', $usuario->correo) }}" required 
                           class="form-input @error('correo') is-invalid @enderror"
                           placeholder="ejemplo@dominio.com">
                    @error('correo')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ADVERTENCIA SOBRE ACTUALIZACIONES --}}
        <div style="background: #fff3cd; padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid #ffeaa7; margin: 2rem 0;">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #856404; flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <h5 style="font-family: var(--font-heading); font-weight: 600; color: #856404; margin-bottom: 0.5rem;">Información Importante</h5>
                    <p style="color: #856404; font-size: 0.875rem; margin: 0;">
                        Los cambios en el rol pueden afectar los permisos del usuario en el sistema.
                        Si cambia la contraseña, el usuario deberá usar la nueva en su próximo inicio de sesión.
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
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Actualizar Usuario
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
        // Toggle para mostrar/ocultar contraseña
        const passwordInput = document.getElementById('contrasena');
        const togglePassword = document.createElement('button');
        togglePassword.type = 'button';
        togglePassword.innerHTML = `
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        `;
        togglePassword.style.position = 'absolute';
        togglePassword.style.right = '0.75rem';
        togglePassword.style.top = '50%';
        togglePassword.style.transform = 'translateY(-50%)';
        togglePassword.style.background = 'none';
        togglePassword.style.border = 'none';
        togglePassword.style.cursor = 'pointer';
        togglePassword.style.color = 'var(--color-text-light)';
        
        passwordInput.style.paddingRight = '2.5rem';
        passwordInput.parentElement.style.position = 'relative';
        passwordInput.parentElement.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ? `
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            ` : `
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>
            `;
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

        // Validación de email
        const emailInput = document.getElementById('correo');
        emailInput.addEventListener('blur', function() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailPattern.test(this.value)) {
                this.classList.add('is-invalid');
                if (!this.nextElementSibling.classList.contains('form-error-message')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'form-error-message';
                    errorDiv.innerHTML = `
                        <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ingrese un correo electrónico válido
                    `;
                    this.parentNode.insertBefore(errorDiv, this.nextElementSibling);
                }
            } else {
                this.classList.remove('is-invalid');
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('form-error-message')) {
                    errorMsg.remove();
                }
            }
        });
    });
</script>
@endsection