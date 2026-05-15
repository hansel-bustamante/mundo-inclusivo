<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mundo Inclusivo</title>
    
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    
</head>
<body class="login-body">
    <div class="login-card">
        
        {{-- CONTENEDOR FLOTANTE CON DISPOSICIÓN LATERAL --}}
        <div style="display: flex; gap: 2rem; width: 100%;">
            
            {{-- COLUMNA IZQUIERDA: LOGO Y TÍTULO --}}
            <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding-right: 2rem; border-right: 2px solid var(--color-border);">
                <div class="logo-container" style="margin-bottom: 1.5rem; width: 100%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Mundo Inclusivo" class="login-logo" style="width: 120px;">
                </div>

                <div style="width: 100%;">
                    <h1 class="app-title" style="text-align: left; font-size: 2rem; margin-bottom: 0.5rem;">
                        Mundo Inclusivo
                    </h1>
                    <p class="subtitle" style="text-align: left; font-size: 1rem; color: var(--color-text-medium); margin-bottom: 1rem;">
                        Sistema de Gestión Integral
                    </p>
                    
                    {{-- INFORMACIÓN ADICIONAL IZQUIERDA --}}
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span style="font-size: 0.875rem; color: var(--color-text-medium);">
                                Plataforma segura y confiable
                            </span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span style="font-size: 0.875rem; color: var(--color-text-medium);">
                                Gestión de beneficiarios
                            </span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span style="font-size: 0.875rem; color: var(--color-text-medium);">
                                Acceso restringido
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: FORMULARIO DE LOGIN --}}
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <div style="margin-bottom: 2rem;">
                    <h2 class="app-title" style="text-align: center; font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--color-text-dark);">
                        Iniciar Sesión
                    </h2>
                    <p class="subtitle" style="text-align: center; font-size: 0.9375rem; color: var(--color-text-medium);">
                        Ingrese sus credenciales para acceder al sistema
                    </p>
                </div>

                @if ($errors->any())
                    <div class="error-alert" role="alert" style="margin-bottom: 1.5rem;">
                        <div class="error-header">
                            <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <p class="error-title">Error de Autenticación</p>
                        </div>
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="nombre_usuario" class="form-label" style="font-weight: 600; color: var(--color-text-dark);">
                            Nombre de Usuario
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="text" 
                                name="nombre_usuario" 
                                id="nombre_usuario" 
                                required 
                                autofocus
                                value="{{ old('nombre_usuario') }}"
                                placeholder="Ej: admin_global"
                                class="form-input"
                                style="padding-left: 2.5rem;"
                            >
                            <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contrasena" class="form-label" style="font-weight: 600; color: var(--color-text-dark);">
                            Contraseña
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                name="contrasena" 
                                id="contrasena" 
                                required 
                                placeholder="Ingrese su contraseña"
                                class="form-input"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;"
                            >
                            <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <button type="button" id="togglePassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--color-text-light);">
                                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-submit" style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.875rem 1.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- FOOTER --}}
        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--color-border-light); text-align: center; font-size: 0.75rem; color: var(--color-text-light);">
            <p style="margin: 0;">
                © {{ date('Y') }} Mundo Inclusivo • Sistema de Gestión • Versión 1.0
            </p>
        </div>
    </div>

    <style>
        .login-card {
            max-width: 900px;
            width: 100%;
            padding: 2.5rem;
            border-radius: var(--border-radius-xl);
        }
        
        @media (max-width: 768px) {
            .login-card {
                max-width: 100%;
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .login-card > div:first-child {
                flex-direction: column;
                gap: 2rem;
            }
            
            .login-card > div:first-child > div:first-child {
                border-right: none;
                border-bottom: 2px solid var(--color-border);
                padding-right: 0;
                padding-bottom: 2rem;
                align-items: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle visibilidad de contraseña
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('contrasena');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar ícono
                const icon = this.querySelector('svg');
                if (type === 'text') {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
                    this.style.color = 'var(--color-primary)';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                    this.style.color = 'var(--color-text-light)';
                }
            });
            
            // Validación en tiempo real
            const formInputs = document.querySelectorAll('.form-input');
            formInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value && this.hasAttribute('required')) {
                        this.style.borderColor = 'var(--color-danger)';
                    } else {
                        this.style.borderColor = 'var(--color-border)';
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.style.borderColor = 'var(--color-primary)';
                    this.style.boxShadow = '0 0 0 3px rgba(67, 160, 71, 0.1)';
                });
            });
        });
    </script>
</body>
</html>