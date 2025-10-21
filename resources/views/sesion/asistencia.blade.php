@extends('layouts.main')

@section('title', 'Gestionar Asistencia de Sesión')

@section('content')
<div class="content-card">

    <h3 class="section-title">Asistencia a Sesión N°: {{ $sesion->nro_sesion }}</h3>
    <p class="section-subtitle">
        Actividad: {{ $sesion->actividad->nombre }} | 
        Fecha: {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }} | 
        Tema: {{ $sesion->tema }}
    </p>

    {{-- Contenedor para mensajes dinámicos de JS --}}
    <div id="flash-message-container"></div>
    
    <div class="action-bar mb-4">
        {{-- Botón de Sincronización Masiva --}}
        <button id="save-all-button" class="btn btn-success" disabled>
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Sincronizar Cambios con el Servidor
        </button>
        
        <a href="{{ route('sesion.index') }}" class="btn btn-secondary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Sesiones
        </a>
    </div>

    <div class="table-wrapper">
        <table id="asistencia-table" class="data-table">
            <thead>
                <tr>
                    <th>ID Persona</th>
                    <th>Participante</th>
                    <th class="text-center">Asistió</th>
                    <th class="text-center">Firma</th>
                    <th>Observaciones</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantesActividad as $participante)
                    {{-- Si el participante tiene asistencia a esta sesión, cargamos los datos --}}
                    @php
                        $asistencia = $asistenciasRegistradas->where('id_persona', $participante->id_persona)->first();
                        $asistio = $asistencia ? 'Sí' : 'No';
                        $firma = $asistencia ? ($asistencia->firma ? 1 : 0) : 0;
                        $observaciones = $asistencia ? $asistencia->observaciones : '';
                        $isSaved = $asistencia ? 'true' : 'false';
                    @endphp

                    <tr data-id-persona="{{ $participante->id_persona }}" data-is-saved="{{ $isSaved }}">
                        <td>{{ $participante->id_persona }}</td>
                        <td>{{ $participante->nombre }} {{ $participante->apellido_paterno }}</td>
                        
                        {{-- Columna "Asistió" (Badge dinámico) --}}
                        <td class="text-center">
                            <span class="badge badge-{{ $asistencia ? 'success' : 'danger' }}">{{ $asistio }}</span>
                        </td>

                        {{-- Columna Checkbox Firma --}}
                        <td class="text-center">
                            <input type="checkbox" data-pivot="firma" 
                                class="pivot-input" 
                                {{ $firma ? 'checked' : '' }} 
                                data-initial-value="{{ $firma ? 1 : 0 }}">
                        </td>
                        
                        {{-- Columna Observaciones --}}
                        <td>
                            <input type="text" data-pivot="observaciones" 
                                class="form-control pivot-input" 
                                value="{{ $observaciones }}"
                                data-initial-value="{{ $observaciones }}">
                        </td>

                        {{-- Acciones (Botones de Guardar y Eliminar) --}}
                        <td class="text-center">
                            <button class="btn btn-sm btn-info btn-save-row" disabled title="Guardar cambios de esta fila">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete-row" {{ $isSaved === 'false' ? 'disabled' : '' }} title="Eliminar registro de asistencia">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.86 12.14a2 2 0 01-2 1.86H7.86a2 2 0 01-2-1.86L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1H9a1 1 0 00-1 1v3"></path></svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
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
        if (event.target.classList.contains('btn-save-row')) {
            const saveButton = event.target;
            const row = saveButton.closest('tr');
            saveButton.disabled = true;
            saveButton.textContent = 'Guardando...';

            const idPersona = row.dataset.idPersona;
            
            // 1. Obtener los datos actuales de la fila
            const data = {
                id_sesion: idSesion,
                id_persona: idPersona,
                // Si el checkbox está marcado, el registro se considera "asistido"
                firma: row.querySelector('input[data-pivot="firma"]').checked ? 1 : 0, 
                observaciones: row.querySelector('input[data-pivot="observaciones"]').value,
            };

            // 2. Determinar si es una creación (POST) o actualización (PUT)
            // Para la asistencia, usamos POST (updateOrCreate) para simplificar.
            
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
                    // Si el servidor devuelve un 422 (validación) o 500
                    return response.json().then(err => { throw new Error(err.message || 'Error de servidor'); });
                }
                return response.json();
            })
            .then(data => {
                showFlashMessage('success', `Asistencia de ${idPersona} guardada/actualizada.`);
                
                // Actualizar el estado visual de la fila
                row.dataset.isSaved = 'true';
                row.querySelector('.badge').className = 'badge badge-success';
                row.querySelector('.badge').textContent = 'Sí';
                row.querySelector('.btn-delete-row').disabled = false;
                
                // Resetear los valores iniciales para futuros cambios
                row.querySelectorAll('.pivot-input').forEach(input => {
                    const isCheckbox = input.type === 'checkbox';
                    input.dataset.initialValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                });

                saveButton.disabled = true;
                saveButton.textContent = ''; // Limpiar texto de 'Guardando...'

                // Reevaluar el botón de Guardar Todo
                const changedRows = table.querySelectorAll('tr[data-id-persona] .btn-save-row:not([disabled])').length;
                saveAllButton.disabled = changedRows === 0;
            })
            .catch(error => {
                showFlashMessage('error', `Error al guardar ${idPersona}: ${error.message}`);
                console.error(error);
                saveButton.disabled = false;
                saveButton.textContent = '';
            });
        }
    });

    // ----------------------------------------------------
    // 3. Operación de Eliminación de Fila Única
    // ----------------------------------------------------
    table.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-delete-row')) {
            const deleteButton = event.target;
            const row = deleteButton.closest('tr');
            const idPersona = row.dataset.idPersona;

            if (!confirm(`¿Estás seguro de que deseas eliminar la asistencia de ${idPersona} a la sesión ${idSesion}?`)) {
                return;
            }

            deleteButton.disabled = true;
            deleteButton.textContent = 'Eliminando...';
            
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
                    row.querySelector('.badge').className = 'badge badge-danger';
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
                    deleteButton.textContent = '';
                    
                    // El botón de guardar individual seguirá deshabilitado si no hay cambios
                } else if (response.status === 404) {
                    showFlashMessage('error', `El registro de asistencia para ${idPersona} no fue encontrado.`);
                } else {
                    return response.json().then(err => { throw new Error(err.message || 'Error de servidor'); });
                }
            })
            .catch(error => {
                showFlashMessage('error', `Error al eliminar ${idPersona}: ${error.message}`);
                console.error(error);
                deleteButton.disabled = false;
                deleteButton.textContent = '';
            });
        }
    });
    
    // ----------------------------------------------------
    // 4. Operación de Guardado Masivo (SIMPLIFICADA)
    // ----------------------------------------------------
    // NOTA: Para mantener la consistencia con el flujo del proyecto,
    // esta función solo agrupa las llamadas individuales.
    saveAllButton.addEventListener('click', function() {
        saveAllButton.disabled = true;
        saveAllButton.textContent = 'Sincronizando...';

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
                saveButton.textContent = 'Guardando...';
            }
        });

        if (rowsToSave.length === 0) {
            showFlashMessage('info', 'No hay cambios para sincronizar.');
            saveAllButton.disabled = false;
            saveAllButton.textContent = 'Sincronizar Cambios con el Servidor';
            return;
        }

        // Ejecutar todas las promesas de guardado
        Promise.all(rowsToSave.map(item => {
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
                item.row.querySelector('.badge').className = 'badge badge-success';
                item.row.querySelector('.badge').textContent = 'Sí';
                item.row.querySelector('.btn-delete-row').disabled = false;

                // Actualizar initial-value para futuros cambios
                item.row.querySelectorAll('.pivot-input').forEach(input => {
                    const isCheckbox = input.type === 'checkbox';
                    input.dataset.initialValue = isCheckbox ? (input.checked ? '1' : '0') : input.value;
                });
                return { status: 'success', data: data };
            })
            .catch(error => {
                console.error('Error en operación:', error);
                // Re-habilitar el botón individual en caso de error
                item.row.querySelector('.btn-save-row').disabled = false;
                item.row.querySelector('.btn-save-row').textContent = '';
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
            saveAllButton.textContent = 'Sincronizar Cambios con el Servidor';
        });
    });
});
</script>
@endsection