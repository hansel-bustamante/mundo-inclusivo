@extends('layouts.main')

@section('title', 'Centro de Reportes')

@section('content')
<div class="content-card">
    <div class="page-header">
        <h1 class="section-title">Centro de Reportes y Análisis</h1>
        <p class="section-subtitle">Seleccione el módulo del cual desea generar información detallada.</p>
    </div>

    {{-- GRID DE TARJETAS --}}
    <div class="reports-grid">
        @foreach($reportes as $rep)
            
            {{-- Lógica de Iconos según el color definido en el controlador --}}
            @php
                $icon = match($rep['color']) {
                    'blue'   => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h.01M3 21h18', // Instituciones
                    'green'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', // Personas
                    'teal'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', // Beneficiarios
                    'yellow' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', // Actividades
                    'purple' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7', // Seguimiento
                    'pink'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', // Evaluaciones
                    default  => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                };

                // Colores de fondo suaves
                $bgColor = match($rep['color']) {
                    'blue'   => '#eff6ff', // blue-50
                    'green'  => '#f0fdf4', // green-50
                    'teal'   => '#f0fdfa', // teal-50
                    'yellow' => '#fefce8', // yellow-50
                    'purple' => '#faf5ff', // purple-50
                    'pink'   => '#fdf2f8', // pink-50
                    default  => '#f3f4f6'
                };

                // Colores de icono y borde hover
                $mainColor = match($rep['color']) {
                    'blue'   => '#2563eb',
                    'green'  => '#16a34a',
                    'teal'   => '#0d9488',
                    'yellow' => '#ca8a04',
                    'purple' => '#9333ea',
                    'pink'   => '#db2777',
                    default  => '#4b5563'
                };
            @endphp

            <a href="{{ $rep['ruta'] }}" class="report-card" style="--hover-color: {{ $mainColor }};">
                <div class="icon-wrapper" style="background-color: {{ $bgColor }}; color: {{ $mainColor }};">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                    </svg>
                </div>
                <div class="text-wrapper">
                    <h3 class="report-title">{{ $rep['titulo'] }}</h3>
                    <p class="report-desc">{{ $rep['desc'] }}</p>
                </div>
                <div class="arrow-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        @endforeach
    </div>
</div>

<style>
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .report-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 25px;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: var(--hover-color);
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-wrapper svg {
        width: 28px;
        height: 28px;
    }

    .text-wrapper {
        flex: 1;
    }

    .report-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 5px 0;
    }

    .report-desc {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    .arrow-icon {
        color: #d1d5db;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .arrow-icon svg {
        width: 20px;
        height: 20px;
    }

    .report-card:hover .arrow-icon {
        color: var(--hover-color);
        transform: translateX(5px);
    }
</style>
@endsection