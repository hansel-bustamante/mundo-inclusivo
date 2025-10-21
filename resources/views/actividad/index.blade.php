@extends('layouts.main')

@section('title', 'Listado de Actividades')

@section('content')
<div class="content-card">
    <h3 class="section-title">Listado de Actividades</h3>
    <a href="{{ route('actividad.create') }}" class="btn btn-primary mb-4">
        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Crear Nueva Actividad
    </a>

    {{-- Mensajes de Sesión --}}
    @if (session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

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
                    <th class="text-center">Participantes</th>
                    <th class="text-center">Acciones</th>
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

                        {{-- CONTEO DE PARTICIPANTES (AÑADIDO Y CORREGIDO) --}}
                        <td class="text-center">
                            <span class="badge badge-info">{{ $actividad->participantes_count }}</span>
                        </td>

                        <td class="actions-cell">
                            <div class="action-buttons">

                                {{-- Botón GESTIONAR PARTICIPANTES (CRÍTICO) --}}
                                <a href="{{ route('actividad.participantes.edit', $actividad->id_actividad) }}"
                                   class="action-link link-info" 
                                   title="Gestionar Participantes">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>

                                {{-- Botón EDITAR --}}
                                <a href="{{ route('actividad.edit', $actividad->id_actividad) }}"
                                    class="action-link link-edit"
                                    title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                {{-- Botón ELIMINAR --}}
                                <button type="button"
                                            onclick="openDeleteModal('{{ route('actividad.destroy', $actividad->id_actividad) }}')"
                                            class="action-link link-delete"
                                            title="Eliminar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay actividades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DE ELIMINACIÓN (Asumido) --}}
<div id="deleteModal" style="display: none;">
    {{-- Contenido del modal para confirmar la eliminación --}}
</div>

<script>
    // Asumiendo una función global para el modal de eliminación
    function openDeleteModal(deleteUrl) {
        if (confirm("¿Estás seguro de que deseas eliminar esta actividad?")) {
            // Aquí deberías tener un formulario oculto para DELETE o usar Fetch API
            const form = document.createElement('form');
            form.action = deleteUrl;
            form.method = 'POST';
            
            // Método spoofing para Laravel
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // CSRF Token
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(tokenInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection