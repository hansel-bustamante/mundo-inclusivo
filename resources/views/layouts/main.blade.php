<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mundo Inclusivo - Dashboard')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/institucion.css') }}">
    {{-- Agrega tus demás archivos CSS específicos aquí si es necesario --}}

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="app-container">
        <aside class="sidebar" role="navigation" aria-label="Menú principal">
            <div class="logo-section">
                <span class="logo-text">Mundo Inclusivo</span>
            </div>

            <div class="sidebar-inner">
                <nav role="menu" aria-label="Navegación principal">
                    <ul class="nav-list" role="menubar">
                        
                        {{-- DASHBOARD --}}
                        <li class="nav-item" role="none">
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               role="menuitem">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-10v10a1 1 0 001 1h3m-9 0h6m-3-2v2M9 10H7m0 0h2m-2 0v2" />
                                </svg>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-category">Catálogos</li>

                        {{-- INSTITUCIONES --}}
                        <li class="nav-item">
                            <a href="{{ route('institucion.index') }}"
                               class="nav-link {{ request()->routeIs('institucion.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h.01M3 21h18" />
                                </svg>
                                <span class="nav-link-text">Instituciones</span>
                            </a>
                        </li>

                        {{-- ÁREAS DE INTERVENCIÓN --}}
                        <li class="nav-item">
                            <a href="{{ route('area.index') }}"
                               class="nav-link {{ request()->routeIs('area.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.99 1.99 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="nav-link-text">Áreas</span>
                            </a>
                        </li>

                        {{-- CÓDIGOS DE ACTIVIDAD --}}
                        <li class="nav-item">
                            <a href="{{ route('codigo.index') }}"
                               class="nav-link {{ request()->routeIs('codigo.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                <span class="nav-link-text">Códigos</span>
                            </a>
                        </li>

                        <li class="nav-category">Gestión Principal</li>
                        
                        {{-- BENEFICIARIOS --}}
                        <li class="nav-item">
                            <a href="{{ route('beneficiario.index') }}"
                               class="nav-link {{ request()->routeIs('beneficiario.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 01-9.288 0M5 14c.792 0 1.547-.196 2.223-.553L12 18h2m-2-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="nav-link-text">Beneficiarios</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('ficha_registro.index') }}"
                                class="nav-link {{ request()->routeIs('ficha_registro.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {{-- Icono de documento/formulario con lápiz --}}
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="nav-link-text">Fichas de Registro</span>
                            </a>
                        </li>

                        {{-- ACTIVIDADES --}}
                        <li class="nav-item">
                            <a href="{{ route('actividad.index') }}"
                               class="nav-link {{ request()->routeIs('actividad.*') && !request()->routeIs('actividad.participantes.edit') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="nav-link-text">Actividades</span>
                            </a>
                        </li>

                        {{-- SESIONES --}}
                        <li class="nav-item">
                            <a href="{{ route('sesion.index') }}"
                               class="nav-link {{ request()->routeIs('sesion.*') && !request()->routeIs('sesion.asistencia.edit') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="nav-link-text">Sesiones</span>
                            </a>
                        </li>
                        
                        {{-- SEGUIMIENTO --}}
                        <li class="nav-item" role="none">
                            <a href="{{ route('seguimiento.index') }}"
                                class="nav-link {{ request()->routeIs('seguimiento.*') ? 'active' : '' }}"
                                role="menuitem">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                                <span class="nav-link-text">Seguimiento</span>
                            </a>
                        </li>

                        {{-- EVALUACIONES --}}
                        <li class="nav-item" role="none">
                            <a href="{{ route('evaluacion.index') }}"
                                class="nav-link {{ request()->routeIs('evaluacion.*') ? 'active' : '' }}"
                                role="menuitem">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0h6"></path>
                                </svg>
                                <span class="nav-link-text">Evaluaciones</span>
                            </a>
                        </li>

                        <li class="nav-category">Administración</li>

                        {{-- PERSONAS (BASE) --}}
                        <li class="nav-item">
                            <a href="{{ route('persona.index') }}" 
                               class="nav-link {{ request()->routeIs('persona.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-7 0V5a2 2 0 012-2h4a2 2 0 012 2v1m-7 0h2m-2 0h-2M15 9h5M7 9h5"/>
                                </svg>
                                <span class="nav-link-text">Personas (Base)</span>
                            </a>
                        </li>

                        {{-- USUARIOS --}}
                        <li class="nav-item">
                            <a href="{{ route('usuario.index') }}" 
                               class="nav-link {{ request()->routeIs('usuario.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 01-9.288 0M5 14c.792 0 1.547-.196 2.223-.553L12 18h2m-2-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="nav-link-text">Usuarios</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div> 
            
            <div class="sidebar-footer">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content" role="main">
            {{-- Aquí se inyecta el contenido específico de la vista --}}
            @yield('content')
        </main>
    </div>
</body>
</html>