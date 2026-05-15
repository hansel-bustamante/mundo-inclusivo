@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrapper">

    {{-- 1. ENCABEZADO MINIMALISTA --}}
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Panel de Control</h1>
            <p class="page-subtitle">
                Hola, <strong>{{ Auth::user()->nombre_usuario }}</strong>. 
                Área: <span class="badge-area">{{ Auth::user()->area_intervencion_id }}</span>
            </p>
        </div>
        <div class="date-badge">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ now()->format('d M, Y') }}
        </div>
    </div>

    {{-- 2. TARJETAS DE ESTADÍSTICAS (KPIs) --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon icon-blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalPersonas }}</div>
                <div class="kpi-label">Personas</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon icon-green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalFichas }}</div>
                <div class="kpi-label">Fichas</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon icon-orange">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalActividades }}</div>
                <div class="kpi-label">Actividades</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon icon-purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalInstituciones }}</div>
                <div class="kpi-label">Instituciones</div>
            </div>
        </div>
    </div>

    {{-- 3. CONTENIDO PRINCIPAL (2 COLUMNAS) --}}
    <div class="main-grid">
        
        {{-- COLUMNA IZQUIERDA: ACCESOS --}}
        <div class="section-container">
            <h3 class="section-heading">Módulos de Gestión</h3>
            <div class="modules-grid">
                <a href="{{ route('persona.index') }}" class="module-card">
                    <div class="module-icon-bg blue">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="module-title">Personas Base</span>
                    <span class="module-desc">Registro civil y datos</span>
                </a>

                <a href="{{ route('ficha_beneficiario.index') }}" class="module-card">
                    <div class="module-icon-bg green">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="module-title">Fichas Técnicas</span>
                    <span class="module-desc">Diagnósticos y discapacidad</span>
                </a>

                <a href="{{ route('actividad.index') }}" class="module-card">
                    <div class="module-icon-bg orange">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="module-title">Actividades</span>
                    <span class="module-desc">Talleres y eventos</span>
                </a>

                <a href="{{ route('institucion.index') }}" class="module-card">
                    <div class="module-icon-bg purple">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="module-title">Instituciones</span>
                    <span class="module-desc">Convenios y entidades</span>
                </a>
            </div>
        </div>

        {{-- COLUMNA DERECHA: DATOS Y ACCIONES --}}
        <div class="sidebar-grid">
            
            {{-- Acciones Rápidas --}}
            <div class="quick-actions-card">
                <h4 class="sidebar-title">Creación Rápida</h4>
                <div class="actions-buttons">
                    <a href="{{ route('persona.create') }}" class="btn-quick btn-primary-outline">
                        + Persona
                    </a>
                    <a href="{{ route('ficha_beneficiario.create') }}" class="btn-quick btn-primary-outline">
                        + Ficha
                    </a>
                    <a href="{{ route('actividad.create') }}" class="btn-quick btn-secondary-outline">
                        + Actividad
                    </a>
                </div>
            </div>

            {{-- Gráfico --}}
            <div class="chart-card">
                <h4 class="sidebar-title">Género</h4>
                <div class="chart-container">
                    <canvas id="generoChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- CSS ESTILIZADO Y LIMPIO --}}
<style>
    /* Variables Locales para consistencia */
    :root {
        --kpi-bg: #ffffff;
        --card-radius: 12px;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --soft-blue: #eff6ff;
        --soft-green: #ecfdf5;
        --soft-orange: #fff7ed;
        --soft-purple: #f5f3ff;
    }

    /* Layout General */
    .dashboard-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* 1. Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }
    .page-subtitle {
        color: var(--text-light);
        margin: 0.25rem 0 0;
        font-size: 0.95rem;
    }
    .badge-area {
        background-color: var(--color-primary-light);
        color: var(--color-primary-dark);
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .date-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        border: 1px solid var(--color-border);
        color: var(--text-light);
        font-size: 0.875rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .date-badge .icon { width: 18px; height: 18px; }

    /* 2. KPIs */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .kpi-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--card-radius);
        border: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .kpi-icon svg { width: 24px; height: 24px; }
    
    /* Colores Suaves para Iconos */
    .icon-blue { background: var(--soft-blue); color: #2563eb; }
    .icon-green { background: var(--soft-green); color: #059669; }
    .icon-orange { background: var(--soft-orange); color: #d97706; }
    .icon-purple { background: var(--soft-purple); color: #7c3aed; }

    .kpi-value { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); line-height: 1.1; }
    .kpi-label { font-size: 0.875rem; color: var(--text-light); }

    /* 3. Main Grid (2 Columns) */
    .main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Modules Section */
    .section-heading {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    .module-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: var(--card-radius);
        padding: 1.5rem;
        text-decoration: none;
        transition: border-color 0.2s;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .module-card:hover {
        border-color: var(--color-primary);
        background-color: #fafafa;
    }
    .module-icon-bg {
        width: 40px; 
        height: 40px; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        margin-bottom: 1rem;
    }
    /* Reutilizamos colores de iconos */
    .module-icon-bg.blue { background: var(--soft-blue); color: #2563eb; }
    .module-icon-bg.green { background: var(--soft-green); color: #059669; }
    .module-icon-bg.orange { background: var(--soft-orange); color: #d97706; }
    .module-icon-bg.purple { background: var(--soft-purple); color: #7c3aed; }

    .module-title { font-weight: 600; color: var(--text-dark); font-size: 1rem; margin-bottom: 0.25rem; }
    .module-desc { font-size: 0.8rem; color: var(--text-light); }

    /* Sidebar (Right Column) */
    .sidebar-grid {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .sidebar-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 1rem 0;
    }
    .quick-actions-card, .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--card-radius);
        border: 1px solid var(--color-border);
    }
    .actions-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .btn-quick {
        display: block;
        text-align: center;
        padding: 0.6rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-primary-outline {
        border: 1px solid var(--color-primary);
        color: var(--color-primary);
    }
    .btn-primary-outline:hover { background: var(--color-primary-light); }
    
    .btn-secondary-outline {
        border: 1px solid var(--color-text-light);
        color: var(--color-text-medium);
    }
    .btn-secondary-outline:hover { background: #f3f4f6; }

    .chart-container {
        position: relative;
        height: 200px;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .main-grid {
            grid-template-columns: 1fr;
        }
        .sidebar-grid {
            flex-direction: row;
            flex-wrap: wrap;
        }
        .sidebar-grid > div {
            flex: 1;
            min-width: 250px;
        }
    }
</style>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('generoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartData),
                    backgroundColor: ['#3b82f6', '#ec4899', '#9ca3af'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    }
                },
                cutout: '70%', // Hace el gráfico más fino y elegante
            }
        });
    });
</script>

@endsection