@extends('layouts.main')

@section('title', 'Gestión de Fichas de Registro')

@section('content')
<div class="content-card">
    {{-- Encabezado con título y botón de acción principal --}}
    <div class="page-header">
        <h1 class="section-title">Fichas de Registro de Beneficiarios</h1>

        {{-- Botón para crear nueva ficha --}}
        {{-- Se elimina la clase 'mb-4' y se confía en el estilo de 'page-header' --}}
        <a href="{{ route('ficha_registro.create') }}" class="btn btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9 5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Crear Nueva Ficha
        </a>
    </div>

    {{-- Mensajes de éxito (usando flash-message flash-success) --}}
    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    {{-- Mensajes de error (usando error-alert) --}}
    @if (session('error'))
        <div class="error-alert">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Error
            </div>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Tabla de fichas --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Beneficiario</th>
                    <th>C.I.</th>
                    <th>Fecha Registro</th>
                    <th>Área Intervención</th>
                    <th>Retraso</th>
                    <th>Registrado Por</th>
                    {{-- Usamos 'actions-cell' para el encabezado de acciones --}}
                    <th class="actions-cell">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fichas as $ficha)
                    <tr>
                        <td>{{ $ficha->id_ficha }}</td>
                        <td>{{ $ficha->beneficiario->persona->nombre }} {{ $ficha->beneficiario->persona->apellido_paterno }}</td>
                        <td>{{ $ficha->beneficiario->persona->carnet_identidad ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($ficha->fecha_registro)->format('d/m/Y') }}</td>
                        <td>{{ $ficha->areaIntervencion->nombre_area }}</td>
                        <td>
                            {{-- Se reemplaza 'badge-danger' y 'badge-success' por las clases de estilo semántico 'status-tag' --}}
                            <span class="status-tag {{ $ficha->retraso_en_desarrollo ? 'tag-danger' : 'tag-success' }}">
                                {{ $ficha->retraso_en_desarrollo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>{{ $ficha->usuario->persona->nombre ?? 'Sistema' }}</td>
                        {{-- Usamos 'actions-cell' para el cuerpo de acciones --}}
                        <td class="actions-cell">
                            <div class="action-buttons">
                                {{-- Botón editar: se reemplazan las clases por 'action-link link-edit' --}}
                                <a href="{{ route('ficha_registro.edit', $ficha->id_ficha) }}" 
                                   class="action-link link-edit" title="Editar">
                                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                   </svg>
                                </a>
                                
                                {{-- Botón eliminar: se reemplazan las clases por 'action-link link-delete' --}}
                                <button type="button" class="action-link link-delete" title="Eliminar"
                                    onclick="openDeleteModal('{{ route('ficha_registro.destroy', $ficha->id_ficha) }}')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            {{-- Se usa 'empty-state' para el mensaje de tabla vacía --}}
                            <div class="empty-state">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-6h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                No hay Fichas de Registro creadas.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- El script JS se mantiene funcional --}}
<script>
    /**
     * Abre un diálogo de confirmación y, si es aceptado, crea y envía
     * un formulario con el método DELETE para eliminar un recurso.
     * @param {string} deleteUrl La URL de la ruta destroy de Laravel.
     */
    function openDeleteModal(deleteUrl) {
        if (confirm("¿Estás seguro de que deseas eliminar este registro? Esta acción es irreversible.")) {
            
            // 1. Crear un formulario dinámico
            const form = document.createElement('form');
            form.action = deleteUrl;
            form.method = 'POST'; // Usamos POST para el spoofing de método
            
            // 2. Método Spoofing (_method='DELETE')
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // 3. CSRF Token (_token) - CRÍTICO para la seguridad en Laravel
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            // Leemos el token de la metaetiqueta en layouts/main.blade.php
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            tokenInput.value = csrfToken;
            form.appendChild(tokenInput);

            // 4. Adjuntar el formulario al body y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

@endsection