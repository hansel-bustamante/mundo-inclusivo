@extends('layouts.main')

@section('title', 'Listado de Beneficiarios')

@section('content')
<div class="content-card">

    {{-- 1. ENCABEZADO Y BOTÓN DE ACCIÓN --}}
    <div class="header-container">
        <h3 class="section-title">Beneficiarios Registrados</h3>
        {{-- En un sistema real, este botón llevaría a crear la Persona y luego el Beneficiario. 
             Aquí lo llevamos directo a la creación de la extensión. --}}
        <a href="{{ route('beneficiario.create') }}" class="btn btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Registrar Beneficiario
        </a>
    </div>

    {{-- 2. MENSAJES DE ESTADO --}}
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    {{-- 3. TABLA DE DATOS --}}
    @if ($beneficiarios->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Persona</th>
                        <th>Nombre Completo</th>
                        <th>C.I.</th>
                        <th>Tipo de Discapacidad</th>
                        <th class="actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beneficiarios as $beneficiario)
                        <tr>
                            <td>{{ $beneficiario->id_persona }}</td>
                            <td>
                                {{ $beneficiario->persona->nombre ?? 'N/A' }} 
                                {{ $beneficiario->persona->apellido_paterno ?? '' }} 
                                {{ $beneficiario->persona->apellido_materno ?? '' }}
                            </td>
                            <td>{{ $beneficiario->persona->carnet_identidad ?? 'N/A' }}</td>
                            <td>{{ $beneficiario->tipo_discapacidad }}</td>
                            
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('beneficiario.edit', $beneficiario->id_persona) }}" 
                                       class="action-link link-edit" title="Editar Beneficiario">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.232z"></path></svg>
                                    </a>
                                    
                                    {{-- Botón Eliminar (Abre Modal) --}}
                                    <button type="button" class="action-link link-delete" title="Eliminar Beneficiario"
                                            onclick="openModal({{ $beneficiario->id_persona }}, '{{ $beneficiario->persona->nombre ?? 'Beneficiario ID ' . $beneficiario->id_persona }}')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 4. PAGINACIÓN --}}
        {{-- Asumiendo que has pasado beneficiarios paginados. Si no, quita esto. --}}
        {{-- <div class="form-actions" style="justify-content: center; margin-top: 1.5rem;">
            {{ $beneficiarios->links('pagination::bootstrap-4') }}
        </div> --}}
    @else
        {{-- 5. ESTADO VACÍO --}}
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="empty-text">No se encontraron beneficiarios registrados.</p>
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
                ¿Está seguro de que desea eliminar la extensión de Beneficiario para **<span id="beneficiarioName" class="font-bold"></span>**? 
                Esto NO elimina la Persona, solo la relación como beneficiario.
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
        const nameSpan = document.getElementById('beneficiarioName');
        
        // CORRECCIÓN CLAVE: Aseguramos que la URL incluya el prefijo 'admin/'
        // Usamos la función 'route' si fuera una vista sin JS (opción 1),
        // pero dado que es JS, construimos la ruta.
        // Si usaste `route('beneficiario.destroy', id)` en la vista sin JS:
        // form.action = "{{ route('beneficiario.destroy', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id); 
        
        // Ya que el modal es de JS, usamos la forma directa (más seguro si no usamos Blade en JS):
        // Reemplaza esta línea:
        // form.action = `/beneficiario/${id}`; 
        
        // Por esta línea que incluye el prefijo:
        form.action = `/admin/beneficiario/${id}`; 
        
        // Actualizar el nombre del beneficiario
        nameSpan.textContent = name;
        
        // Mostrar el modal
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