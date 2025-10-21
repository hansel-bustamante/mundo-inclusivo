<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mundo Inclusivo</title>
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    
</head>
<body class="body-layout">
    <div class="login-card">
        
        <div class="header-text">
            <h1 class="app-title">Mundo Inclusivo</h1>
            <p class="subtitle">Inicio de Sesión</p>
        </div>

        @if ($errors->any())
            <div class="error-alert" role="alert">
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
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input 
                    type="text" 
                    name="nombre_usuario" 
                    id="nombre_usuario" 
                    required 
                    autofocus
                    value="{{ old('nombre_usuario') }}"
                    placeholder="Ej: admin_global"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input 
                    type="password" 
                    name="contrasena" 
                    id="contrasena" 
                    required 
                    placeholder="Ingrese su contraseña"
                    class="form-input"
                >
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn-primary">
                    Iniciar Sesión
                </button>
            </div>
        </form>
        
        <div class="footer-links">
            <a href="#" class="link-help">¿Olvidaste tu contraseña?</a>
        </div>

    </div>
</body>
</html>