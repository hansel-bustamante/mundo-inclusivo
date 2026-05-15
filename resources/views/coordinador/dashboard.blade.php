@extends('layouts.main')

@section('title', 'Dashboard Coordinador')

@section('content')
<div class="dashboard-wrapper">

    {{-- 1. ENCABEZADO --}}
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Panel de Coordinación</h1>
            <p class="page-subtitle">
                Bienvenido, <strong>{{ Auth::user()->nombre_usuario }}</strong>. 
                Gestión y seguimiento institucional.
            </p>
        </div>
        <div class="date-badge">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ now()->format('d M, Y') }}
        </div>
    </div>

    {{-- 2. KPIs (ESTADÍSTICAS) - SOLO INSTITUCIONES Y SEGUIMIENTOS --}}
    <div class="kpi-grid">
        {{-- Card Instituciones --}}
        <div class="kpi-card">
            <div class="kpi-icon icon-purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalInstituciones }}</div>
                <div class="kpi-label">Instituciones</div>
            </div>
        </div>

        {{-- Card Seguimientos --}}
        <div class="kpi-card">
            <div class="kpi-icon icon-indigo">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalSeguimientos }}</div>
                <div class="kpi-label">Seguimientos</div>
            </div>
        </div>
    </div>

    {{-- 3. GRID PRINCIPAL (MÓDULOS + SIDEBAR) --}}
    <div class="main-grid">
        
        {{-- COLUMNA IZQUIERDA: MÓDULOS --}}
        <div class="section-container">
            <h3 class="section-heading">Módulos de Gestión</h3>
            <div class="modules-grid">
                
                {{-- Instituciones --}}
                <a href="{{ route('institucion.index') }}" class="module-card">
                    <div class="module-icon-bg purple">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="module-title">Instituciones</span>
                    <span class="module-desc">Convenios y entidades</span>
                </a>

                {{-- Seguimiento --}}
                <a href="{{ route('seguimiento.index') }}" class="module-card">
                    <div class="module-icon-bg indigo">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <span class="module-title">Seguimiento</span>
                    <span class="module-desc">Monitoreo de avances</span>
                </a>

                {{-- Evaluaciones --}}
                <a href="{{ route('evaluacion.index') }}" class="module-card">
                    <div class="module-icon-bg green">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="module-title">Evaluaciones</span>
                    <span class="module-desc">Resultados e impacto</span>
                </a>

                {{-- Reportes --}}
                <a href="{{ route('reportes.index') }}" class="module-card">
                    <div class="module-icon-bg yellow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="module-title">Reportes</span>
                    <span class="module-desc">Informes analíticos</span>
                </a>
            </div>
        </div>

        {{-- COLUMNA DERECHA: SIDEBAR --}}
        <div class="sidebar-grid">
            
            {{-- Acciones Rápidas --}}
            <div class="quick-actions-card">
                <h4 class="sidebar-title">Gestión Rápida</h4>
                <div class="actions-buttons">
                    <a href="{{ route('institucion.create') }}" class="btn-quick btn-primary-outline">
                        + Nueva Institución
                    </a>
                    {{-- Eliminado Actividad --}}
                </div>
            </div>

            {{-- Gráfico --}}
            <div class="chart-card">
                <h4 class="sidebar-title">Instituciones por Tipo</h4>
                <div class="chart-container">
                    <canvas id="coordinadorChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ESTILOS CSS LIMPIOS --}}
<style>
    /* Variables Locales */
    :root {
        --kpi-bg: #ffffff;
        --card-radius: 12px;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --soft-green: #ecfdf5;
        --soft-purple: #f5f3ff;
        --soft-indigo: #eef2ff;
        --soft-yellow: #fffbeb;
    }

    .dashboard-wrapper { max-width: 1200px; margin: 0 auto; padding: 1rem; }

    /* Header */
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .page-title { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .page-subtitle { color: var(--text-light); margin: 0.25rem 0 0; font-size: 0.95rem; }
    
    .date-badge {
        display: flex; align-items: center; gap: 0.5rem; background: white;
        padding: 0.5rem 1rem; border-radius: 20px; border: 1px solid var(--color-border);
        color: var(--text-light); font-size: 0.875rem; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .date-badge .icon { width: 18px; height: 18px; }

    /* KPIs */
    .kpi-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;
    }
    .kpi-card {
        background: white; padding: 1.5rem; border-radius: var(--card-radius);
        border: 1px solid var(--color-border); display: flex; align-items: center;
        gap: 1.25rem; transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    
    .kpi-icon {
        width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    }
    .kpi-icon svg { width: 24px; height: 24px; }
    
    .kpi-value { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); line-height: 1.1; }
    .kpi-label { font-size: 0.875rem; color: var(--text-light); }

    /* Colores */
    .icon-purple { background: var(--soft-purple); color: #7c3aed; }
    .icon-indigo { background: var(--soft-indigo); color: #4f46e5; }
    .icon-green { background: var(--soft-green); color: #059669; }
    .icon-yellow { background: var(--soft-yellow); color: #d97706; }

    /* Layout Principal */
    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
    .section-heading { font-size: 1.1rem; font-weight: 600; color: var(--text-dark); margin-bottom: 1rem; }

    /* Módulos */
    .modules-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
    .module-card {
        background: white; border: 1px solid var(--color-border); border-radius: var(--card-radius);
        padding: 1.5rem; text-decoration: none; transition: border-color 0.2s;
        display: flex; flex-direction: column; align-items: flex-start;
    }
    .module-card:hover { border-color: var(--color-primary); background-color: #fafafa; }
    
    .module-icon-bg {
        width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;
    }
    .module-icon-bg.purple { background: var(--soft-purple); color: #7c3aed; }
    .module-icon-bg.indigo { background: var(--soft-indigo); color: #4f46e5; }
    .module-icon-bg.green { background: var(--soft-green); color: #059669; }
    .module-icon-bg.yellow { background: var(--soft-yellow); color: #d97706; }

    .module-title { font-weight: 600; color: var(--text-dark); font-size: 1rem; margin-bottom: 0.25rem; }
    .module-desc { font-size: 0.8rem; color: var(--text-light); }

    /* Sidebar */
    .sidebar-grid { display: flex; flex-direction: column; gap: 1.5rem; }
    .sidebar-title { font-size: 0.95rem; font-weight: 600; color: var(--text-dark); margin: 0 0 1rem 0; }
    
    .quick-actions-card, .chart-card {
        background: white; padding: 1.5rem; border-radius: var(--card-radius); border: 1px solid var(--color-border);
    }
    .actions-buttons { display: flex; flex-direction: column; gap: 0.75rem; }
    
    .btn-quick {
        display: block; text-align: center; padding: 0.6rem; border-radius: 8px;
        font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: background 0.2s;
    }
    .btn-primary-outline { border: 1px solid var(--color-primary); color: var(--color-primary); }
    .btn-primary-outline:hover { background: var(--color-primary-light); }

    .chart-container { position: relative; height: 200px; width: 100%; display: flex; justify-content: center; }

    @media (max-width: 900px) {
        .main-grid { grid-template-columns: 1fr; }
        .sidebar-grid { flex-direction: row; flex-wrap: wrap; }
        .sidebar-grid > div { flex: 1; min-width: 250px; }
    }
</style>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('coordinadorChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartData),
                    backgroundColor: ['#7c3aed', '#4f46e5', '#d97706', '#059669'],
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
                        labels: { usePointStyle: true, boxWidth: 8 }
                    }
                },
                cutout: '70%',
            }
        });
    });
</script>

@endsection