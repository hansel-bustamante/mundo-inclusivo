@extends('layouts.main')

@section('title', 'Gestionar Asistencia de Sesión')

@section('content')
<div class="content-card">

    {{-- HEADER MEJORADO CON INFORMACIÓN DE LA SESIÓN --}}
    <div class="header-container">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <h3 class="section-title">Gestión de Asistencia</h3>
                <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                    Sesión N°: {{ $sesion->nro_sesion }}
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <span style="color: var(--color-text-medium); font-weight: 500;">
                    {{ $sesion->actividad->nombre }}
                </span>
                <span style="color: var(--color-text-light); font-size: 0.875rem;">
                    📅 {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }} • 
                    ⏰ {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                </span>
            </div>
        </div>
    </div>

    {{-- PANEL INFORMATIVO MEJORADO --}}
    <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin-bottom: 2rem;">
        <div style="display: flex; align-items: flex-start; gap: 1rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info); flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.5rem;">Tema de la Sesión</h5>
                <p style="color: var(--color-text-medium); margin: 0; font-weight: 500;">
                    {{ $sesion->tema }}
                </p>
            </div>
        </div>
    </div>

    {{-- CONTENEDOR PARA MENSAJES DINÁMICOS --}}
    <div id="flash-message-container" style="margin-bottom: 1.5rem;"></div>
    
    {{-- BARRA DE ACCIONES PRINCIPAL MEJORADA --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding: 1.5rem; background: var(--color-bg-light); border-radius: var(--border-radius); border: 1px solid var(--color-border);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <button id="save-all-button" class="btn btn-primary" disabled style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Sincronizar Todos los Cambios
            </button>
            
            {{-- CONTADOR DE CAMBIOS PENDIENTES --}}
            <div id="pending-changes-counter" style="display: none; background: var(--color-warning); color: var(--color-white); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                <span id="changes-count">0</span> cambios pendientes
            </div>
        </div>
        
        {{-- BOTÓN DE RETORNO --}}
        <a href="{{ route('sesion.por_actividad', $sesion->id_actividad) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Sesiones
        </a>
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    @php
        $totalParticipantes = $participantesActividad->count();
        
        // CORRECCIÓN: Contar solo los que tienen firma=1 explícitamente
        $asistenciasRegistradasCount = $asistenciasRegistradas->filter(function ($item) {
            return $item->firma == 1;
        })->count();

        $porcentajeAsistencia = $totalParticipantes > 0 ? round(($asistenciasRegistradasCount / $totalParticipantes) * 100) : 0;
    @endphp

    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card card-border-green">
            <div class="card-content-header">
                <span class="card-label">Total Participantes</span>
                <svg class="card-icon card-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="card-value">{{ $totalParticipantes }}</div>
            <div class="card-description">Participantes registrados en la actividad</div>
        </div>
        
        <div class="stat-card card-border-indigo">
            <div class="card-content-header">
                <span class="card-label">Asistencia Registrada</span>
                <svg class="card-icon card-icon-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="card-value">{{ $asistenciasRegistradasCount }}</div>
            <div class="card-description">Asistencias confirmadas</div>
        </div>
        
        <div class="stat-card card-border-yellow">
            <div class="card-content-header">
                <span class="card-label">Porcentaje</span>
                <svg class="card-icon card-icon-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div class="card-value">{{ $porcentajeAsistencia }}%</div>
            <div class="card-description">Tasa de asistencia actual</div>
        </div>
    </div>

    {{-- TABLA DE ASISTENCIA MEJORADA --}}
    <div class="table-wrapper">
        <table id="asistencia-table" class="data-table">
            <thead>
                <tr>
                    {{-- CAMBIO 1: TÍTULO DE COLUMNA --}}
                    <th style="width: 100px;">C.I.</th>
                    <th>Participante</th>
                    <th style="width: 100px; text-align: center;">Estado</th>
                    <th style="width: 100px; text-align: center;">Firma</th>
                    <th style="width: 300px;">Observaciones</th>
                    <th style="width: 120px; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantesActividad as $participante)
                    @php
                        // 1. Buscamos el registro
                        $asistencia = $asistenciasRegistradas->where('id_persona', $participante->id_persona)->first();
                        
                        // 2. Lógica de estado visual (Solo "Sí" si firma == 1)
                        $tieneFirma = $asistencia && $asistencia->firma == 1;
                        
                        $textoEstado = $tieneFirma ? 'Sí' : 'No';
                        $claseBadge = $tieneFirma ? 'badge-success' : 'badge-danger';
                        
                        $firmaValue = $tieneFirma ? 1 : 0;
                        $observaciones = $asistencia ? $asistencia->observaciones : '';
                        
                        // Si existe registro en BD (aunque sea firma 0), está "guardado"
                        $isSaved = $asistencia ? 'true' : 'false';
                    @endphp

                    {{-- Mantenemos el data-id-persona con el ID real para la lógica JS --}}
                    <tr data-id-persona="{{ $participante->id_persona }}" data-is-saved="{{ $isSaved }}">
                        {{-- CAMBIO 2: MOSTRAR CARNET EN LUGAR DE ID --}}
                        <td style="font-family: var(--font-monospace); color: var(--color-text-medium); font-weight: 600;">
                            {{ $participante->carnet_identidad }}
                        </td>
                        <td style="font-weight: 600; color: var(--color-text-dark);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg style="width: 1rem; height: 1rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $participante->nombre }} {{ $participante->apellido_paterno }}
                            </div>
                        </td>
                        
                        <td style="text-align: center;">
                            <span class="badge {{ $claseBadge }}" style="padding: 0.375rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                                {{ $textoEstado }}
                            </span>
                        </td>

                        <td style="text-align: center;">
                            <input type="checkbox" 
                                data-pivot="firma" 
                                class="pivot-input form-checkbox" 
                                {{ $tieneFirma ? 'checked' : '' }} 
                                data-initial-value="{{ $firmaValue }}"
                                title="Marcar si el participante firmó">
                        </td>
                        
                        <td>
                            <input type="text" 
                                data-pivot="observaciones" 
                                class="form-input pivot-input" 
                                value="{{ $observaciones }}"
                                placeholder="Observaciones adicionales..."
                                data-initial-value="{{ $observaciones }}"
                                style="width: 100%; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                        </td>

                        <td style="text-align: center;">
                            <div class="action-buttons">
                                <button class="action-link link-edit btn-save-row" disabled title="Guardar cambios de esta fila">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                                <button class="action-link link-delete btn-delete-row" {{ $isSaved === 'false' ? 'disabled' : '' }} title="Eliminar registro de asistencia">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- LEYENDA DE ESTADOS --}}
    <div style="margin-top: 2rem; padding: 1rem; background: var(--color-bg-light); border-radius: var(--border-radius); border: 1px solid var(--color-border);">
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <span style="font-size: 0.875rem; color: var(--color-text-medium); font-weight: 600;">Leyenda:</span>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="badge badge-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Sí</span>
                <span style="font-size: 0.875rem; color: var(--color-text-light);">Asistió a la sesión</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="badge badge-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">No</span>
                <span style="font-size: 0.875rem; color: var(--color-text-light);">No asistió a la sesión</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1rem; height: 1rem; color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span style="font-size: 0.875rem; color: var(--color-text-light);">Cambios pendientes de guardar</span>
            </div>
        </div>
    </div>
</div>

<style>
.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 0.25rem;
    border: 2px solid var(--color-border);
    background: var(--color-white);
    cursor: pointer;
    transition: all 0.2s var(--transition-bounce);
}

.form-checkbox:checked {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
}

.form-checkbox:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.1);
}

.badge-success {
    background: var(--color-primary-light);
    color: var(--color-primary-dark);
}

.badge-danger {
    background: rgba(229, 57, 53, 0.1);
    color: var(--color-danger-dark);
}

.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Estilos para filas con cambios pendientes */
tr.changed {
    background: rgba(253, 216, 53, 0.05) !important;
    border-left: 3px solid var(--color-warning);
}

/* Estilos para botones de acción en estado loading */
.action-link.loading {
    opacity: 0.6;
    cursor: not-allowed;
}

.action-link.loading svg {
    display: none;
}

.action-link.loading::after {
    content: '';
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .data-table {
        min-width: 800px;
    }
    
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('asistencia-table');
    const saveAllButton = document.getElementById('save-all-button');
    const flashContainer = document.getElementById('flash-message-container');
    const pendingChangesCounter = document.getElementById('pending-changes-counter');
    const changesCount = document.getElementById('changes-count');
    const idSesion = {{ $sesion->id_sesion }};
    const apiRoute = '/api/asistencia-sesion';

    // Función para mostrar mensajes flash
    function showFlashMessage(type, message) {
        flashContainer.innerHTML = '';
        const alertDiv = document.createElement('div');
        alertDiv.className = type === 'success' ? 'flash-message flash-success' : 
                            type === 'error' ? 'flash-message flash-danger' : 
                            'flash-message flash-info';
        alertDiv.innerHTML = `
            <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 
                                                                                           type === 'error' ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 
                                                                                           'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}"></path>
            </svg>
            <span>${message}</span>
        `;
        flashContainer.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Actualizar contador de cambios pendientes
    function updatePendingChangesCounter() {
        const changedRows = table.querySelectorAll('tr.changed').length;
        changesCount.textContent = changedRows;
        
        if (changedRows > 0) {
            pendingChangesCounter.style.display = 'flex';
            saveAllButton.disabled = false;
        } else {
            pendingChangesCounter.style.display = 'none';
            saveAllButton.disabled = true;
        }
    }

    // Verificar cambios en una fila
    function checkRowChanges(row) {
        let isChanged = false;
        row.querySelectorAll('.pivot-input').forEach(input => {
            const isCheckbox = input.type === 'checkbox';
            const currentValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
            if (currentValue !== input.dataset.initialValue) {
                isChanged = true;
            }
        });

        const saveButton = row.querySelector('.btn-save-row');
        saveButton.disabled = !isChanged;

        if (isChanged) {
            row.classList.add('changed');
        } else {
            row.classList.remove('changed');
        }

        updatePendingChangesCounter();
    }

    // Detección de cambios
    table.addEventListener('input', function(event) {
        if (event.target.classList.contains('pivot-input')) {
            const row = event.target.closest('tr');
            checkRowChanges(row);
        }
    });

    table.addEventListener('change', function(event) {
        if (event.target.classList.contains('pivot-input')) {
            const row = event.target.closest('tr');
            checkRowChanges(row);
        }
    });

    // Guardar fila individual
    table.addEventListener('click', function(event) {
        if (event.target.closest('.btn-save-row')) {
            const saveButton = event.target.closest('.btn-save-row');
            if (saveButton.disabled) return;

            const row = saveButton.closest('tr');
            const idPersona = row.dataset.idPersona;
            
            saveButton.classList.add('loading');
            saveButton.disabled = true;

            const data = {
                id_sesion: idSesion,
                id_persona: idPersona,
                firma: row.querySelector('input[data-pivot="firma"]').checked ? 1 : 0,
                observaciones: row.querySelector('input[data-pivot="observaciones"]').value,
            };

            fetch(apiRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Error de servidor'); });
                }
                return response.json();
            })
            .then(data => {
                showFlashMessage('success', `Asistencia de ${idPersona} guardada correctamente.`);
                
                // Actualizar estado visual
                row.dataset.isSaved = 'true';
                const badge = row.querySelector('.badge');
                badge.className = 'badge badge-success';
                badge.textContent = 'Sí';
                row.querySelector('.btn-delete-row').disabled = false;
                
                // Actualizar valores iniciales
                row.querySelectorAll('.pivot-input').forEach(input => {
                    const isCheckbox = input.type === 'checkbox';
                    input.dataset.initialValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                });

                row.classList.remove('changed');
                updatePendingChangesCounter();
            })
            .catch(error => {
                showFlashMessage('error', `Error al guardar ${idPersona}: ${error.message}`);
                saveButton.disabled = false;
            })
            .finally(() => {
                saveButton.classList.remove('loading');
            });
        }
    });

    // Eliminar fila individual
    table.addEventListener('click', function(event) {
        if (event.target.closest('.btn-delete-row')) {
            const deleteButton = event.target.closest('.btn-delete-row');
            if (deleteButton.disabled) return;

            const row = deleteButton.closest('tr');
            const idPersona = row.dataset.idPersona;

            if (!confirm(`¿Estás seguro de que deseas eliminar la asistencia de ${idPersona}?`)) {
                return;
            }

            deleteButton.classList.add('loading');
            deleteButton.disabled = true;

            fetch(`${apiRoute}/${idSesion}/${idPersona}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.status === 204) {
                    showFlashMessage('success', `Asistencia de ${idPersona} eliminada correctamente.`);
                    
                    // Resetear estado visual
                    row.dataset.isSaved = 'false';
                    const badge = row.querySelector('.badge');
                    badge.className = 'badge badge-danger';
                    badge.textContent = 'No';
                    
                    row.querySelector('input[data-pivot="firma"]').checked = false;
                    row.querySelector('input[data-pivot="observaciones"]').value = '';
                    
                    row.querySelectorAll('.pivot-input').forEach(input => {
                        const isCheckbox = input.type === 'checkbox';
                        input.dataset.initialValue = isCheckbox ? '0' : '';
                    });

                    row.classList.remove('changed');
                    updatePendingChangesCounter();
                } else if (response.status === 404) {
                    showFlashMessage('error', `El registro de asistencia para ${idPersona} no fue encontrado.`);
                } else {
                    return response.json().then(err => { throw new Error(err.message || 'Error de servidor'); });
                }
            })
            .catch(error => {
                showFlashMessage('error', `Error al eliminar ${idPersona}: ${error.message}`);
                deleteButton.disabled = false;
            })
            .finally(() => {
                deleteButton.classList.remove('loading');
            });
        }
    });

    // Guardado masivo
    saveAllButton.addEventListener('click', function() {
        const changedRows = Array.from(table.querySelectorAll('tr.changed'));
        if (changedRows.length === 0) return;

        saveAllButton.disabled = true;
        const originalContent = saveAllButton.innerHTML;
        saveAllButton.innerHTML = '<span class="loading-spinner" style="margin-right: 0.5rem;"></span> Sincronizando...';

        const promises = changedRows.map(row => {
            const idPersona = row.dataset.idPersona;
            const data = {
                id_sesion: idSesion,
                id_persona: idPersona,
                firma: row.querySelector('input[data-pivot="firma"]').checked ? 1 : 0,
                observaciones: row.querySelector('input[data-pivot="observaciones"]').value,
            };

            return fetch(apiRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en ${idPersona}`);
                }
                return response.json();
            })
            .then(() => {
                // Actualizar estado visual de la fila
                row.dataset.isSaved = 'true';
                const badge = row.querySelector('.badge');
                badge.className = 'badge badge-success';
                badge.textContent = 'Sí';
                row.querySelector('.btn-delete-row').disabled = false;
                
                row.querySelectorAll('.pivot-input').forEach(input => {
                    const isCheckbox = input.type === 'checkbox';
                    input.dataset.initialValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                });

                row.classList.remove('changed');
                return { success: true, idPersona };
            })
            .catch(error => {
                return { success: false, idPersona, error: error.message };
            });
        });

        Promise.all(promises).then(results => {
            const successful = results.filter(r => r.success).length;
            const failed = results.filter(r => !r.success).length;

            if (failed === 0) {
                showFlashMessage('success', `Todas las ${successful} asistencias fueron sincronizadas exitosamente.`);
            } else {
                showFlashMessage('error', `Sincronización completada: ${successful} exitosas, ${failed} con errores.`);
            }

            updatePendingChangesCounter();
            saveAllButton.innerHTML = originalContent;
        });
    });

    // Inicializar contador
    updatePendingChangesCounter();
});
</script>
@endsection