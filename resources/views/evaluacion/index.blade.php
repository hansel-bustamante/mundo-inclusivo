@extends('layouts.main')

@section('title', 'Listado de Evaluaciones')

@section('content')

    @if (session('success'))
        <div class="flash-message flash-success">
            <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flash-message flash-error">
            <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-card">
        <div class="header-container">
            <h3 class="section-title">Listado de Evaluaciones</h3>
            <a href="{{ route('evaluacion.create') }}" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Registrar Evaluación
            </a>
        </div>

        <div class="table-wrapper table-responsive">
            @if ($evaluaciones->isEmpty())
                <table class="data-table">
                    <tr class="empty-message">
                        <td colspan="6">
                            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            No hay <strong>evaluaciones</strong> registradas.
                        </td>
                    </tr>
                </table>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Actividad Evaluada</th>
                            <th>Fecha Evaluación</th>
                            <th>Resultado</th>
                            <th>Ponderación</th>
                            <th class="actions-cell">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluaciones as $evaluacion)
                            <tr>
                                <td>{{ $evaluacion->id_evaluacion }}</td>
                                <td>
                                    {{ $evaluacion->actividad->nombre ?? 'N/A' }}
                                    <small>({{ $evaluacion->actividad_id }})</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($evaluacion->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($evaluacion->resultado) {
                                            'Cumplido' => 'badge badge-success',
                                            'Parcial' => 'badge badge-warning',
                                            'No cumplido' => 'badge badge-error',
                                            default => 'badge badge-neutral',
                                        };
                                    @endphp
                                    <span class="{{ $badgeClass }}">{{ $evaluacion->resultado }}</span>
                                </td>
                                <td>{{ number_format($evaluacion->ponderacion, 2) ?? 'N/A' }}</td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('evaluacion.edit', $evaluacion->id_evaluacion) }}"
                                           class="action-link link-edit" title="Editar">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <button type="button"
                                                onclick="openDeleteModal('{{ route('evaluacion.destroy', $evaluacion->id_evaluacion) }}')"
                                                class="action-link link-delete" title="Eliminar">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4
                                                      a1 1 0 00-1-1H9a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Modal de eliminación (usa el mismo estilo que otros listados) --}}
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal-content" id="modalContent">
            <h3 class="modal-title">
                <svg class="modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                             1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                             16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Confirmar Eliminación
            </h3>
            <p class="modal-text">
                ¿Está seguro de que desea eliminar esta evaluación? Esta acción <strong>no se puede deshacer</strong>.
            </p>
            <div class="modal-footer">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary btn-cancel">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-confirm">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        function openDeleteModal(actionUrl) {
            deleteForm.action = actionUrl;
            deleteModal.classList.add('show');
            deleteModal.style.display = 'flex';
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('show');
            setTimeout(() => deleteModal.style.display = 'none', 300);
        }

        deleteModal.addEventListener('click', e => {
            if (e.target === deleteModal) closeDeleteModal();
        });
    </script>

@endsection
