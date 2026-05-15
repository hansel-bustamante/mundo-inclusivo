```html
@extends('layouts.main')

@section('title', 'Listado de Usuarios')

@section('content')
<div class="content-card">

    {{-- ENCABEZADO MEJORADO --}}
    <div class="header-container">
        <div>
            <h3 class="section-title">Gestión de Usuarios</h3>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin-top: 0.5rem;">
                Administración de usuarios del sistema y sus permisos
            </p>
        </div>
        <a href="{{ route('usuario.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Usuario
        </a>
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card card-border-green">
            <div class="card-content-header">
                <span class="card-label">Total Usuarios</span>
                <svg class="card-icon card-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 0h-1m-4 0H8m4 0V3"></path></svg>
            </div>
            <div class="card-value">{{ $usuarios->count() }}</div>
            <p class="card-description">Activos en el sistema</p>
        </div>
        
        <div class="stat-card card-border-indigo">
            <div class="card-content-header">
                <span class="card-label">Distribución de Roles</span>
                <svg class="card-icon card-icon-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="card-value">{{ $usuarios->pluck('rol')->unique()->count() }}</div>
            <p class="card-description">Diferentes tipos de roles</p>
        </div>
    </div>

    {{-- MENSAJES DE ESTADO MEJORADOS --}}
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="error-alert">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="error-title">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    
    {{-- TABLA DE DATOS MEJORADA --}}
    @if ($usuarios->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Persona Asociada</th>
                        <th>Rol</th>
                        <th>Área de Intervención</th>
                        <th>Estado</th>
                        <th class="actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td style="font-weight: 600; color: var(--color-primary-dark);">
                                {{ $loop->iteration }}
                            </td>
                            <td style="font-weight: 600; color: var(--color-text-dark);">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                        {{ substr($usuario->nombre_usuario, 0, 1) }}
                                    </div>
                                    {{ $usuario->nombre_usuario }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div style="font-weight: 600;">
                                        {{ $usuario->persona->nombre ?? 'N/A' }} 
                                        {{ $usuario->persona->apellido_paterno ?? '' }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--color-text-light);">
                                        C.I.: {{ $usuario->persona->carnet_identidad ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeColors = [
                                        'admin' => 'linear-gradient(135deg, var(--color-danger) 0%, var(--color-danger-dark) 100%)',
                                        'registrador' => 'linear-gradient(135deg, var(--color-info) 0%, var(--color-blue) 100%)',
                                        'default' => 'var(--color-bg-light)'
                                    ];
                                    $badgeColor = $badgeColors[$usuario->rol] ?? $badgeColors['default'];
                                    $textColor = in_array($usuario->rol, ['admin', 'registrador']) ? 'white' : 'var(--color-text-medium)';
                                @endphp
                                <span class="badge" style="background: {{ $badgeColor }}; color: {{ $textColor }}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; border: {{ $usuario->rol == 'default' ? '1px solid var(--color-border)' : 'none' }};">
                                    {{ ucfirst($usuario->rol) }}
                                </span>
                            </td>
                            <td>{{ $usuario->areaIntervencion->nombre_area ?? 'Sin Área' }}</td>
                            <td>
                                <span class="badge" style="background: linear-gradient(135deg, var(--color-green) 0%, var(--color-primary-dark) 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                                    Activo
                                </span>
                            </td>
                            
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('usuario.edit', $usuario->id_persona) }}" 
                                       class="action-link link-edit" 
                                       title="Editar Usuario">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    {{-- Botón Resetear Contraseña (Solo Admin) --}}
                                    @if(Auth::user()->rol == 'admin')
                                        <button type="button" 
                                                class="action-link link-warning" 
                                                title="Resetear Contraseña a 123456"
                                                onclick="openResetModal('{{ route('usuario.reset_password', $usuario->id_persona) }}', '{{ addslashes($usuario->nombre_usuario) }}')"
                                                style="color: var(--color-warning); background-color: rgba(253, 216, 53, 0.1);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                        </button>
                                    @endif
                                    
                                    {{-- Botón Eliminar --}}
                                    <button type="button" 
                                            class="action-link link-delete" 
                                            title="Eliminar Usuario"
                                            onclick="openDeleteModal('{{ route('usuario.destroy', $usuario->id_persona) }}', '{{ addslashes($usuario->nombre_usuario) }}')">
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
        @if(method_exists($usuarios, 'links'))
            @if($usuarios->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                    {{ $usuarios->links('vendor.pagination.custom') }}
                </div>
            </div>
            @endif
        @endif

    @else
        {{-- ESTADO VACÍO MEJORADO --}}
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 0h-1m-4 0H8m4 0V3"></path></svg>
            <p class="empty-text" style="margin-top: 1rem; font-size: 1.125rem; color: var(--color-text-medium);">
                No hay usuarios registrados en el sistema.
                <br>
                <a href="{{ route('usuario.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    Registrar Primer Usuario
                </a>
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
            ¿Está seguro de que desea eliminar al usuario 
            <strong style="color: var(--color-text-dark);" id="usuarioName"></strong>?
            <br>
            <small style="color: var(--color-text-light); font-size: 0.875rem;">
                Esto solo eliminará el usuario del sistema, no la persona base.
            </small>
        </p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('deleteModal')" style="flex: 1;">Cancelar</button>
                <button type="submit" class="btn btn-confirm" style="flex: 1;">Sí, Eliminar</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE RESET DE CONTRASEÑA --}}
<div class="modal-backdrop" id="resetModal">
    <div class="modal-content">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 4rem; height: 4rem; background: linear-gradient(135deg, var(--color-warning) 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <h4 class="modal-title" style="color: var(--color-warning); margin-bottom: 0.5rem;">
                Resetear Contraseña
            </h4>
        </div>
        
        <p class="modal-text" style="text-align: center; color: var(--color-text-medium); line-height: 1.6; margin-bottom: 2rem;">
            ¿Está seguro de resetear la contraseña de 
            <strong style="color: var(--color-text-dark);" id="resetUsuarioName"></strong>?
            <br>
            <small style="color: var(--color-text-light); font-size: 0.875rem;">
                La contraseña se cambiará a <strong>123456</strong> y se le pedirá cambiarla al iniciar sesión.
            </small>
        </p>
        
        <form id="resetForm" method="POST">
            @csrf
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('resetModal')" style="flex: 1;">Cancelar</button>
                <button type="submit" class="btn btn-warning" style="flex: 1; background: linear-gradient(135deg, var(--color-warning) 0%, #f59e0b 100%); color: white;">Sí, Resetear</button>
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
    
    .link-warning {
        color: var(--color-warning) !important;
        background-color: rgba(253, 216, 53, 0.1) !important;
    }
    
    .link-warning:hover {
        color: white !important;
        background-color: var(--color-warning) !important;
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
    function openDeleteModal(actionUrl, nombreUsuario) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('usuarioName');
        
        form.action = actionUrl;
        nameSpan.textContent = nombreUsuario;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function openResetModal(actionUrl, nombreUsuario) {
        const modal = document.getElementById('resetModal');
        const form = document.getElementById('resetForm');
        const nameSpan = document.getElementById('resetUsuarioName');
        
        form.action = actionUrl;
        nameSpan.textContent = nombreUsuario;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Cierra modales al hacer clic en el fondo
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Cerrar modales con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal-backdrop.show');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
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
```