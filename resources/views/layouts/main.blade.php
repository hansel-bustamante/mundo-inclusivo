<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mundo Inclusivo - Dashboard')</title>
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/institucion.css') }}">
    
</head>
<body>
    <div class="app-container">
        
        <aside class="sidebar">
            <div class="logo-section">
                <span class="logo-text">Mundo Inclusivo</span>
            </div>
            
            <nav>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-10v10a1 1 0 001 1h3m-9 0h6m-3-2v2M9 10H7m0 0h2m-2 0v2"></path></svg>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    
                    {{-- SECCIÓN CATÁLOGOS --}}
                    <li class="nav-item">
                        <span class="nav-link-text" style="color: #4b5563; padding: 0.75rem 1rem 0.25rem; font-size: 0.75rem; text-transform: uppercase;">Catálogos</span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('institucion.index') }}" class="nav-link {{ request()->routeIs('institucion.*') ? 'active' : '' }}">
                             <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h.01M3 21h18"></path></svg>
                            <span class="nav-link-text">Instituciones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('area.index') }}" class="nav-link {{ request()->routeIs('area.*') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.99 1.99 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="nav-link-text">Áreas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('codigo.index') }}" class="nav-link {{ request()->routeIs('codigo.*') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            <span class="nav-link-text">Códigos</span>
                        </a>
                    </li>
                    
                    {{-- INICIO DEL MÓDULO PRINCIPAL: ACTIVIDADES --}}
                    {{-- Separador Visual (Estilo similar al que usas para 'Otros') --}}
                    <li class="nav-item" style="margin-top: 1rem;">
                        <span class="nav-link-text" style="color: #4b5563; padding: 0.75rem 1rem 0.25rem; font-size: 0.75rem; text-transform: uppercase;">Gestión Principal</span>
                    </li>
                    
                    {{-- NUEVO: Enlace a CRUD de Actividades --}}
                    <li class="nav-item">
                        <a href="{{ route('actividad.index') }}" class="nav-link {{ request()->routeIs('actividad.*') ? 'active' : '' }}">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="nav-link-text">Actividades</span>
                        </a>
                    </li>
                    
                    {{-- SECCIÓN OTROS (Original) --}}
                    <li class="nav-item" style="margin-top: 1rem;">
                        <span class="nav-link-text" style="color: #4b5563; padding: 0.75rem 1rem 0.25rem; font-size: 0.75rem; text-transform: uppercase;">Otros</span>
                    </li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">
                            <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M7.5 14a6.002 6.002 0 005.965 3.332L12 18h2m-2-4a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="nav-link-text">Usuarios</span>
                        </a>
                    </li>

                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-area">
                @yield('content')
            </div>
        </main>

    </div>
</body>
</html>