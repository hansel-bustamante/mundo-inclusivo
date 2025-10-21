@extends('layouts.main')

@section('title', 'Gestionar Asistencia de Sesión')

@section('content')
<div class="content-card">

    {{-- HEADER CON DETALLES DE LA SESIÓN --}}
    <div class="header-info-panel mb-5 p-4 bg-light-gray rounded-lg shadow-sm">
        <h3 class="section-title text-xl font-bold mb-1">
            Gestión de Asistencia | Sesión N°: {{ $sesion->nro_sesion }}
        </h3>
        <div class="flex flex-wrap space-x-6 text-sm text-gray-600">
            <p><strong>Actividad:</strong> {{ $sesion->actividad->nombre }}</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</p>
            <p><strong>Tema:</strong> {{ $sesion->tema }}</p>
        </div>
    </div>

    {{-- Contenedor para mensajes dinámicos de JS --}}
    <div id="flash-message-container" class="mb-4"></div>
    
    {{-- BARRA DE ACCIONES PRINCIPAL --}}
    <div class="action-bar flex justify-between items-center mb-6">
        
        {{-- Botón de Sincronización Masiva --}}
        <button id="save-all-button" class="btn btn-primary" disabled>
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Sincronizar Cambios
        </button>
        
        {{-- Botón de Retorno --}}
        <a href="{{ route('sesion.index') }}" class="btn btn-secondary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Sesiones
        </a>
    </div>

    {{-- TABLA DE ASISTENCIA --}}
    <div class="table-wrapper shadow-lg rounded-lg overflow-hidden">
        <table id="asistencia-table" class="data-table min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participante</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Firma</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Observaciones</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($participantesActividad as $participante)
                    @php
                        $asistencia = $asistenciasRegistradas->where('id_persona', $participante->id_persona)->first();
                        $asistio = $asistencia ? 'Sí' : 'No';
                        $firma = $asistencia ? ($asistencia->firma ? 1 : 0) : 0;
                        $observaciones = $asistencia ? $asistencia->observaciones : '';
                        $isSaved = $asistencia ? 'true' : 'false';
                    @endphp

                    <tr data-id-persona="{{ $participante->id_persona }}" data-is-saved="{{ $isSaved }}" class="hover:bg-yellow-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $participante->id_persona }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $participante->nombre }} {{ $participante->apellido_paterno }}</td>
                        
                        {{-- Columna "Estado" (Badge dinámico) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="badge badge-{{ $asistencia ? 'success' : 'danger' }} font-semibold text-xs">{{ $asistio }}</span>
                        </td>

                        {{-- Columna Checkbox Firma --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <input type="checkbox" data-pivot="firma" 
                                class="pivot-input form-checkbox h-5 w-5 text-indigo-600 rounded" 
                                {{ $firma ? 'checked' : '' }} 
                                data-initial-value="{{ $firma ? 1 : 0 }}">
                        </td>
                        
                        {{-- Columna Observaciones --}}
                        <td class="px-6 py-4">
                            <input type="text" data-pivot="observaciones" 
                                class="form-control pivot-input block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                value="{{ $observaciones }}"
                                placeholder="Escriba aquí..."
                                data-initial-value="{{ $observaciones }}">
                        </td>

                        {{-- Acciones (Botones de Guardar y Eliminar) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                <button class="btn-action-save btn-save-row" disabled title="Guardar cambios de esta fila">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                                <button class="btn-action-delete btn-delete-row" {{ $isSaved === 'false' ? 'disabled' : '' }} title="Eliminar registro de asistencia">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
</div>

<style>
/* Estilos mejorados para los botones de acción */
.btn-action-save, .btn-action-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-action-save {
    background-color: #10b981;
    color: white;
}

.btn-action-save:hover:not(:disabled) {
    background-color: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-action-save:disabled {
    background-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-action-delete {
    background-color: #ef4444;
    color: white;
}

.btn-action-delete:hover:not(:disabled) {
    background-color: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-action-delete:disabled {
    background-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Spinner para carga */
.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loading-spinner-sm {
    display: inline-block;
    width: 0.75rem;
    height: 0.75rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// El script JavaScript se mantiene **igual** ya que su lógica es correcta, 
// solo se han actualizado los estilos de los elementos HTML que manipula.
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('asistencia-table');
    const saveAllButton = document.getElementById('save-all-button');
    const flashContainer = document.getElementById('flash-message-container');
    const idSesion = {{ $sesion->id_sesion }};
    const apiRoute = '/api/asistencia-sesion'; // Ruta API correcta

    // Función para mostrar mensajes de éxito/error (similar a participantes.blade.php)
    function showFlashMessage(type, message) {
        flashContainer.innerHTML = '';
        const alertDiv = document.createElement('div');
        // Usamos las clases de alerta existentes
        alertDiv.className = type === 'success' ? 'success-alert' : 'error-alert';
        alertDiv.innerHTML = `<span>${message}</span>`;
        flashContainer.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // ----------------------------------------------------
    // 1. Detección de Cambios
    // ----------------------------------------------------
    table.addEventListener('input', function(event) {
        if (event.target.classList.contains('pivot-input')) {
            const row = event.target.closest('tr');
            const saveButton = row.querySelector('.btn-save-row');
            
            let isChanged = false;
            // Verifica si algún campo de la fila ha cambiado
            row.querySelectorAll('.pivot-input').forEach(input => {
                const isCheckbox = input.type === 'checkbox';
                const currentValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                if (currentValue !== input.dataset.initialValue) {
                    isChanged = true;
                }
            });

            saveButton.disabled = !isChanged;
            
            // Habilitar/Deshabilitar el botón de Guardar Todo
            const changedRows = table.querySelectorAll('tr[data-id-persona] .btn-save-row:not([disabled])').length;
            saveAllButton.disabled = changedRows === 0;
        }
    });

    // ----------------------------------------------------
    // 2. Operación de Guardado/Actualización de Fila Única
    // ----------------------------------------------------
    table.addEventListener('click', function(event) {
        if (event.target.closest('.btn-save-row')) {
            const saveButton = event.target.closest('.btn-save-row');
            const row = saveButton.closest('tr');
            saveButton.disabled = true;
            
            // Reemplazamos el ícono con un spinner/texto temporal
            const originalContent = saveButton.innerHTML;
            saveButton.innerHTML = '<span class="loading-spinner"></span>';

            const idPersona = row.dataset.idPersona;
            
            // 1. Obtener los datos actuales de la fila
            const data = {
                id_sesion: idSesion,
                id_persona: idPersona,
                // Si el checkbox está marcado, el registro se considera "asistido"
                firma: row.querySelector('input[data-pivot="firma"]').checked ? 1 : 0, 
                observaciones: row.querySelector('input[data-pivot="observaciones"]').value,
            };

            // 2. Llamada POST (updateOrCreate)
            fetch(apiRoute, {
                method: 'POST', // Usamos POST para updateOrCreate en el controlador
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
                showFlashMessage('success', `Asistencia de ${idPersona} guardada/actualizada.`);
                
                // Actualizar el estado visual de la fila
                row.dataset.isSaved = 'true';
                row.querySelector('.badge').className = 'badge badge-success font-semibold text-xs';
                row.querySelector('.badge').textContent = 'Sí';
                row.querySelector('.btn-delete-row').disabled = false;
                
                // Resetear los valores iniciales para futuros cambios
                row.querySelectorAll('.pivot-input').forEach(input => {
                    const isCheckbox = input.type === 'checkbox';
                    input.dataset.initialValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                });

                saveButton.disabled = true;
                saveButton.innerHTML = originalContent; // Restaurar ícono

                // Reevaluar el botón de Guardar Todo
                const changedRows = table.querySelectorAll('tr[data-id-persona] .btn-save-row:not([disabled])').length;
                saveAllButton.disabled = changedRows === 0;
            })
            .catch(error => {
                showFlashMessage('error', `Error al guardar ${idPersona}: ${error.message}`);
                console.error(error);
                saveButton.disabled = false;
                saveButton.innerHTML = originalContent;
            });
        }
    });

    // ----------------------------------------------------
    // 3. Operación de Eliminación de Fila Única
    // ----------------------------------------------------
    table.addEventListener('click', function(event) {
        if (event.target.closest('.btn-delete-row')) {
            const deleteButton = event.target.closest('.btn-delete-row');
            const row = deleteButton.closest('tr');
            const idPersona = row.dataset.idPersona;

            if (!confirm(`¿Estás seguro de que deseas eliminar la asistencia de ${idPersona} a la sesión ${idSesion}?`)) {
                return;
            }

            deleteButton.disabled = true;
            
            const originalContent = deleteButton.innerHTML;
            deleteButton.innerHTML = '<span class="loading-spinner-sm"></span>'; 

            // Ruta DELETE con clave compuesta
            fetch(`${apiRoute}/${idSesion}/${idPersona}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.status === 204) {
                    showFlashMessage('success', `Asistencia de ${idPersona} eliminada correctamente.`);
                    
                    // Resetear el estado visual de la fila a NO ASISTIÓ
                    row.dataset.isSaved = 'false';
                    row.querySelector('.badge').className = 'badge badge-danger font-semibold text-xs';
                    row.querySelector('.badge').textContent = 'No';
                    
                    // Resetear campos de entrada
                    row.querySelector('input[data-pivot="firma"]').checked = false;
                    row.querySelector('input[data-pivot="observaciones"]').value = '';
                    
                    // Resetear valores iniciales a "No asistió"
                    row.querySelectorAll('.pivot-input').forEach(input => {
                        const isCheckbox = input.type === 'checkbox';
                        input.dataset.initialValue = isCheckbox ? '0' : '';
                    });
                    
                    // Deshabilitar botón de eliminar (ya no hay registro en DB)
                    deleteButton.disabled = true;
                    deleteButton.innerHTML = originalContent;
                    
                    // El botón de guardar individual seguirá deshabilitado si no hay cambios
                } else if (response.status === 404) {
                    showFlashMessage('error', `El registro de asistencia para ${idPersona} no fue encontrado. Posiblemente ya fue eliminado.`);
                } else {
                    return response.json().then(err => { throw new Error(err.message || 'Error de servidor'); });
                }
                
                if (response.status !== 204) {
                     // Solo restaurar el botón si hubo un error (para reintentar)
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = originalContent;
                }
            })
            .catch(error => {
                showFlashMessage('error', `Error al eliminar ${idPersona}: ${error.message}`);
                console.error(error);
                deleteButton.disabled = false;
                deleteButton.innerHTML = originalContent;
            });
        }
    });
    
    // ----------------------------------------------------
    // 4. Operación de Guardado Masivo
    // ----------------------------------------------------
    saveAllButton.addEventListener('click', function() {
        saveAllButton.disabled = true;
        
        const originalContent = saveAllButton.innerHTML;
        saveAllButton.innerHTML = '<svg class="btn-icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m15.356 2H20v-5"></path></svg> Sincronizando...';

        const rowsToSave = [];
        table.querySelectorAll('tr[data-id-persona]').forEach(row => {
            const saveButton = row.querySelector('.btn-save-row');
            if (!saveButton.disabled) {
                // Si el botón individual no está deshabilitado, hay cambios
                const idPersona = row.dataset.idPersona;
                const data = {
                    id_sesion: idSesion,
                    id_persona: idPersona,
                    firma: row.querySelector('input[data-pivot="firma"]').checked ? 1 : 0,
                    observaciones: row.querySelector('input[data-pivot="observaciones"]').value,
                };
                rowsToSave.push({ row, data });
                
                // Deshabilitar botón individual mientras se guarda
                saveButton.disabled = true;
                saveButton.innerHTML = '<span class="loading-spinner"></span>';
            }
        });

        if (rowsToSave.length === 0) {
            showFlashMessage('info', 'No hay cambios para sincronizar.');
            saveAllButton.disabled = true; // Mantenerlo deshabilitado si no hay cambios
            saveAllButton.innerHTML = originalContent;
            return;
        }

        // Ejecutar todas las promesas de guardado
        Promise.all(rowsToSave.map(item => {
            const saveButton = item.row.querySelector('.btn-save-row');
            return fetch(apiRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(item.data)
            })
            .then(response => {
                const isSuccess = response.ok;
                if (!isSuccess) {
                    return response.json().then(err => { throw new Error(err.message || 'Error de servidor'); });
                }
                return response.json();
            })
            .then(data => {
                // Actualizar el estado de la fila si fue exitoso
                item.row.dataset.isSaved = 'true';
                item.row.querySelector('.badge').className = 'badge badge-success font-semibold text-xs';
                item.row.querySelector('.badge').textContent = 'Sí';
                item.row.querySelector('.btn-delete-row').disabled = false;

                // Actualizar initial-value para futuros cambios
                item.row.querySelectorAll('.pivot-input').forEach(input => {
                    const isCheckbox = input.type === 'checkbox';
                    input.dataset.initialValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                });
                
                saveButton.innerHTML = item.row.querySelector('.btn-delete-row').previousElementSibling.innerHTML; // Restaurar ícono
                
                return { status: 'success', data: data };
            })
            .catch(error => {
                console.error('Error en operación:', error);
                // Re-habilitar el botón individual en caso de error
                saveButton.disabled = false;
                saveButton.innerHTML = item.row.querySelector('.btn-delete-row').previousElementSibling.innerHTML;
                return { status: 'error', error: error.message, data: item.data };
            });
        }))
        .then(results => {
            const totalSuccess = results.filter(r => r.status === 'success').length;
            const totalError = results.filter(r => r.status === 'error').length;

            if (totalError > 0) {
                showFlashMessage('error', `Sincronización completada. ${totalSuccess} exitoso(s), ${totalError} error(es). Revisa la consola para detalles.`);
            } else {
                showFlashMessage('success', 'Todas las asistencias han sido sincronizadas exitosamente.');
            }

            saveAllButton.disabled = true;
            saveAllButton.innerHTML = originalContent;
        });
    });
});
</script>
@endsection