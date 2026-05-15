@extends('layouts.main')

@section('title', 'Panel de Administración')

@section('content')
<div class="dashboard-wrapper">

    {{-- 1. ENCABEZADO --}}
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Panel de Administración</h1>
            <p class="page-subtitle">
                Resumen general y métricas clave del sistema.
            </p>
        </div>
        <div class="date-badge">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Actualizado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- 2. KPIs (ESTADÍSTICAS TOTALES) --}}
    <div class="kpi-grid">
        {{-- Usuarios --}}
        <div class="kpi-card">
            <div class="kpi-icon icon-green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($totalUsuarios) }}</div>
                <div class="kpi-label">Usuarios Totales</div>
            </div>
        </div>

        {{-- Áreas --}}
        <div class="kpi-card">
            <div class="kpi-icon icon-indigo">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.99 1.99 0 01-2.828 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalAreas }}</div>
                <div class="kpi-label">Áreas de Intervención</div>
            </div>
        </div>

        {{-- Instituciones --}}
        <div class="kpi-card">
            <div class="kpi-icon icon-blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $totalInstituciones }}</div>
                <div class="kpi-label">Instituciones Socias</div>
            </div>
        </div>
    </div>

    {{-- 3. GRID PRINCIPAL (GRÁFICO + MENÚ LATERAL) --}}
    <div class="main-grid">
        
        {{-- COLUMNA IZQUIERDA: GRÁFICO (Más grande) --}}
        <div class="chart-section">
            <div class="chart-header">
                <h3 class="section-title">Distribución de Usuarios</h3>
                <div class="chart-controls">
                    <select id="chartType" class="chart-select">
                        <option value="bar">Barras</option>
                        <option value="pie">Circular</option>
                        <option value="line">Líneas</option>
                    </select>
                    <button id="exportChart" class="btn-icon-only" title="Exportar Gráfico">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </button>
                </div>
            </div>
            <div class="chart-container-large">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        {{-- COLUMNA DERECHA: ACCESOS RÁPIDOS --}}
        <div class="sidebar-grid">
            <h3 class="section-title-small">Gestión del Sistema</h3>
            
            <a href="{{ route('usuario.index') }}" class="module-card">
                <div class="module-icon-bg green">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <span class="module-title">Usuarios</span>
                    <span class="module-desc">Accesos y roles</span>
                </div>
            </a>

            <a href="{{ route('area.index') }}" class="module-card">
                <div class="module-icon-bg indigo">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.99 1.99 0 01-2.828 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <div>
                    <span class="module-title">Áreas</span>
                    <span class="module-desc">Zonas geográficas</span>
                </div>
            </a>

            <a href="{{ route('institucion.index') }}" class="module-card">
                <div class="module-icon-bg blue">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <span class="module-title">Instituciones</span>
                    <span class="module-desc">Socios locales</span>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- ESTILOS CSS --}}
<style>
    /* Variables */
    :root {
        --kpi-bg: #ffffff;
        --card-radius: 12px;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --soft-green: #ecfdf5;
        --soft-indigo: #eef2ff;
        --soft-blue: #eff6ff;
    }

    .dashboard-wrapper { max-width: 1200px; margin: 0 auto; padding: 1rem; }

    /* Header */
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .page-title { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .page-subtitle { color: var(--text-light); margin: 0.25rem 0 0; font-size: 0.95rem; }
    .date-badge { display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.5rem 1rem; border-radius: 20px; border: 1px solid var(--color-border); color: var(--text-light); font-size: 0.875rem; }
    .date-badge .icon { width: 18px; height: 18px; }

    /* KPIs */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
    .kpi-card { background: white; padding: 1.5rem; border-radius: var(--card-radius); border: 1px solid var(--color-border); display: flex; align-items: center; gap: 1.25rem; transition: transform 0.2s; }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    .kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .kpi-icon svg { width: 24px; height: 24px; }
    .kpi-value { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); line-height: 1.1; }
    .kpi-label { font-size: 0.875rem; color: var(--text-light); }

    /* Colors */
    .icon-green { background: var(--soft-green); color: #059669; }
    .icon-indigo { background: var(--soft-indigo); color: #4f46e5; }
    .icon-blue { background: var(--soft-blue); color: #2563eb; }

    /* Main Grid */
    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
    
    /* Chart Section */
    .chart-section { background: white; padding: 1.5rem; border-radius: var(--card-radius); border: 1px solid var(--color-border); }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .section-title { font-size: 1.1rem; font-weight: 600; color: var(--text-dark); margin: 0; }
    .chart-controls { display: flex; gap: 0.5rem; }
    .chart-select { padding: 0.4rem; border: 1px solid var(--color-border); border-radius: 6px; font-size: 0.875rem; color: var(--text-dark); }
    .btn-icon-only { background: none; border: 1px solid var(--color-border); border-radius: 6px; padding: 0.4rem; cursor: pointer; color: var(--text-light); }
    .btn-icon-only:hover { background: #f9fafb; color: var(--text-dark); }
    .btn-icon-only svg { width: 20px; height: 20px; }
    .chart-container-large { position: relative; height: 350px; width: 100%; }

    /* Sidebar Modules */
    .sidebar-grid { display: flex; flex-direction: column; gap: 1rem; }
    .section-title-small { font-size: 0.95rem; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem 0; }
    
    .module-card { background: white; border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 1rem; text-decoration: none; display: flex; align-items: center; gap: 1rem; transition: border-color 0.2s; }
    .module-card:hover { border-color: var(--color-primary); background-color: #fafafa; }
    .module-icon-bg { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .module-icon-bg.green { background: var(--soft-green); color: #059669; }
    .module-icon-bg.indigo { background: var(--soft-indigo); color: #4f46e5; }
    .module-icon-bg.blue { background: var(--soft-blue); color: #2563eb; }
    
    .module-title { display: block; font-weight: 600; color: var(--text-dark); font-size: 1rem; }
    .module-desc { display: block; font-size: 0.8rem; color: var(--text-light); }

    @media (max-width: 900px) {
        .main-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('usersChart').getContext('2d');
        const chartType = document.getElementById('chartType');
        
        // Datos del controlador
        const labels = @json($chartLabels);
        const data = @json($chartData);
        
        const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#6366f1'];
        let chartInstance = null;
        
        function createChart(type = 'bar') {
            if (chartInstance) chartInstance.destroy();
            
            const config = {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Usuarios',
                        data: data,
                        backgroundColor: type === 'line' ? 'rgba(59, 130, 246, 0.1)' : colors,
                        borderColor: type === 'line' ? '#3b82f6' : colors,
                        borderWidth: 1,
                        fill: type === 'line'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: type !== 'bar', position: 'right' }
                    },
                    scales: type === 'bar' || type === 'line' ? {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    } : {}
                }
            };
            
            chartInstance = new Chart(ctx, config);
        }
        
        createChart();
        
        chartType.addEventListener('change', function() {
            createChart(this.value);
        });
        
        document.getElementById('exportChart').addEventListener('click', function() {
            const link = document.createElement('a');
            link.download = `usuarios-${new Date().toISOString().split('T')[0]}.png`;
            link.href = document.getElementById('usersChart').toDataURL('image/png');
            link.click();
        });
    });
</script>
@endsection