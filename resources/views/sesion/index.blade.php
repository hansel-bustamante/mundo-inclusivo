@extends('layouts.main')

@section('title', isset($actividad) ? 'Sesiones de: ' . $actividad->nombre : 'Listado General de Sesiones')

@section('content')
<div class="content-card">
    {{-- HEADER MEJORADO --}}
    <div class="header-container">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                {{-- BOTÓN DE VOLVER --}}
                @if(isset($actividad))
                    <a href="{{ route('actividad.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Actividades
                    </a>
                @else
                    <a href="{{ route(Auth::user()->rol . '.dashboard') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver al Dashboard
                    </a>
                @endif
                
                <h3 class="section-title">
                    @if(isset($actividad))
                        Sesiones de la Actividad
                    @else
                        Listado General de Sesiones
                    @endif
                </h3>
            </div>
            
            @if(isset($actividad))
                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                        Actividad: {{ $actividad->id_actividad }}
                    </span>
                    <span style="color: var(--color-text-medium); font-weight: 500;">
                        {{ $actividad->nombre }}
                    </span>
                    <span style="color: var(--color-text-light); font-size: 0.875rem;">
                        📅 {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }} • 📍 {{ $actividad->lugar }}
                    </span>
                </div>
            @endif
        </div>

        {{-- BOTÓN DE CREAR NUEVA SESIÓN --}}
        @if(isset($actividad))
            <a href="{{ route('sesion.create_por_actividad', $actividad->id_actividad) }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Registrar Nueva Sesión
            </a>
        @else
            <a href="{{ route('sesion.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Crear Nueva Sesión
            </a>
        @endif
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    @if(!isset($actividad) && $sesiones->count() > 0)
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card card-border-green">
            <div class="card-content-header">
                <span class="card-label">Total Sesiones</span>
                <svg class="card-icon card-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div class="card-value">{{ $sesiones->count() }}</div>
            <div class="card-description">Sesiones registradas en el sistema</div>
        </div>
        
        @php
            $sesionesEsteMes = $sesiones->filter(function($sesion) {
                return \Carbon\Carbon::parse($sesion->fecha)->isCurrentMonth();
            })->count();
            
            $proximaSesion = $sesiones->where('fecha', '>=', now()->format('Y-m-d'))->sortBy('fecha')->first();
        @endphp
        
        <div class="stat-card card-border-indigo">
            <div class="card-content-header">
                <span class="card-label">Este Mes</span>
                <svg class="card-icon card-icon-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="card-value">{{ $sesionesEsteMes }}</div>
            <div class="card-description">Sesiones programadas para este mes</div>
        </div>
        
        <div class="stat-card card-border-yellow">
            <div class="card-content-header">
                <span class="card-label">Próxima Sesión</span>
                <svg class="card-icon card-icon-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="card-value">
                @if($proximaSesion)
                    {{ \Carbon\Carbon::parse($proximaSesion->fecha)->format('d/m') }}
                @else
                    -
                @endif
            </div>
            <div class="card-description">
                @if($proximaSesion)
                    {{ $proximaSesion->tema }}
                @else
                    No hay sesiones futuras
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- MENSAJES DE SESIÓN MEJORADOS --}}
    @if (session('success'))
        <div class="flash-message flash-success" style="margin-bottom: 1.5rem;">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- CONTENEDOR DE TABLA MEJORADO --}}
    @if($sesiones->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Nro.</th>
                        <th>Tema</th>
                        <th style="width: 120px;">Fecha</th>
                        <th style="width: 120px;">Horario</th>
                        <th>Actividad</th>
                        <th style="width: 140px;" class="actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sesiones as $sesion)
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: var(--color-primary-dark);">
                                {{ $sesion->nro_sesion }}
                            </td>
                            <td style="font-weight: 600; color: var(--color-text-dark);">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--color-text-light); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    {{ Str::limit($sesion->tema, 60) }}
                                </div>
                            </td>
                            <td style="color: var(--color-text-medium);">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td style="color: var(--color-text-medium);">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1rem; height: 1rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                @if(isset($actividad))
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500;">
                                        <svg style="width: 1rem; height: 1rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        {{ $actividad->nombre }}
                                    </span>
                                @else
                                    <a href="{{ route('sesion.por_actividad', $sesion->actividad->id_actividad) }}" 
                                       class="text-link" 
                                       title="Ver todas las sesiones de esta actividad"
                                       style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500;">
                                        <svg style="width: 1rem; height: 1rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        {{ $sesion->actividad->nombre ?? 'N/A' }}
                                    </a>
                                @endif
                            </td>
                            <td class="actions-cell">
                                {{-- Lógica de Autorización --}}
                                @if(Auth::user()->rol === 'admin' || (isset($sesion->actividad->area_intervencion_id) && Auth::user()->area_intervencion_id === $sesion->actividad->area_intervencion_id))
                                    <div class="action-buttons">
                                        {{-- 1. Botón Asistencia --}}
                                        {{-- 1. Botón Asistencia (CORREGIDO EL NOMBRE DE LA RUTA) --}}
                                        <a href="{{ route('sesion.asistencia.edit', $sesion->id_sesion) }}"
                                        class="action-link link-info"
                                        title="Gestionar Asistencia">
                                            {{-- Icono de lista de chequeo --}}
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </a>

                                        {{-- 2. Botón Editar --}}
                                        <a href="{{ route('sesion.edit', $sesion->id_sesion) }}"
                                           class="action-link link-edit"
                                           title="Editar Sesión">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        {{-- 3. Botón Eliminar --}}
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('sesion.destroy', $sesion->id_sesion) }}', '{{ $sesion->nro_sesion }}', '{{ $sesion->tema }}')"
                                            class="action-link link-delete"
                                            title="Eliminar Sesión">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="badge" style="background: var(--color-bg-light); color: var(--color-text-light); padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem;">
                                        Sin permisos
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN MEJORADA --}}
        @if($sesiones->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border);">
            <div style="color: var(--color-text-medium); font-size: 0.875rem;">
                Mostrando {{ $sesiones->firstItem() }} - {{ $sesiones->lastItem() }} de {{ $sesiones->total() }} sesiones
            </div>
            <div>
                {{ $sesiones->links() }}
            </div>
        </div>
        @endif
    @else
        {{-- ESTADO VACÍO MEJORADO --}}
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <h4 style="font-family: var(--font-heading); color: var(--color-text-medium); margin-bottom: 0.5rem;">
                @if(isset($actividad))
                    No hay sesiones registradas para esta actividad
                @else
                    No hay sesiones registradas
                @endif
            </h4>
            <p style="color: var(--color-text-light); margin: 0 0 1.5rem 0;">
                @if(isset($actividad))
                    Comienza registrando la primera sesión de esta actividad.
                @else
                    Comienza creando la primera sesión del sistema.
                @endif
            </p>
            @if(isset($actividad))
                <a href="{{ route('sesion.create_por_actividad', $actividad->id_actividad) }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Registrar Primera Sesión
                </a>
            @else
                <a href="{{ route('sesion.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Crear Primera Sesión
                </a>
            @endif
        </div>
    @endif
</div>

{{-- MODAL DE ELIMINACIÓN MEJORADO --}}
<div id="deleteModal" class="modal-backdrop" style="display: none;">
    <div class="modal-content">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <svg style="width: 3rem; height: 3rem; color: var(--color-danger); margin: 0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="modal-title">Confirmar Eliminación</h3>
        </div>
        
        <p style="text-align: center; color: var(--color-text-medium); margin-bottom: 2rem;">
            ¿Está seguro de que desea eliminar la sesión <strong id="sesionInfo"></strong>? 
            Esta acción es irreversible y eliminará todos los datos asociados.
        </p>
        
        <div class="form-actions" style="justify-content: center; gap: 1rem;">
            <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-confirm" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Sí, Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal(deleteUrl, nroSesion, tema) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const sesionInfo = document.getElementById('sesionInfo');
    
    // Actualizar información de la sesión
    sesionInfo.textContent = `N° ${nroSesion} - ${tema}`;
    
    // Configurar el formulario
    form.action = deleteUrl;
    
    // Mostrar modal
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Cerrar modal al hacer clic fuera del contenido
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<style>
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--color-overlay);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s var(--transition-bounce);
    padding: 1rem;
}

.modal-backdrop.show {
    opacity: 1;
    pointer-events: auto;
}

.modal-content {
    background-color: var(--color-bg-card);
    border-radius: var(--border-radius-xl);
    padding: 2.5rem;
    max-width: 32rem;
    width: 100%;
    transform: translateY(-50px) scale(0.95);
    opacity: 0;
    transition: all 0.3s var(--transition-bounce);
    box-shadow: var(--shadow-hover);
    position: relative;
    overflow: hidden;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--color-danger) 0%, var(--color-danger-dark) 100%);
}

.modal-backdrop.show .modal-content {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.text-link {
    color: var(--color-info);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s var(--transition-bounce);
}

.text-link:hover {
    color: var(--color-info);
    text-decoration: underline;
    transform: translateX(2px);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .data-table {
        min-width: 800px;
    }
    
    .modal-content {
        padding: 2rem 1.5rem;
    }
}
</style>
@endsection