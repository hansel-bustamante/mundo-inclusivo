@extends('layouts.main')

@section('title', 'Cambio de Contraseña Requerido')

@section('content')
<div class="content-card" style="max-width: 500px; margin: 4rem auto; position: relative;">
    
    {{-- DECORACIÓN DE FONDO --}}
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--color-danger) 0%, var(--color-warning) 100%); border-radius: var(--border-radius) var(--border-radius) 0 0;"></div>

    <div style="text-align: center; margin-bottom: 2rem;">
        {{-- ALERTA MEJORADA --}}
        <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); color: #c2410c; padding: 1.5rem; border-radius: var(--border-radius); border: 2px solid #fed7aa; margin-bottom: 1.5rem; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #c2410c 0%, #f97316 100%);"></div>
            <div style="width: 4rem; height: 4rem; background: linear-gradient(135deg, #c2410c 0%, #f97316 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h4 style="font-family: var(--font-heading); font-weight: 700; color: #c2410c; margin-bottom: 0.5rem;">
                Acción Requerida
            </h4>
            <p style="color: #c2410c; font-size: 0.9375rem; margin: 0; line-height: 1.5;">
                Por seguridad, su contraseña temporal ha expirado o fue restablecida. 
                Debe configurar una nueva contraseña personal para continuar.
            </p>
        </div>

        {{-- INDICADOR DE SEGURIDAD --}}
        <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 0.75rem; height: 0.75rem; background: var(--color-danger); border-radius: 50%;"></div>
                <span style="font-size: 0.875rem; color: var(--color-text-medium); font-weight: 500;">Seguridad</span>
            </div>
            <div style="width: 2rem; height: 1px; background: var(--color-border);"></div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 0.75rem; height: 0.75rem; background: var(--color-warning); border-radius: 50%;"></div>
                <span style="font-size: 0.875rem; color: var(--color-text-medium); font-weight: 500;">Actualización</span>
            </div>
            <div style="width: 2rem; height: 1px; background: var(--color-border);"></div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 0.75rem; height: 0.75rem; background: var(--color-primary); border-radius: 50%;"></div>
                <span style="font-size: 0.875rem; color: var(--color-text-medium); font-weight: 500;">Acceso</span>
            </div>
        </div>
    </div>

    {{-- FORMULARIO MEJORADO --}}
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label" style="font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.5rem;">
                    <span style="color: var(--color-danger);">*</span> Nueva Contraseña
                </label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" 
                           class="form-input @error('password') is-invalid @enderror" 
                           required minlength="6" 
                           placeholder="Ingrese su nueva contraseña"
                           style="padding-right: 2.5rem;">
                    <svg id="togglePassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-text-light); cursor: pointer;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                @error('password')
                    <div class="form-error-message">
                        <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
                <div style="margin-top: 0.5rem;">
                    <div id="password-strength" style="height: 4px; background: var(--color-border); border-radius: 2px; overflow: hidden;">
                        <div id="password-strength-bar" style="height: 100%; width: 0%; background: var(--color-danger); transition: width 0.3s, background 0.3s;"></div>
                    </div>
                    <small style="color: var(--color-text-light); font-size: 0.75rem; display: block; margin-top: 0.25rem;">
                        Mínimo 6 caracteres - Incluye mayúsculas, minúsculas y números
                    </small>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.5rem;">
                    <span style="color: var(--color-danger);">*</span> Confirmar Nueva Contraseña
                </label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="form-input @error('password_confirmation') is-invalid @enderror" 
                           required minlength="6" 
                           placeholder="Repita la contraseña"
                           style="padding-right: 2.5rem;">
                    <svg id="togglePasswordConfirmation" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-text-light); cursor: pointer;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                @error('password_confirmation')
                    <div class="form-error-message">
                        <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
                <div id="password-match" style="margin-top: 0.5rem;">
                    <small style="color: var(--color-text-light); font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                        <svg id="match-icon" style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span id="match-text">Las contraseñas deben coincidir</span>
                    </small>
                </div>
            </div>
        </div>

        {{-- REQUISITOS DE CONTRASEÑA --}}
        <div style="background: var(--color-bg-light); padding: 1rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin: 1.5rem 0;">
            <h5 style="font-family: var(--font-heading); font-size: 0.875rem; font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.5rem;">
                Requisitos de seguridad:
            </h5>
            <ul style="color: var(--color-text-medium); font-size: 0.75rem; padding-left: 1.25rem; margin: 0;">
                <li>Mínimo 6 caracteres</li>
                <li>Incluir letras mayúsculas y minúsculas</li>
                <li>Incluir al menos un número</li>
                <li>No usar contraseñas comunes o predecibles</li>
            </ul>
        </div>

        {{-- BOTÓN DE ACCIÓN --}}
        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; gap: 0.5rem; padding: 0.875rem 1.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                Guardar Nueva Contraseña e Ingresar
            </button>
        </div>
    </form>
</div>

<style>
    .form-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.1);
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
        .content-card {
            max-width: 100%;
            margin: 2rem auto;
            padding: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const strengthBar = document.getElementById('password-strength-bar');
        const matchIcon = document.getElementById('match-icon');
        const matchText = document.getElementById('match-text');
        
        // Toggle visibilidad de contraseña
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.style.color = type === 'text' ? 'var(--color-primary)' : 'var(--color-text-light)';
        });
        
        togglePasswordConfirmation.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.style.color = type === 'text' ? 'var(--color-primary)' : 'var(--color-text-light)';
        });
        
        // Validar fuerza de contraseña
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Longitud
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 25;
            
            // Complejidad
            if (/[A-Z]/.test(password)) strength += 25;
            if (/\d/.test(password)) strength += 25;
            
            // Actualizar barra de fuerza
            strengthBar.style.width = strength + '%';
            
            // Cambiar color según fuerza
            if (strength < 50) {
                strengthBar.style.background = 'var(--color-danger)';
            } else if (strength < 75) {
                strengthBar.style.background = 'var(--color-warning)';
            } else {
                strengthBar.style.background = 'var(--color-primary)';
            }
            
            // Verificar coincidencia
            checkPasswordMatch();
        });
        
        // Verificar coincidencia de contraseñas
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword.length === 0) {
                matchIcon.style.color = 'var(--color-text-light)';
                matchText.style.color = 'var(--color-text-light)';
                matchText.textContent = 'Las contraseñas deben coincidir';
                return;
            }
            
            if (password === confirmPassword) {
                matchIcon.style.color = 'var(--color-primary)';
                matchIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                matchText.style.color = 'var(--color-primary)';
                matchText.textContent = 'Las contraseñas coinciden';
            } else {
                matchIcon.style.color = 'var(--color-danger)';
                matchIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                matchText.style.color = 'var(--color-danger)';
                matchText.textContent = 'Las contraseñas no coinciden';
            }
        }
        
        // Validación en tiempo real
        const requiredInputs = document.querySelectorAll('input[required]');
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