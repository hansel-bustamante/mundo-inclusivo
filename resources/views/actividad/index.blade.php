@extends('layouts.main')

@section('title', 'Listado de Actividades')

@section('content')
<div class="content-card">
    {{-- TÍTULO Y BOTÓN DE ACCIÓN --}}
    <div class="page-header">
        <h1 class="section-title">Listado de Actividades</h1>
        <a href="{{ route('actividad.create') }}" class="btn btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Crear Nueva Actividad
        </a>
    </div>

    {{-- MENSAJES DE SESIÓN --}}
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- CRÍTICO: El contenedor que permite el scroll horizontal --}}
    <div class="table-wrapper"> 
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
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
                        <td>{{ $actividad->id_actividad }}</td>
                        <td>{{ $actividad->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $actividad->lugar }}</td>
                        <td>{{ $actividad->areaIntervencion->nombre_area ?? 'N/A' }}</td>
                        <td>{{ $actividad->codigoActividad->codigo_actividad ?? 'N/A' }}</td>

                        <td class="actions-cell">
                            <span class="status-tag tag-info">{{ $actividad->participantes_count }}</span>
                        </td>

                        <td class="actions-cell">
                            <div class="action-buttons">
                                <a href="{{ route('actividad.participantes.edit', $actividad->id_actividad) }}"
                                   class="action-link link-info" title="Gestionar Participantes">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2a3 3 0 00-5.356-1.857M9 20V8m6 12V6m-3 6v4m-6-4h.01M5 16h.01M19 16h.01M9 8h.01M5 12h.01M19 12h.01M9 16h.01"></path></svg>
                                </a>

                                <a href="{{ route('actividad.edit', $actividad->id_actividad) }}"
                                   class="action-link link-edit" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <button type="button"
                                        onclick="openDeleteModal('{{ route('actividad.destroy', $actividad->id_actividad) }}')"
                                        class="action-link link-delete" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                No hay actividades registradas.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function openDeleteModal(deleteUrl) {
        if (confirm("¿Estás seguro de que deseas eliminar esta actividad? Esta acción es irreversible.")) {
            const form = document.createElement('form');
            form.action = deleteUrl;
            form.method = 'POST';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            tokenInput.value = csrfToken;
            form.appendChild(tokenInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection