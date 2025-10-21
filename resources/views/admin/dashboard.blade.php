@extends('layouts.main')

@section('title', 'Panel de Administración')

@section('content')
    <h2 class="section-heading">Resumen General del Sistema</h2>

    <div class="stats-grid">
        
        <div class="stat-card card-border-gray">
            <div class="card-content-header">
                <p class="card-label">Total de Usuarios</p>
                <svg class="card-icon card-icon-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M7.5 14a6.002 6.002 0 005.965 3.332L12 18h2m-2-4a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <p class="card-value">1,500</p>
            <p class="card-description">Usuarios únicos registrados en el sistema</p>
        </div>
        
        <div class="stat-card card-border-indigo">
            <div class="card-content-header">
                <p class="card-label">Áreas de Intervención</p>
                <svg class="card-icon card-icon-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1m-2-6h.01M20 12l-4-4m-6 4a4 4 0 11-8 0 4 4 0 018 0zm4 4h.01M15.5 15a2.5 2.5 0 01-2.5 2.5h-10A2.5 2.5 0 011 15V7.5a2.5 2.5 0 012.5-2.5h10a2.5 2.5 0 012.5 2.5V15z"></path></svg>
            </div>
            <p class="card-value">12</p>
            <p class="card-description">Áreas de trabajo y municipios activos</p>
        </div>

        <div class="stat-card card-border-green">
            <div class="card-content-header">
                <p class="card-label">Instituciones Socias</p>
                <svg class="card-icon card-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-4-4h.01M3 21h18"></path></svg>
            </div>
            <p class="card-value">45</p>
            <p class="card-description">Convenios y registros de socios activos</p>
        </div>

    </div>

    <div class="welcome-section">
        <h3 class="welcome-title">Bienvenido al Panel de Gestión Central</h3>
        
        <p class="welcome-text">
            Utiliza el menú lateral para gestionar los recursos y la información clave. Como <strong>Administrador</strong>, tu principal tarea es el mantenimiento de los catálogos base:
        </p>

        <div class="welcome-links-grid">
            <a href="{{ route('institucion.index') }}" class="link-card link-card-green-hover">
                <p class="link-title">1. Catálogo de Instituciones</p>
                <p class="link-subtitle">Gestión de socios y colaboradores.</p>
            </a>

            <a href="{{ route('area.index') }}" class="link-card link-card-indigo-hover">
                <p class="link-title">2. Áreas de Intervención</p>
                <p class="link-subtitle">Definición de zonas geográficas.</p>
            </a>

            <a href="{{ route('codigo.index') }}" class="link-card link-card-yellow-hover">
                <p class="link-title">3. Códigos de Actividad</p>
                <p class="link-subtitle">Clasificación de actividades realizadas.</p>
            </a>
        </div>

        <p class="info-footer">
            Recordatorio: El acceso a los datos de beneficiarios, actividades y seguimientos se realiza a través de las secciones específicas del menú. El contenido estadístico de las tarjetas será dinámico en una fase posterior.
        </p>
    </div>
@endsection