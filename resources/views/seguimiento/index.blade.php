@extends('layouts.main')

@section('title', 'Gestión de Seguimientos')

@section('content')
<div class="content-card">
    <h3 class="section-title">Seguimientos Registrados</h3>

    {{-- Botón para registrar nuevo seguimiento --}}
    <a href="{{ route('seguimiento.create') }}" class="btn btn-primary mb-4 inline-flex items-center">
        <svg class="btn-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-3-3v6m-9 5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        Registrar Nuevo Seguimiento
    </a>

    {{-- Mensajes de éxito o error --}}
    @if (session('success'))
        <div class="success-alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error-alert">{{ session('error') }}</div>
    @endif

    {{-- Tabla de seguimientos --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Actividad Asociada</th>
                    <th>Observaciones (Resumen)</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seguimientos as $seguimiento)
                    <tr>
                        <td>{{ $seguimiento->id_seguimiento }}</td>
                        <td>{{ \Carbon\Carbon::parse($seguimiento->fecha)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge badge-info">{{ $seguimiento->tipo }}</span>
                        </td>
                        <td>{{ $seguimiento->actividad->nombre }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($seguimiento->observaciones, 70, '...') }}</td>
                        <td class="text-center actions">
                            {{-- Botón editar --}}
                            <a href="{{ route('seguimiento.edit', $seguimiento->id_seguimiento) }}" 
                               class="btn-action edit inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                           d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                              a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572
                                              L16.732 3.732z" />
                                </svg>
                                Editar
                            </a>

                            {{-- Botón eliminar --}}
                            <button type="button" class="btn-action delete inline-flex items-center"
                                onclick="openDeleteModal('{{ route('seguimiento.destroy', $seguimiento->id_seguimiento) }}')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                           d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                              a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22
                                              M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3" />
                                </svg>
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-600 py-4">
                            No hay registros de seguimiento creados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script para confirmar eliminación segura --}}
<script>
    /**
     * Abre un diálogo de confirmación y, si se confirma, crea y envía
     * un formulario con método DELETE hacia la URL indicada.
     * @param {string} deleteUrl Ruta de eliminación (Laravel route)
     */
    function openDeleteModal(deleteUrl) {
        if (confirm("¿Estás seguro de que deseas eliminar este registro? Esta acción es irreversible.")) {
            const form = document.createElement('form');
            form.action = deleteUrl;
            form.method = 'POST';

            // Spoofing de método DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Token CSRF
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
