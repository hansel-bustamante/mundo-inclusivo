@extends('layouts.main')

@section('title', 'Listado de Actividades')

@section('content')
<div class="content-card">

    {{-- ENCABEZADO MEJORADO --}}
    <div class="header-container">
        <div>
            <h3 class="section-title">Gestión de Actividades</h3>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                Administración de actividades y sesiones del sistema
            </p>
        </div>
        <a href="{{ route('actividad.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Actividad
        </a>
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card card-border-green">
            <div class="card-content-header">
                <span class="card-label">Total Actividades</span>
                <svg class="card-icon card-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div class="card-value">{{ $actividades->count() }}</div>
            <p class="card-description">Registradas en el sistema</p>
        </div>
        
        <div class="stat-card card-border-indigo">
            <div class="card-content-header">
                <span class="card-label">Participantes Totales</span>
                <svg class="card-icon card-icon-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="card-value">{{ $actividades->sum('participantes_count') }}</div>
            <p class="card-description">En todas las actividades</p>
        </div>
    </div>

    {{-- MENSAJES DE SESIÓN --}}
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLA MEJORADA --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre de la Actividad</th>
                    <th>Fecha</th>
                    <th>Lugar</th>
                    <th>Área</th>
                    <th>Código</th>
                    <th class="actions-cell">Participantes</th> 
                    <th class="actions-cell">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($actividades as $actividad)
                    <tr>
                        <td style="font-weight: 600; color: var(--color-primary-dark);">
                            {{ $loop->iteration }}
                        </td>
                        <td style="font-weight: 600; color: var(--color-text-dark);">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                    {{ substr($actividad->nombre, 0, 1) }}
                                </div>
                                {{ $actividad->nombre }}
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 500; color: var(--color-text-medium);">
                                {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>{{ $actividad->lugar }}</td>
                        <td>
                            <span class="badge" style="background: var(--color-bg-light); color: var(--color-text-medium); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; border: 1px solid var(--color-border);">
                                {{ $actividad->areaIntervencion->nombre_area ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span style="font-family: var(--font-monospace); font-size: 0.875rem; color: var(--color-text-medium);">
                                {{ $actividad->codigoActividad->codigo_actividad ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="actions-cell">
                            <span class="badge" style="background: linear-gradient(135deg, var(--color-info) 0%, var(--color-blue) 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                                {{ $actividad->participantes_count }}
                            </span>
                        </td>

                        <td class="actions-cell">
                            <div class="action-buttons">
                                {{-- GESTIONAR PARTICIPANTES --}}
                                <a href="{{ route('actividad.participantes.edit', $actividad->id_actividad) }}"
                                   class="action-link link-info"
                                   title="Gestionar Participantes"
                                   style="color: var(--color-info); background-color: rgba(30, 136, 229, 0.1);">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </a>

                                {{-- GESTIONAR SESIONES --}}
                                <a href="{{ route('sesion.por_actividad', $actividad->id_actividad) }}" 
                                   class="action-link link-success"
                                   title="Gestionar Sesiones"
                                   style="color: var(--color-green); background-color: rgba(67, 160, 71, 0.1);">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </a>

                                {{-- EDITAR ACTIVIDAD --}}
                                <a href="{{ route('actividad.edit', $actividad->id_actividad) }}"
                                   class="action-link link-edit"
                                   title="Editar Actividad">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                {{-- ELIMINAR ACTIVIDAD --}}
                                <button type="button"
                                        onclick="openDeleteModal('{{ route('actividad.destroy', $actividad->id_actividad) }}', '{{ addslashes($actividad->nombre) }}')"
                                        class="action-link link-delete"
                                        title="Eliminar Actividad">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <p class="empty-text" style="margin-top: 1rem; font-size: 1.125rem; color: var(--color-text-medium);">
                                    No hay actividades registradas en el sistema.
                                    <br>
                                    <a href="{{ route('actividad.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                        Crear Primera Actividad
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN PERSONALIZADA --}}
    @if(method_exists($actividades, 'links'))
        @if($actividades->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;">
            <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                {{ $actividades->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    @endif
</div>

{{-- MODAL DE ELIMINACIÓN MEJORADO --}}
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-content">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 4rem; height: 4rem; background: linear-gradient(135deg, var(--color-danger) 0%, var(--color-danger-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h4 class="modal-title" style="color: var(--color-danger); margin-bottom: 0.5rem;">
                Confirmar Eliminación
            </h4>
        </div>
        
        <p class="modal-text" style="text-align: center; color: var(--color-text-medium); line-height: 1.6; margin-bottom: 2rem;">
            ¿Está seguro de que desea eliminar la actividad 
            <strong style="color: var(--color-text-dark);" id="actividadName"></strong>?
            <br>
            <small style="color: var(--color-text-light); font-size: 0.875rem;">
                Esta acción eliminará permanentemente la actividad y todos sus registros asociados.
            </small>
        </p>
        
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()" style="flex: 1;">Cancelar</button>
                <button type="submit" class="btn btn-confirm" style="flex: 1;">Sí, Eliminar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
    }
    
    .link-info {
        color: var(--color-info) !important;
        background-color: rgba(30, 136, 229, 0.1) !important;
    }
    
    .link-info:hover {
        color: white !important;
        background-color: var(--color-info) !important;
    }
    
    .link-success {
        color: var(--color-green) !important;
        background-color: rgba(67, 160, 71, 0.1) !important;
    }
    
    .link-success:hover {
        color: white !important;
        background-color: var(--color-green) !important;
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
        
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
    }
</style>

<script>
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const actividadName = document.getElementById('actividadName');

    function openDeleteModal(actionUrl, nombreActividad) {
        deleteForm.action = actionUrl;
        actividadName.textContent = nombreActividad;
        deleteModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    
    // Cierra el modal si se hace clic en el fondo
    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal.classList.contains('show')) {
            closeDeleteModal();
        }
    });

    // Mejorar la experiencia de hover en las filas
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.05)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endsection