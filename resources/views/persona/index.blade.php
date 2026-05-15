@extends('layouts.main')

@section('title', 'Listado de Personas')

@section('content')
<div class="content-card">

    {{-- 1. ENCABEZADO Y BOTÓN DE ACCIÓN --}}
    <div class="header-container">
        <h3 class="section-title">Registro de Personas Base</h3>
        <a href="{{ route('persona.create') }}" class="btn btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Registrar Nueva Persona
        </a>
    </div>

    {{-- 2. BARRA DE BÚSQUEDA MEJORADA --}}
    <div class="search-container" style="margin-bottom: 2rem; margin-top: 1.5rem;">
        <form action="{{ route('persona.index') }}" method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <div style="position: relative; flex: 1; max-width: 400px;">
                <input type="text" name="busqueda" 
                       value="{{ $busqueda ?? '' }}" 
                       placeholder="Buscar por Nombre, Apellido o C.I..." 
                       class="form-input" 
                       style="padding-left: 2.5rem;">
                <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Buscar
            </button>

            @if(request('busqueda'))
                <a href="{{ route('persona.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- 3. MENSAJES DE ESTADO MEJORADOS --}}
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
    
    {{-- 4. TABLA DE DATOS MEJORADA --}}
    @if ($personas->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>C.I.</th>
                        <th>Fecha Nac.</th>
                        <th>Estado</th>
                        <th class="actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($personas as $persona)
                        <tr>
                            {{-- Número secuencial --}}
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight: 600; color: var(--color-text-dark);">
                                {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                            </td>
                            <td>{{ $persona->carnet_identidad ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    @if ($persona->usuario)
                                        <span class="badge" style="background: linear-gradient(135deg, var(--color-warning) 0%, #f59e0b 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Usuario</span>
                                    @endif
                                    @if ($persona->is_beneficiario)
                                        <span class="badge" style="background: linear-gradient(135deg, var(--color-info) 0%, var(--color-blue) 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Beneficiario</span>
                                    @endif
                                    @if (!$persona->is_beneficiario && !$persona->usuario)
                                        <span class="badge" style="background: var(--color-bg-light); color: var(--color-text-medium); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid var(--color-border);">Libre</span>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    {{-- ============================================================== --}}
                                    {{-- BOTÓN ASIGNAR USUARIO (SOLO ADMIN) --}}
                                    {{-- ============================================================== --}}
                                    @if(auth()->check() && auth()->user()->rol == 'admin' && !$persona->usuario)
                                        <a href="{{ route('usuario.create', ['id_persona' => $persona->id_persona]) }}" 
                                           class="action-link link-success" 
                                           title="Asignar Usuario"
                                           style="color: var(--color-green); background-color: rgba(67, 160, 71, 0.1);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                        </a>
                                    @endif

                                    {{-- ============================================================== --}}
                                    {{-- BOTÓN VINCULAR FICHA (SOLO REGISTRADOR) --}}
                                    {{-- ============================================================== --}}
                                    @if(auth()->check() && auth()->user()->rol == 'registrador' && !$persona->is_beneficiario)
                                        <a href="{{ route('ficha_beneficiario.create', ['id_persona' => $persona->id_persona]) }}" 
                                           class="action-link link-info" 
                                           title="Vincular Ficha de Beneficiario"
                                           style="color: var(--color-info); background-color: rgba(30, 136, 229, 0.1);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                    @endif

                                    {{-- Botón Editar --}}
                                    <a href="{{ route('persona.edit', $persona->id_persona) }}" 
                                       class="action-link link-edit" 
                                       title="Editar Persona">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.232z"></path></svg>
                                    </a>
                                    
                                    {{-- Botón Eliminar --}}
                                    @if (!$persona->is_beneficiario && !$persona->usuario)
                                        <button type="button" 
                                                class="action-link link-delete" 
                                                onclick="openModal('{{ route('persona.destroy', $persona->id_persona) }}', '{{ $persona->nombre }} {{ $persona->apellido_paterno }}')"
                                                title="Eliminar">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @else
                                        <span class="action-link" 
                                              style="color: var(--color-text-light); background-color: var(--color-bg-light); cursor: not-allowed; opacity: 0.6;" 
                                              title="No se puede eliminar, tiene registros asociados">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN MEJORADA --}}
        @if($personas->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;">
            <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                {{ $personas->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif

    @else
        {{-- 5. ESTADO VACÍO MEJORADO --}}
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="empty-text" style="margin-top: 1rem; font-size: 1.125rem; color: var(--color-text-medium);">
                @if(request('busqueda'))
                    No se encontraron resultados para "<strong style="color: var(--color-text-dark);">"{{ request('busqueda') }}"</strong>".
                    <br>
                    <a href="{{ route('persona.index') }}" style="color: var(--color-primary); font-weight: 600; text-decoration: none; margin-top: 0.5rem; display: inline-block;">
                        Ver todos los registros
                    </a>
                @else
                    No se encontraron personas registradas.
                    <br>
                    <a href="{{ route('persona.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        Registrar Primera Persona
                    </a>
                @endif
            </p>
        </div>
    @endif

</div>

{{-- MODAL DE ELIMINACIÓN MEJORADO --}}
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-content">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 4rem; height: 4rem; background: linear-gradient(135deg, var(--color-danger) 0%, var(--color-danger-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg style="width: 2rem; height: 2rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h4 class="modal-title" style="color: var(--color-danger); margin-bottom: 0.5rem;">
                    Confirmar Eliminación
                </h4>
            </div>
            
            <p class="modal-text" style="text-align: center; color: var(--color-text-medium); line-height: 1.6; margin-bottom: 2rem;">
                ¿Está seguro de que desea eliminar a 
                <strong style="color: var(--color-text-dark);" id="personaName"></strong>?
                <br>
                <small style="color: var(--color-text-light); font-size: 0.875rem;">
                    Esta acción no se puede deshacer.
                </small>
            </p>
            
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal()" style="flex: 1;">Cancelar</button>
                <button type="submit" class="btn btn-confirm" style="flex: 1;">Sí, Eliminar</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Estilos adicionales para mejorar la vista específica */
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .link-success {
        color: var(--color-green) !important;
        background-color: rgba(67, 160, 71, 0.1) !important;
    }
    
    .link-success:hover {
        color: white !important;
        background-color: var(--color-green) !important;
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
</style>

<script>
    function openModal(actionUrl, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('personaName');
        
        form.action = actionUrl; 
        nameSpan.textContent = name;
        modal.classList.add('show');
        
        // Prevenir scroll del body cuando el modal está abierto
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('show');
        
        // Restaurar scroll del body
        document.body.style.overflow = 'auto';
    }

    // Cerrar modal al hacer clic fuera del contenido
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target.id === 'deleteModal') {
            closeModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection