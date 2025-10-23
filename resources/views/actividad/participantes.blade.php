@extends('layouts.main')

@section('title', 'Gestionar Participantes de Actividad')

@section('content')
<div class="content-card">

    <h3 class="section-title">Participantes de Actividad: {{ $actividad->nombre }}</h3>
    <p class="section-subtitle">
        Fecha: {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }} | 
        Lugar: {{ $actividad->lugar }}
    </p>

    {{-- 🟢 CONTENEDOR PARA MENSAJES DINÁMICOS DE JAVASCRIPT --}}
    <div id="flash-message-container"></div>
    
    {{-- 🔴 BLOQUE DE ERRORES DE VALIDACIÓN BLADE --}}
    @if ($errors->any())
        <div class="error-alert">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                           d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 
                              9 0 11-18 0 9 9 0 0118 0z">
                     </path>
                </svg>
                <span>Errores de validación:</span>
            </div>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================================
        🧩 SECCIÓN PRINCIPAL DEL FORMULARIO
        ================================ --}}
    <div class="form-grid" style="grid-template-columns: 1fr 3fr;">
        
        {{-- 🔹 Columna 1: Agregar nueva persona --}}
        <div class="form-column">
            <h4 class="form-column-title">Añadir Persona</h4>

            {{-- 1️⃣ Selector de Persona --}}
            <div class="form-group">
                <label for="new_id_persona" class="form-label">Seleccionar Persona</label>
                <select id="new_id_persona" class="form-input">
                    <option value="">-- Buscar y Seleccionar Persona --</option>
                    @foreach ($personasDisponibles as $p)
                        <option value="{{ $p->id_persona }}" 
                                data-nombre="{{ $p->nombre }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}"
                                data-ci="{{ $p->carnet_identidad ?? 'N/A' }}">
                            {{ $p->apellido_paterno }} {{ $p->apellido_materno }}, 
                            {{ $p->nombre }} (C.I. {{ $p->carnet_identidad ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- 2️⃣ Selector de Institución (nuevo campo obligatorio) --}}
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
            
            {{-- 3️⃣ Checkboxes de atributos --}}
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

            {{-- 4️⃣ Botón para añadir persona --}}
            <button type="button" id="addParticipantBtn" class="btn btn-secondary mt-3" disabled>
                Añadir a la Lista
            </button>
        </div>

        {{-- 🔹 Columna 2: Lista de participantes actuales --}}
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
                        {{-- Filas cargadas desde el servidor --}}
                        @foreach ($participantesActuales as $persona)
                            <tr data-id="{{ $persona->id_persona }}" 
                                data-is-saved="true"
                                data-institucion-id="{{ $persona->pivot->participante->id_institucion ?? '' }}">
                                <td>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</td>
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
                                    <button type="button" 
                                            class="action-link link-delete remove-row-btn" 
                                            title="Eliminar de la lista">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                                             xmlns="http://www.w3.org/2000/svg">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                   d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Botón de sincronización --}}
            <button type="button" id="saveParticipantsBtn" class="btn btn-primary mt-4">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                           d="M5 13l4 4L19 7"></path>
                </svg>
                Sincronizar Cambios con el Servidor
            </button>
        </div>
    </div>

    {{-- 🔹 Botón para volver --}}
    <div class="form-actions mt-4">
        <a href="{{ route('actividad.index') }}" class="btn btn-secondary">
            Volver a Actividades
        </a>
    </div>
</div>

{{-- =========================================================
   🧠 JAVASCRIPT DE GESTIÓN DE PARTICIPANTES
   ========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selector = document.getElementById('new_id_persona');
    const institucionSelector = document.getElementById('new_id_institucion');
    const addButton = document.getElementById('addParticipantBtn');
    const saveButton = document.getElementById('saveParticipantsBtn');
    const tableBody = document.querySelector('#participantsTable tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const actividadId = {{ $actividad->id_actividad }};
    const flashMessageContainer = document.getElementById('flash-message-container');

    const tieneDiscapacidadCheckbox = document.getElementById('new_tiene_discapacidad');
    const esFamiliarCheckbox = document.getElementById('new_es_familiar');
    const firmaCheckbox = document.getElementById('new_firma');

    /** =========================================================
     * 🔔 Mostrar mensaje flash dinámico
     ========================================================== */
    function showFlashMessage(type, message) {
        const className = type === 'success' ? 'flash-success' : 'flash-error';
        const iconPath = type === 'success' 
            ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0...' 
            : 'M10 14l2-2m0 0l2-2m-2 2...';
        
        flashMessageContainer.innerHTML = `
            <div class="flash-message ${className}">
                <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" 
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                           d="${iconPath}"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
        setTimeout(() => flashMessageContainer.innerHTML = '', 5000);
    }

    /** =========================================================
     * 🟢 Activar botón “Añadir” solo si ambos select están completos
     ========================================================== */
    function checkAddButtonStatus() {
        addButton.disabled = !(selector.value && institucionSelector.value);
    }
    selector.addEventListener('change', checkAddButtonStatus);
    institucionSelector.addEventListener('change', checkAddButtonStatus);

    /** =========================================================
     * ❌ Eliminar participante (local o del servidor)
     ========================================================== */
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            const isSaved = row.dataset.isSaved === 'true';

            if (isSaved) {
                if (confirm('¿Seguro que quieres eliminar a este participante?')) {
                    deleteParticipant(id, row);
                }
            } else {
                row.remove();
            }
        }
    });

    /** =========================================================
     * ➕ Añadir nuevo participante a la lista local
     ========================================================== */
    addButton.addEventListener('click', function() {
        const id = selector.value;
        const institucionId = institucionSelector.value;

        if (!institucionId) {
            showFlashMessage('error', 'Debe seleccionar una institución.');
            return;
        }

        if (document.querySelector(`tr[data-id="${id}"]`)) {
            showFlashMessage('error', 'Esta persona ya fue añadida.');
            return;
        }

        const selectedOption = selector.options[selector.selectedIndex];
        const nombre = selectedOption.dataset.nombre;
        const ci = selectedOption.dataset.ci;

        const newRow = tableBody.insertRow();
        newRow.dataset.id = id;
        newRow.dataset.isSaved = 'false';
        newRow.dataset.institucionId = institucionId;

        newRow.innerHTML = `
            <td>${nombre}</td>
            <td>${ci}</td>
            <td><input type="checkbox" data-pivot="tiene_discapacidad" checked></td>
            <td><input type="checkbox" data-pivot="es_familiar"></td>
            <td><input type="checkbox" data-pivot="firma" checked></td>
            <td><span class="badge badge-warning">No</span></td>
            <td>
                <button type="button" class="action-link link-delete remove-row-btn" title="Eliminar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                         xmlns="http://www.w3.org/2000/svg">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                               d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </td>
        `;

        selector.value = "";
        institucionSelector.value = "";
        checkAddButtonStatus();
        tieneDiscapacidadCheckbox.checked = true;
        esFamiliarCheckbox.checked = false;
        firmaCheckbox.checked = true;
    });

    /** =========================================================
     * 🗑️ Eliminar participante del servidor
     ========================================================== */
    function deleteParticipant(personaId, rowElement) {
        fetch(`/admin/participaen/${personaId}/${actividadId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(res => {
            if (res.status === 204) {
                rowElement.remove();
                showFlashMessage('success', 'Participante eliminado exitosamente.');
            } else if (res.status === 404) {
                showFlashMessage('error', 'Participante no encontrado.');
                rowElement.remove();
            } else {
                return res.json().then(data => { throw new Error(data.message || 'Error de servidor'); });
            }
        })
        .catch(err => showFlashMessage('error', 'Mensaje: ' + err.message));
    }

    /** =========================================================
     * 💾 Sincronizar (crear / actualizar) participantes
     ========================================================== */
    saveButton.addEventListener('click', function() {
        const rows = tableBody.querySelectorAll('tr');
        const updates = [];

        rows.forEach(row => {
            const personaId = row.dataset.id;
            const isSaved = row.dataset.isSaved === 'true';
            const institucionId = row.dataset.institucionId;
            const data = {
                id_persona: parseInt(personaId),
                id_actividad: actividadId,
                id_institucion: parseInt(institucionId)
            };
            let needsUpdate = !isSaved;

            row.querySelectorAll('input[type="checkbox"][data-pivot]').forEach(cb => {
                const key = cb.dataset.pivot;
                const value = cb.checked;
                data[key] = value;
                if (isSaved && (value ? '1' : '0') !== cb.dataset.initialValue)
                    needsUpdate = true;
            });

            if (needsUpdate) {
                updates.push({
                    method: isSaved ? 'PUT' : 'POST',
                    url: isSaved ? `/admin/participaen/${personaId}/${actividadId}` : `/admin/participaen`,
                    data,
                    row
                });
            }
        });

        if (updates.length === 0) {
            showFlashMessage('success', 'No hay cambios para guardar.');
            return;
        }

        saveButton.disabled = true;
        saveButton.textContent = 'Guardando...';

        Promise.all(updates.map(item =>
            fetch(item.url, {
                method: item.method,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' },
                body: JSON.stringify(item.data)
            })
            .then(r => {
                if (!r.ok) return r.json().then(err => { throw new Error(err.message || 'Error desconocido.'); });
                return r.json();
            })
            .then(() => {
                item.row.dataset.isSaved = 'true';
                item.row.querySelector('.badge').className = 'badge badge-success';
                item.row.querySelector('.badge').textContent = 'Sí';
                item.row.querySelectorAll('input[data-pivot]').forEach(cb => cb.dataset.initialValue = cb.checked ? '1' : '0');
            })
            .catch(e => ({ error: e.message }))
        ))
        .then(results => {
            const errors = results.filter(r => r.error);
            if (errors.length > 0)
                showFlashMessage('error', `Guardado parcial: ${errors.length} error(es).`);
            else
                showFlashMessage('success', 'Todos los participantes sincronizados correctamente.');
        })
        .finally(() => {
            saveButton.disabled = false;
            saveButton.textContent = 'Sincronizar Cambios con el Servidor';
        });
    });
});
</script>
@endsection
