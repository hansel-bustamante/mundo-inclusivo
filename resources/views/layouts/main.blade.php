<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mundo Inclusivo - Dashboard')</title>

    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ESTILOS ADICIONALES PARA EL PERFIL DE USUARIO Y MEJORAS VISUALES --}}
    <style>
        /* Perfil de usuario mejorado */
        .user-profile-section {
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        }
        
        .user-info-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .user-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.125rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .user-details {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 1rem;
            color: var(--color-white);
            margin-bottom: 0.25rem;
        }
        
        .user-email {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
        }
        
        .user-role-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: var(--color-white);
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .nav-category {
            color: var(--color-link-light);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 1rem 1rem 0.5rem;
            font-weight: 600;
            opacity: 0.7;
            font-family: var(--font-ui);
            margin-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-category:first-of-type {
            border-top: none;
            margin-top: 0;
        }
        
        /* Mejora para íconos de navegación */
        .nav-link-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            transition: all var(--transition-speed) var(--transition-bounce);
        }
        
        /* Efecto hover mejorado para enlaces */
        .nav-link:hover .nav-link-icon {
            transform: translateX(3px) scale(1.1);
        }
        
        .nav-link.active .nav-link-icon {
            transform: translateX(3px) scale(1.1);
        }
        
        /* Responsividad mejorada */
        @media (max-width: 767px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
            }
            
            .sidebar.is-open {
                transform: translateX(0);
            }
            
            .user-profile-section {
                padding: 1rem;
            }
            
            .user-avatar {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="app-container">
        <aside class="sidebar" role="navigation" aria-label="Menú principal">
            
            {{-- LOGO MEJORADO --}}
            <div class="logo-section" style="position: relative; overflow: hidden;">
                <span class="logo-text">Mundo Inclusivo</span>
                <div style="position: absolute; top: 0; right: 0; width: 60px; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1));"></div>
            </div>

            {{-- ============================================================== --}}
            {{-- PERFIL DEL USUARIO LOGUEADO MEJORADO --}}
            {{-- ============================================================== --}}
            @if(Auth::check())
                <div class="user-profile-section">
                    <div class="user-info-wrapper">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->nombre_usuario, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <div class="user-name">
                                {{ Auth::user()->nombre_usuario }}
                            </div>
                            <div class="user-email">
                                {{ Auth::user()->email ?? 'Usuario del sistema' }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <span class="user-role-badge">
                            {{ ucfirst(Auth::user()->rol) }}
                        </span>
                    </div>
                </div>
            @endif
            {{-- ============================================================== --}}

            <div class="sidebar-inner">
                <nav role="menu" aria-label="Navegación principal">
                    <ul class="nav-list" role="menubar">
                        
                        {{-- ============================================================== --}}
                        {{-- 1. DASHBOARD (Dinámico según rol) --}}
                        {{-- ============================================================== --}}
                        <li class="nav-item" role="none">
                            @php
                                $dashboardRoute = match(Auth::user()->rol ?? 'guest') {
                                    'admin' => 'admin.dashboard',
                                    'registrador' => 'registrador.dashboard',
                                    'coordinador' => 'coordinador.dashboard',
                                    default => 'login'
                                };
                            @endphp
                            <a href="{{ route($dashboardRoute) }}"
                               class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}"
                               role="menuitem">
                                <svg class="nav-link-icon" viewBox="0 0 24 24">
                                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-10v10a1 1 0 001 1h3m-9 0h6m-3-2v2M9 10H7m0 0h2m-2 0v2" />
                                </svg>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>

                        {{-- ============================================================== --}}
                        {{-- 2. CATÁLOGOS COMUNES --}}
                        {{-- ============================================================== --}}
                        <li class="nav-category">Catálogos</li>

                        <li class="nav-item">
                            <a href="{{ route('institucion.index') }}"
                               class="nav-link {{ request()->routeIs('institucion.*') ? 'active' : '' }}">
                                <svg class="nav-link-icon" viewBox="0 0 24 24">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h.01M3 21h18" />
                                </svg>
                                <span class="nav-link-text">Instituciones</span>
                            </a>
                        </li>

                        {{-- ============================================================== --}}
                        {{-- 3. GESTIÓN OPERATIVA (ADMIN y REGISTRADOR) --}}
                        {{-- ============================================================== --}}
                        @if(Auth::check() && in_array(Auth::user()->rol, ['admin', 'registrador']))
                            <li class="nav-category">Gestión Operativa</li>

                            <li class="nav-item">
                                <a href="{{ route('persona.index') }}"
                                   class="nav-link {{ request()->routeIs('persona.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-7 0V5a2 2 0 012-2h4a2 2 0 012 2v1m-7 0h2m-2 0h-2M15 9h5M7 9h5" />
                                    </svg>
                                    <span class="nav-link-text">Personas (Base)</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('ficha_beneficiario.index') }}"
                                   class="nav-link {{ request()->routeIs('ficha_beneficiario.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 01-9.288 0M5 14c.792 0 1.547-.196 2.223-.553L12 18h2m-2-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="nav-link-text">Beneficiarios</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('actividad.index') }}"
                                   class="nav-link {{ request()->routeIs('actividad.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="nav-link-text">Actividades</span>
                                </a>
                            </li>
                        @endif

                        {{-- ============================================================== --}}
                        {{-- 4. MONITOREO (ADMIN y COORDINADOR) --}}
                        {{-- ============================================================== --}}
                        @if(Auth::check() && in_array(Auth::user()->rol, ['admin', 'coordinador']))
                            <li class="nav-category">Monitoreo</li>

                            <li class="nav-item">
                                <a href="{{ route('seguimiento.index') }}"
                                   class="nav-link {{ request()->routeIs('seguimiento.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                    <span class="nav-link-text">Seguimiento</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('evaluacion.index') }}"
                                   class="nav-link {{ request()->routeIs('evaluacion.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0h6" />
                                    </svg>
                                    <span class="nav-link-text">Evaluaciones</span>
                                </a>
                            </li>
                        @endif

                        {{-- ============================================================== --}}
                        {{-- 5. REPORTES (VISIBLE PARA TODOS, SEGREGADO EN CONTROLADOR) --}}
                        {{-- ============================================================== --}}
                        @if(Auth::check())
                            <li class="nav-category">Reportes y Análisis</li>
                            <li class="nav-item">
                                <a href="{{ route('reportes.index') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="nav-link-text">Generación de Reportes</span>
                                </a>
                            </li>
                        @endif

                        {{-- ============================================================== --}}
                        {{-- 6. ADMINISTRACIÓN (SOLO ADMIN) --}}
                        {{-- ============================================================== --}}
                        @if(Auth::check() && Auth::user()->rol == 'admin')
                            <li class="nav-category">Administración</li>

                            <li class="nav-item">
                                <a href="{{ route('usuario.index') }}"
                                   class="nav-link {{ request()->routeIs('usuario.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 01-9.288 0M5 14c.792 0 1.547-.196 2.223-.553L12 18h2m-2-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="nav-link-text">Usuarios</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('area.index') }}"
                                   class="nav-link {{ request()->routeIs('area.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M17.657 16.657L13.414 20.9a1.99 1.99 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="nav-link-text">Áreas</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('codigo.index') }}"
                                   class="nav-link {{ request()->routeIs('codigo.*') ? 'active' : '' }}">
                                    <svg class="nav-link-icon" viewBox="0 0 24 24">
                                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    <span class="nav-link-text">Códigos</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>

            {{-- FOOTER MEJORADO --}}
            <div class="sidebar-footer">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg class="nav-link-icon" viewBox="0 0 24 24" style="margin: 0;">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content" role="main">
            @yield('content')
        </main>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    {{-- SCRIPT PARA TOGGLE DE SIDEBAR EN MÓVILES --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar en móviles
            const sidebarToggle = document.createElement('button');
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            sidebarToggle.className = 'sidebar-toggle';
            sidebarToggle.style.cssText = `
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: var(--color-primary);
                color: white;
                border: none;
                border-radius: var(--border-radius);
                width: 3rem;
                height: 3rem;
                display: none;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                cursor: pointer;
                box-shadow: var(--shadow-base);
                transition: all 0.3s var(--transition-bounce);
            `;
            
            document.body.appendChild(sidebarToggle);
            
            const sidebar = document.querySelector('.sidebar');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('is-open');
                this.style.left = sidebar.classList.contains('is-open') ? 'calc(100% - 4rem)' : '1rem';
                this.innerHTML = sidebar.classList.contains('is-open') ? 
                    '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });
            
            // Mostrar/ocultar toggle según tamaño de pantalla
            function handleResize() {
                if (window.innerWidth <= 767) {
                    sidebarToggle.style.display = 'flex';
                    if (!sidebar.classList.contains('is-open')) {
                        sidebarToggle.style.left = '1rem';
                    }
                } else {
                    sidebarToggle.style.display = 'none';
                    sidebar.classList.remove('is-open');
                }
            }
            
            // Cerrar sidebar al hacer clic fuera en móviles
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 767 && 
                    !sidebar.contains(e.target) && 
                    !sidebarToggle.contains(e.target) &&
                    sidebar.classList.contains('is-open')) {
                    sidebar.classList.remove('is-open');
                    sidebarToggle.style.left = '1rem';
                    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
            
            window.addEventListener('resize', handleResize);
            handleResize(); // Ejecutar al cargar
            
            // Mejorar experiencia de navegación con teclado
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth <= 767 && sidebar.classList.contains('is-open')) {
                    sidebar.classList.remove('is-open');
                    sidebarToggle.style.left = '1rem';
                    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>