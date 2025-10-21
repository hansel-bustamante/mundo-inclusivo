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

    {{-- 2. MENSAJES DE ESTADO --}}
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="flash-message flash-error">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    
    {{-- 3. TABLA DE DATOS --}}
    @if ($personas->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
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
                            <td>{{ $persona->id_persona }}</td>
                            <td>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</td>
                            <td>{{ $persona->carnet_identidad ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td>
                                @if ($persona->beneficiario)
                                    <span class="badge badge-info">Beneficiario</span>
                                @endif
                                @if ($persona->usuario)
                                    <span class="badge badge-warning">Usuario</span>
                                @endif
                                @if (!$persona->beneficiario && !$persona->usuario)
                                    <span class="badge badge-secondary">Libre</span>
                                @endif
                            </td>
                            
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('persona.edit', $persona->id_persona) }}" 
                                       class="action-link link-edit" title="Editar Persona">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.232z"></path></svg>
                                    </a>
                                    
                                    {{-- Botón Eliminar (Abre Modal) --}}
                                    {{-- Solo se puede eliminar si no es Beneficiario ni Usuario --}}
                                    @if (!$persona->beneficiario && !$persona->usuario)
                                        <button type="button" class="action-link link-delete" title="Eliminar Persona"
                                                onclick="openModal({{ $persona->id_persona }}, '{{ $persona->nombre }} {{ $persona->apellido_paterno }}')">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @else
                                        <span class="action-link link-disabled" title="No se puede eliminar, tiene registros asociados">
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

    @else
        {{-- 5. ESTADO VACÍO --}}
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="empty-text">No se encontraron personas registradas.</p>
        </div>
    @endif

</div>

{{-- 6. MODAL DE ELIMINACIÓN --}}
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-content">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <h4 class="modal-title">
                <svg class="modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Confirmar Eliminación
            </h4>
            <p class="modal-text">
                ¿Está seguro de que desea eliminar a **<span id="personaName" class="font-bold"></span>**? Esta acción es irreversible si la persona no tiene registros asociados.
            </p>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-confirm">Sí, Eliminar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('personaName');
        
        // La ruta debe usar el prefijo /admin/persona/{id}
        form.action = `/admin/persona/${id}`; 
        
        nameSpan.textContent = name;
        modal.classList.add('show');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target.id === 'deleteModal') {
            closeModal();
        }
    });
</script>
@endsection