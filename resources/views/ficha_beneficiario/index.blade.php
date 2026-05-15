@extends('layouts.main')

@section('title', 'Gestión de Beneficiarios')

@section('content')
<div class="content-card">

    <div class="header-container">
        <div>
            <h3 class="section-title">Fichas de Beneficiarios</h3>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                Gestión integral de fichas de beneficiarios del sistema
            </p>
        </div>
        <a href="{{ route('ficha_beneficiario.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Ficha
        </a>
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card card-border-green">
            <div class="card-content-header">
                <span class="card-label">Total Fichas</span>
                <svg class="card-icon card-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div class="card-value">{{ $fichas->count() }}</div>
            <p class="card-description">Registradas en el sistema</p>
        </div>
        
        <div class="stat-card card-border-indigo">
            <div class="card-content-header">
                <span class="card-label">Áreas Activas</span>
                <svg class="card-icon card-icon-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div class="card-value">{{ $fichas->pluck('areaIntervencion.nombre_area')->unique()->filter()->count() }}</div>
            <p class="card-description">Diferentes áreas de intervención</p>
        </div>
    </div>

    {{-- BUSCADOR MEJORADO --}}
    <div class="search-container" style="margin-bottom: 2rem;">
        <form action="{{ route('ficha_beneficiario.index') }}" method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <div style="position: relative; flex: 1; max-width: 400px;">
                <input type="text" name="busqueda" value="{{ $busqueda ?? '' }}" 
                       placeholder="Buscar por nombre, C.I. o discapacidad..." 
                       class="form-input" style="padding-left: 2.5rem;">
                <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Buscar
            </button>

            @if(request('busqueda'))
                <a href="{{ route('ficha_beneficiario.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($fichas->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Beneficiario</th>
                        <th>C.I.</th>
                        <th>Discapacidad</th>
                        <th>Fecha Registro</th>
                        <th>Área</th>
                        <th class="actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fichas as $ficha)
                        <tr>
                            <td style="font-weight: 600; color: var(--color-primary-dark);">
                                {{ $loop->iteration }}
                            </td>
                            <td style="font-weight: 600; color: var(--color-text-dark);">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                        {{ substr($ficha->beneficiario?->persona?->nombre ?? 'N', 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">
                                            {{ $ficha->beneficiario?->persona?->nombre ?? 'N/A' }} 
                                            {{ $ficha->beneficiario?->persona?->apellido_paterno ?? '' }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--color-text-light);">
                                            {{ $ficha->beneficiario?->persona?->apellido_materno ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $ficha->beneficiario?->persona?->carnet_identidad ?? 'N/A' }}</td>
                            <td>
                                <span class="badge" style="background: var(--color-bg-light); color: var(--color-text-medium); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; border: 1px solid var(--color-border);">
                                    {{ $ficha->beneficiario?->tipo_discapacidad ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--color-text-medium);">
                                    {{ \Carbon\Carbon::parse($ficha->fecha_registro)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: linear-gradient(135deg, var(--color-info) 0%, var(--color-blue) 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                                    {{ $ficha->areaIntervencion?->nombre_area ?? 'N/A' }}
                                </span>
                            </td>
                            
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('ficha_beneficiario.edit', $ficha->id_ficha) }}" 
                                       class="action-link link-edit"
                                       title="Editar Ficha">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.232z"></path></svg>
                                    </a>
                                    
                                    <button type="button" 
                                            class="action-link link-delete" 
                                            onclick="openModal('{{ route('ficha_beneficiario.destroy', $ficha->id_ficha) }}', '{{ addslashes($ficha->beneficiario?->persona?->nombre ?? 'N/A') }}')"
                                            title="Eliminar Ficha">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN PERSONALIZADA --}}
        @if(method_exists($fichas, 'links'))
            @if($fichas->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                    {{ $fichas->links('vendor.pagination.custom') }}
                </div>
            </div>
            @endif
        @endif
    @else
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="empty-text" style="margin-top: 1rem; font-size: 1.125rem; color: var(--color-text-medium);">
                @if(request('busqueda'))
                    No se encontraron resultados para "<strong style="color: var(--color-text-dark);">{{ request('busqueda') }}</strong>".
                    <br>
                    <a href="{{ route('ficha_beneficiario.index') }}" style="color: var(--color-primary); font-weight: 600; text-decoration: none; margin-top: 0.5rem; display: inline-block;">
                        Ver todas las fichas
                    </a>
                @else
                    No hay fichas de beneficiarios registradas.
                    <br>
                    <a href="{{ route('ficha_beneficiario.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        Crear Primera Ficha
                    </a>
                @endif
            </p>
        </div>
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
            ¿Está seguro de que desea eliminar la ficha de 
            <strong style="color: var(--color-text-dark);" id="beneficiarioName"></strong>?
            <br>
            <small style="color: var(--color-text-light); font-size: 0.875rem;">
                Esta acción eliminará permanentemente el registro de la ficha.
            </small>
        </p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal()" style="flex: 1;">Cancelar</button>
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
    
    .search-container .form-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.1);
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
        
        .search-container form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container form > * {
            width: 100%;
        }
    }
</style>

<script>
    function openModal(url, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('beneficiarioName');
        
        form.action = url;
        nameSpan.textContent = name;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target.id === 'deleteModal') {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

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