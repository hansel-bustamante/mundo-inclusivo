@extends('layouts.main')

@section('title', 'Gestionar Participantes de Actividad')

@section('content')
<div class="content-card">

    <h3 class="section-title">Participantes de Actividad: {{ $actividad->nombre }}</h3>
    <p class="section-subtitle">Fecha: {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }} | Lugar: {{ $actividad->lugar }}</p>

    {{-- CONTENEDOR PARA MENSAJES DINÁMICOS DE JS --}}
    <div id="flash-message-container"></div>
    
    {{-- BLOQUE DE ERRORES (Para errores de validación de formulario Blade si existieran) --}}
    @if ($errors->any())
        <div class="error-alert">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Errores de validación:</span>
            </div>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-grid" style="grid-template-columns: 1fr 3fr;">
        
        {{-- Columna 1: Selector de Persona y Atributos --}}
        <div class="form-column">
            <h4 class="form-column-title">Añadir Persona</h4>

            {{-- 1. Selector de Persona --}}
            <div class="form-group">
                <label for="new_id_persona" class="form-label">Seleccionar Persona</label>
                <select id="new_id_persona" class="form-input">
                    <option value="">-- Buscar y Seleccionar Persona --</option>
                    @foreach ($personasDisponibles as $p)
                        <option value="{{ $p->id_persona }}" 
                                data-nombre="{{ $p->nombre }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}"
                                data-ci="{{ $p->carnet_identidad ?? 'N/A' }}">
                            {{ $p->apellido_paterno }} {{ $p->apellido_materno }}, {{ $p->nombre }} (C.I. {{ $p->carnet_identidad ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- 2. Selector de Institución (¡NUEVO CAMPO REQUERIDO!) --}}
            <div class="form-group">
                <label for="new_id_institucion" class="form-label">Seleccionar Institución (*)</label>
                <select id="new_id_institucion" class="form-input">
                    <option value="">-- Seleccionar Institución --</option>
                    @foreach ($instituciones as $inst)
                        <option value="{{ $inst->id_institucion }}">
                            {{ $inst->nombre_institucion }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="new_tiene_discapacidad" checked class="form-checkbox">
                <label for="new_tiene_discapacidad">Tiene Discapacidad</label>
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="new_es_familiar" class="form-checkbox">
                <label for="new_es_familiar">Es Familiar</label>
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="new_firma" checked class="form-checkbox">
                <label for="new_firma">Registra Firma</label>
            </div>

            <button type="button" id="addParticipantBtn" class="btn btn-secondary mt-3" disabled>
                Añadir a la Lista
            </button>

        </div>

        {{-- Columna 2: Lista de Participantes Actuales --}}
        <div class="form-column">
            <h4 class="form-column-title">Lista de Participantes Registrados</h4>

            <div class="table-wrapper">
                <table class="data-table" id="participantsTable">
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>C.I.</th>
                            <th>Discapacidad</th>
                            <th>Familiar</th>
                            <th>Firma</th>
                            <th>Guardado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- FILAS GENERADAS CON PHP PARA CARGAR LOS EXISTENTES --}}
                        @foreach ($participantesActuales as $persona)
                            {{-- Se asume que el participante SIEMPRE tiene un id_institucion registrado en la tabla participante. --}}
                            <tr data-id="{{ $persona->id_persona }}" 
                                data-is-saved="true"
                                data-institucion-id="{{ $persona->pivot->participante->id_institucion ?? '' }}">
                                <td>
                                    {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                </td>
                                <td>{{ $persona->carnet_identidad ?? 'N/A' }}</td>
                                <td>
                                    <input type="checkbox" 
                                           data-pivot="tiene_discapacidad" 
                                           data-initial-value="{{ $persona->pivot->tiene_discapacidad ? '1' : '0' }}"
                                           {{ $persona->pivot->tiene_discapacidad ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="checkbox" 
                                           data-pivot="es_familiar" 
                                           data-initial-value="{{ $persona->pivot->es_familiar ? '1' : '0' }}"
                                           {{ $persona->pivot->es_familiar ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="checkbox" 
                                           data-pivot="firma" 
                                           data-initial-value="{{ $persona->pivot->firma ? '1' : '0' }}"
                                           {{ $persona->pivot->firma ? 'checked' : '' }}>
                                </td>
                                <td><span class="badge badge-success">Sí</span></td>
                                <td>
                                    <button type="button" class="action-link link-delete remove-row-btn" 
                                            title="Eliminar de la lista">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        {{-- Aquí se insertarán las nuevas filas con JS --}}
                    </tbody>
                </table>
            </div>
            
            <button type="button" id="saveParticipantsBtn" class="btn btn-primary mt-4">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Sincronizar Cambios con el Servidor
            </button>
        </div>
    </div>

    <div class="form-actions mt-4">
        <a href="{{ route('actividad.index') }}" class="btn btn-secondary">
            Volver a Actividades
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selector = document.getElementById('new_id_persona');
    const institucionSelector = document.getElementById('new_id_institucion'); // NUEVO
    const addButton = document.getElementById('addParticipantBtn');
    const saveButton = document.getElementById('saveParticipantsBtn');
    const tableBody = document.querySelector('#participantsTable tbody');
    // ATENCIÓN: Necesitas el CSRF token. Asume que está en un meta tag en tu layout principal.
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    const actividadId = {{ $actividad->id_actividad }};
    const flashMessageContainer = document.getElementById('flash-message-container');

    const tieneDiscapacidadCheckbox = document.getElementById('new_tiene_discapacidad');
    const esFamiliarCheckbox = document.getElementById('new_es_familiar');
    const firmaCheckbox = document.getElementById('new_firma');

    function showFlashMessage(type, message) {
        const className = type === 'success' ? 'flash-success' : 'flash-error';
        const iconPath = type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
        
        flashMessageContainer.innerHTML = `
            <div class="flash-message ${className}">
                <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path></svg>
                <span>${message}</span>
            </div>
        `;
        setTimeout(() => { flashMessageContainer.innerHTML = ''; }, 5000);
    }

    // 1. Habilitar botón de añadir al seleccionar ambos campos (Persona e Institución)
    function checkAddButtonStatus() {
        if (selector.value && institucionSelector.value) {
            addButton.disabled = false;
        } else {
            addButton.disabled = true;
        }
    }
    selector.addEventListener('change', checkAddButtonStatus);
    institucionSelector.addEventListener('change', checkAddButtonStatus);


    // 2. Función para eliminar filas (temporalmente o del servidor)
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            const isSaved = row.dataset.isSaved === 'true';

            if (isSaved) {
                // Si está guardado, hay que eliminarlo del servidor
                if (confirm('¿Seguro que quieres eliminar a este participante de la actividad?')) {
                    deleteParticipant(id, row);
                }
            } else {
                // Si no está guardado, solo se elimina de la lista
                row.remove();
            }
        }
    });

    // 3. Función para añadir nuevo participante a la lista local
    addButton.addEventListener('click', function() {
        const id = selector.value;
        const institucionId = institucionSelector.value; // <-- CAPTURADO
        
        // Validación de InstitutionId (aunque ya está en checkAddButtonStatus)
        if (!institucionId) {
             showFlashMessage('error', '¡Debe seleccionar una institución!');
             return;
        }

        // Validar si ya está en la lista
        if (document.querySelector(`tr[data-id="${id}"]`)) {
            showFlashMessage('error', '¡Esta persona ya ha sido añadida a la lista!');
            return;
        }

        const selectedOption = selector.options[selector.selectedIndex];
        const nombre = selectedOption.dataset.nombre;
        const ci = selectedOption.dataset.ci;
        
        const tieneDiscapacidad = tieneDiscapacidadCheckbox.checked;
        const esFamiliar = esFamiliarCheckbox.checked;
        const firma = firmaCheckbox.checked;

        const newRow = tableBody.insertRow();
        newRow.dataset.id = id;
        newRow.dataset.isSaved = 'false'; 
        newRow.dataset.institucionId = institucionId; // <-- GUARDADO PARA LA SINCRONIZACIÓN

        // Contenido de la fila
        newRow.innerHTML = `
            <td>${nombre}</td>
            <td>${ci}</td>
            <td>
                <input type="checkbox" data-pivot="tiene_discapacidad" data-initial-value="${tieneDiscapacidad ? 1 : 0}" ${tieneDiscapacidad ? 'checked' : ''}>
            </td>
            <td>
                <input type="checkbox" data-pivot="es_familiar" data-initial-value="${esFamiliar ? 1 : 0}" ${esFamiliar ? 'checked' : ''}>
            </td>
            <td>
                <input type="checkbox" data-pivot="firma" data-initial-value="${firma ? 1 : 0}" ${firma ? 'checked' : ''}>
            </td>
            <td><span class="badge badge-warning">No</span></td>
            <td>
                <button type="button" class="action-link link-delete remove-row-btn" title="Eliminar de la lista">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </td>
        `;

        // Resetear selector y campos
        selector.value = "";
        institucionSelector.value = ""; // <-- Resetear Institución
        checkAddButtonStatus(); // Deshabilita el botón
        tieneDiscapacidadCheckbox.checked = true; 
        esFamiliarCheckbox.checked = false;
        firmaCheckbox.checked = true;
    });

    // 4. Función API: Eliminar un participante del servidor
function deleteParticipant(personaId, rowElement) {
    fetch(`/admin/participaen/${personaId}/${actividadId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            // CRÍTICO: Este encabezado fuerza a Laravel a responder con JSON en fallos de middleware/auth
            'X-Requested-With': 'XMLHttpRequest' 
        },
    })
        .then(response => {
            if (response.status === 204) {
                rowElement.remove();
                showFlashMessage('success', 'Participante eliminado exitosamente.');
            } else if (response.status === 404) {
                showFlashMessage('error', 'Error: Participante no encontrado en esta actividad.');
                rowElement.remove(); 
            } else {
                // Aquí es donde se atrapa el error 500. Si el servidor no devuelve JSON válido,
                // la promesa de response.json() fallará, y el catch lo atrapará.
                return response.json().then(data => { throw new Error(data.message || 'Error de servidor'); });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // El mensaje de error que ves en el navegador viene de aquí
            showFlashMessage('error', 'Error al eliminar: ' + error.message);
        });
    }

    // 5. Función API: Sincronizar (Guardar/Actualizar) participantes
    saveButton.addEventListener('click', function() {
        const rows = tableBody.querySelectorAll('tr');
        const updates = [];

        rows.forEach(row => {
            const personaId = row.dataset.id;
            const isSaved = row.dataset.isSaved === 'true';
            const institucionId = row.dataset.institucionId; // <-- LECTURA CRÍTICA DE INSTITUCIÓN

            const checkboxes = row.querySelectorAll('input[type="checkbox"][data-pivot]');
            let data = {
                id_persona: parseInt(personaId),
                id_actividad: actividadId,
                id_institucion: parseInt(institucionId), // <-- CRÍTICO: INCLUIR INSTITUCIÓN
            };
            let needsUpdate = !isSaved; 

            checkboxes.forEach(cb => {
                const key = cb.dataset.pivot;
                const value = cb.checked;
                data[key] = value;

                if (isSaved && (value ? '1' : '0') !== cb.dataset.initialValue) {
                    needsUpdate = true;
                }
            });

            if (needsUpdate) {
                updates.push({
                    method: isSaved ? 'PUT' : 'POST',
                    url: isSaved ? `/admin/participaen/${personaId}/${actividadId}` : `/admin/participaen`,
                    data: data,
                    row: row
                });
            }
        });

        if (updates.length === 0) {
            showFlashMessage('success', 'No hay cambios pendientes para guardar.');
            return;
        }

        saveButton.disabled = true;
        saveButton.textContent = 'Guardando...';

        // Ejecutar todas las promesas de actualización/creación
        Promise.all(updates.map(item => {
            return fetch(item.url, {
                method: item.method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(item.data)
            })
            .then(response => {
                if (!response.ok) {
                    // Si el error es una validación, el JSON contendrá errores
                    return response.json().then(err => { 
                         throw new Error(err.message || (err.errors ? Object.values(err.errors).flat().join(', ') : 'Error desconocido.')); 
                    });
                }
                return response.json();
            })
            .then(data => {
                // Actualizar el estado de la fila si fue exitoso
                item.row.dataset.isSaved = 'true';
                item.row.querySelector('.badge').className = 'badge badge-success';
                item.row.querySelector('.badge').textContent = 'Sí';

                // Actualizar initial-value para futuros cambios
                item.row.querySelectorAll('input[type="checkbox"][data-pivot]').forEach(cb => {
                    cb.dataset.initialValue = cb.checked ? '1' : '0';
                });
                return { status: 'success', data: data };
            })
            .catch(error => {
                console.error('Error en operación:', error);
                return { status: 'error', error: error.message, data: item.data };
            });
        }))
        .then(results => {
            const totalSuccess = results.filter(r => r.status === 'success').length;
            const totalError = results.filter(r => r.status === 'error').length;

            if (totalError > 0) {
                showFlashMessage('error', `Guardado completado. ${totalSuccess} exitoso(s), ${totalError} error(es). Revisa la consola para detalles.`);
            } else {
                showFlashMessage('success', 'Todos los participantes han sido sincronizados exitosamente.');
            }

            saveButton.disabled = false;
            saveButton.textContent = 'Sincronizar Cambios con el Servidor';
        });
    });
});
</script>
@endsection