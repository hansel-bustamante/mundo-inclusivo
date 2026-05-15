@extends('layouts.main')

@section('title', 'Gestionar Participantes')

@section('content')
<div class="content-card">

    {{-- HEADER MEJORADO --}}
    <div class="header-container">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('actividad.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Actividades
                </a>
                <h3 class="section-title">Gestionar Participantes</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                    Actividad: {{ $actividad->id_actividad }}
                </span>
                <span style="color: var(--color-text-medium); font-weight: 500;">
                    {{ $actividad->nombre }}
                </span>
            </div>
        </div>
    </div>

    {{-- INFORMACIÓN DE LA ACTIVIDAD --}}
    <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.25rem;">Detalles de la Actividad</h5>
                <p style="color: var(--color-text-medium); margin: 0; font-weight: 500;">
                    📅 {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }} • 
                    📍 {{ $actividad->lugar }} • 
                    🏷️ {{ $actividad->areaIntervencion->nombre_area ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <div id="flash-message-container"></div>
    
    {{-- BLOQUE 1: AÑADIR PARTICIPANTE MEJORADO --}}
    <div class="content-card" style="margin-bottom: 2.5rem;">
        <div class="header-container" style="border-bottom: 2px solid var(--color-primary); margin-bottom: 1.5rem;">
            <h4 class="section-title" style="font-size: 1.375rem; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Añadir Nuevo Participante
            </h4>
        </div>

        <div class="form-grid">
            
            {{-- Buscar Persona --}}
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label for="new_id_persona" class="form-label" style="margin: 0;">
                        <span style="color: var(--color-danger);">*</span> Buscar Persona
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer; color: var(--color-text-medium); font-weight: 500;">
                        <input type="checkbox" id="is_visitor_search" style="margin: 0;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Modo Visitante
                    </label>
                </div>
                <select id="new_id_persona" class="form-input form-select">
                    <option value="">-- Buscar por Nombre o C.I. --</option>
                </select>
            </div>

            {{-- Institución --}}
            <div class="form-group">
                <label for="institucion-select" class="form-label">Institución</label>
                <select id="institucion-select" class="form-input form-select">
                    <option value="">-- Sin Institución / Seleccionar --</option> 
                    @foreach ($instituciones as $inst)
                        <option value="{{ $inst->id_institucion }}">{{ $inst->nombre_institucion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Opciones y Botón --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border);">
            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="new_tiene_discapacidad" checked class="form-checkbox"> 
                    <label for="new_tiene_discapacidad" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; cursor: pointer;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                        Con Discapacidad
                    </label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="new_es_familiar" class="form-checkbox"> 
                    <label for="new_es_familiar" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; cursor: pointer;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-purple);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Es Familiar
                    </label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="new_firma" checked class="form-checkbox"> 
                    <label for="new_firma" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 500; cursor: pointer;">
                        <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-yellow);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Registra Firma
                    </label>
                </div>
            </div>

            <button type="button" id="addParticipantBtn" class="btn btn-primary" disabled style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Añadir a la Lista
            </button>
        </div>
    </div>

    {{-- BLOQUE 2: LISTA DE PARTICIPANTES MEJORADA --}}
    <div class="content-card">
        <div class="header-container" style="border-bottom: 2px solid var(--color-info); margin-bottom: 1.5rem;">
            <h4 class="section-title" style="font-size: 1.375rem; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Lista de Participantes
                <span class="badge" style="background: var(--color-info); color: var(--color-white); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem; font-weight: 600;">
                    {{ $participantesActuales->count() }}
                </span>
            </h4>
        </div>

        @if($participantesActuales->count() > 0)
            <div class="table-wrapper">
                <table class="data-table" id="participantsTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>C.I.</th>
                            <th>Institución</th>
                            <th style="width: 120px;">Opciones</th>
                            <th style="width: 120px;">Estado</th>
                            <th style="width: 80px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participantesActuales as $p)
                            <tr data-id="{{ $p->id_persona }}" data-is-saved="true" data-institucion-id="{{ $p->afiliacion?->id_institucion ?? '' }}">
                                <td style="font-weight: 600; color: var(--color-text-dark);">
                                    {{ $p->nombre }} {{ $p->apellido_paterno }}
                                </td>
                                <td style="font-family: var(--font-monospace); color: var(--color-text-medium);">
                                    {{ $p->carnet_identidad }}
                                </td>
                                <td>
                                    @if($p->afiliacion?->institucion?->nombre_institucion)
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <svg style="width: 1rem; height: 1rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $p->afiliacion->institucion->nombre_institucion }}
                                        </span>
                                    @else
                                        <span style="color: var(--color-text-light); font-style: italic;">Sin Institución</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        @if($p->pivot->tiene_discapacidad)
                                            <span class="badge-option" title="Con Discapacidad" style="background: rgba(67, 160, 71, 0.1); color: var(--color-primary-dark); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">
                                                ♿
                                            </span>
                                        @endif
                                        @if($p->pivot->es_familiar)
                                            <span class="badge-option" title="Es Familiar" style="background: rgba(142, 36, 170, 0.1); color: var(--color-purple); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">
                                                👪
                                            </span>
                                        @endif
                                        @if($p->pivot->firma)
                                            <span class="badge-option" title="Registra Firma" style="background: rgba(253, 216, 53, 0.1); color: var(--color-yellow); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">
                                                ✍️
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark); padding: 0.375rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                                        Guardado
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="action-link link-delete remove-row-btn" title="Quitar participante">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.125rem; height: 1.125rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                <h4 style="font-family: var(--font-heading); color: var(--color-text-medium); margin-bottom: 0.5rem;">No hay participantes registrados</h4>
                <p style="color: var(--color-text-light); margin: 0;">Comienza añadiendo participantes usando el formulario superior.</p>
            </div>
        @endif
        
        <div class="form-actions" style="margin-top: 2rem;">
            <button type="button" id="saveParticipantsBtn" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Guardar Cambios
            </button>
        </div>
    </div>
</div>

<style>
    .form-checkbox {
        width: 1.125rem;
        height: 1.125rem;
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
    
    .badge-option {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.75rem;
        height: 1.75rem;
        font-weight: 600;
    }
    
    .flash-success {
        background-color: #f0fdf4;
        border-color: var(--color-primary);
        color: var(--color-primary-dark);
    }
    
    .flash-danger {
        background-color: #fef2f2;
        border-color: var(--color-danger);
        color: var(--color-danger-dark);
    }
    
    .flash-info {
        background-color: #f0f9ff;
        border-color: var(--color-info);
        color: var(--color-info);
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        .data-table {
            min-width: 800px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const actividadId = {{ $actividad->id_actividad }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Mensajes Flash Mejorados
    function showFlashMessage(message, type) {
        const container = document.getElementById('flash-message-container');
        const icon = type === 'success' ? 
            '<svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
            type === 'danger' ?
            '<svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
            '<svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        
        container.innerHTML = `
            <div class="flash-message flash-${type}" style="margin-bottom: 1.5rem;">
                ${icon}
                <span>${message}</span>
            </div>`;
        setTimeout(() => container.innerHTML = '', 5000);
    }

    // Select2
    $('#new_id_persona').select2({
        placeholder: "Buscar en tu Área...",
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            url: "{{ route('api.actividad.participantes.search') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    es_visitante: $('#is_visitor_search').is(':checked')
                };
            },
            processResults: function (data) { return { results: data.results }; }
        }
    });

    // Precarga Institución
    $('#new_id_persona').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.institucion_id) {
            if ($("#institucion-select option[value='" + data.institucion_id + "']").length > 0) {
                $('#institucion-select').val(data.institucion_id).trigger('change');
            }
        } else {
            $('#institucion-select').val('').trigger('change');
        }
        checkAdd(); 
    });

    // Modo Visitante
    $('#is_visitor_search').change(function(){
        $('#new_id_persona').val(null).trigger('change');
        $('#institucion-select').val('').trigger('change');
        
        if($(this).is(':checked')) {
            $('#new_id_persona').select2('destroy');
            $('#new_id_persona').select2({
                placeholder: "🔒 INGRESE C.I. EXACTO...",
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('api.actividad.participantes.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { q: params.term, es_visitante: true }; },
                    processResults: function (data) { return { results: data.results }; }
                }
            });
        } else {
            $('#new_id_persona').select2('destroy');
            $('#new_id_persona').select2({
                placeholder: "Buscar en tu Área...",
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('api.actividad.participantes.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { q: params.term, es_visitante: false }; },
                    processResults: function (data) { return { results: data.results }; }
                }
            });
        }
    });

    // Habilitar Botón Añadir
    function checkAdd() {
        const personaVal = $('#new_id_persona').val();
        const btn = document.getElementById('addParticipantBtn');
        
        if (personaVal && personaVal !== "") {
            btn.disabled = false;
            btn.style.opacity = "1";
        } else {
            btn.disabled = true;
            btn.style.opacity = "0.6";
        }
    }
    
    $('#new_id_persona').on('change', checkAdd);

    // Añadir a Lista Local Mejorado
    document.getElementById('addParticipantBtn').addEventListener('click', function() {
        const data = $('#new_id_persona').select2('data')[0];
        const instId = $('#institucion-select').val();
        const instText = $('#institucion-select option:selected').text();
        
        if(!data) return;
        
        if(document.querySelector(`tr[data-id="${data.id}"]`)) {
            showFlashMessage('Esta persona ya está en la lista.', 'danger');
            return;
        }

        const tbody = document.querySelector('#participantsTable tbody');
        
        // Si no hay tabla visible, creamos una
        if (!tbody) {
            const table = document.getElementById('participantsTable');
            const newTbody = document.createElement('tbody');
            table.appendChild(newTbody);
            document.querySelector('.empty-state').style.display = 'none';
        }

        const row = tbody.insertRow();
        row.dataset.id = data.id;
        row.dataset.isSaved = 'false';
        row.dataset.institucionId = instId; 
        
        const dis = document.getElementById('new_tiene_discapacidad').checked;
        const fam = document.getElementById('new_es_familiar').checked;
        const fir = document.getElementById('new_firma').checked;

        row.dataset.dis = dis ? 1 : 0;
        row.dataset.fam = fam ? 1 : 0;
        row.dataset.fir = fir ? 1 : 0;

        const displayInstText = instId === '' ? 'Sin Institución' : instText;
        const instBadge = instId === '' ? 
            '<span style="color: var(--color-text-light); font-style: italic;">Sin Institución</span>' :
            `<span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                <svg style="width: 1rem; height: 1rem; color: var(--color-text-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                ${instText}
            </span>`;

        const optionsHtml = `
            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                ${dis ? '<span class="badge-option" title="Con Discapacidad" style="background: rgba(67, 160, 71, 0.1); color: var(--color-primary-dark); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">♿</span>' : ''}
                ${fam ? '<span class="badge-option" title="Es Familiar" style="background: rgba(142, 36, 170, 0.1); color: var(--color-purple); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">👪</span>' : ''}
                ${fir ? '<span class="badge-option" title="Registra Firma" style="background: rgba(253, 216, 53, 0.1); color: var(--color-yellow); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">✍️</span>' : ''}
            </div>
        `;

        row.innerHTML = `
            <td style="font-weight: 600; color: var(--color-text-dark);">${data.text}</td>
            <td style="font-family: var(--font-monospace); color: var(--color-text-medium);">${data.ci || '-'}</td>
            <td>${instBadge}</td>
            <td>${optionsHtml}</td>
            <td>
                <span class="badge" style="background: rgba(253, 216, 53, 0.1); color: var(--color-yellow); padding: 0.375rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                    Pendiente
                </span>
            </td>
            <td>
                <button class="action-link link-delete remove-row-btn" title="Quitar participante">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.125rem; height: 1.125rem;"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </td>
        `;
        
        $('#new_id_persona').val(null).trigger('change');
        checkAdd();
        showFlashMessage('Participante añadido a la lista. Recuerda guardar los cambios.', 'info');
    });

    // Eliminar Fila Mejorado
    document.addEventListener('click', function(e) {
        if(e.target.closest('.remove-row-btn')) {
            const row = e.target.closest('tr');
            if(row.dataset.isSaved === 'true') {
                if(confirm('¿Estás seguro de que quieres eliminar permanentemente a este participante?')) {
                    deleteServer(row);
                }
            } else {
                row.remove();
                showFlashMessage('Participante removido de la lista.', 'info');
                
                // Si no quedan filas, mostrar estado vacío
                const tbody = document.querySelector('#participantsTable tbody');
                if (tbody && tbody.children.length === 0) {
                    document.querySelector('.empty-state').style.display = 'block';
                }
            }
        }
    });

    function deleteServer(row) {
        fetch(`{{ url('participaen') }}/${row.dataset.id}/${actividadId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(r => {
            if(r.ok) {
                row.remove();
                showFlashMessage('Participante eliminado correctamente.', 'success');
                
                // Si no quedan filas, mostrar estado vacío
                const tbody = document.querySelector('#participantsTable tbody');
                if (tbody && tbody.children.length === 0) {
                    document.querySelector('.empty-state').style.display = 'block';
                }
            } else {
                showFlashMessage('Error al eliminar el participante.', 'danger');
            }
        });
    }

    // Guardar Todo Mejorado
    document.getElementById('saveParticipantsBtn').addEventListener('click', function() {
        const rows = document.querySelectorAll('tr[data-is-saved="false"]');
        if(rows.length === 0) {
            showFlashMessage('No hay participantes nuevos para guardar.', 'info');
            return;
        }
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Guardando... (${rows.length})
        `;

        const promises = Array.from(rows).map(row => {
            return fetch("{{ route('participaen.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    id_persona: row.dataset.id,
                    id_actividad: actividadId,
                    id_institucion: row.dataset.institucionId || null,
                    tiene_discapacidad: row.dataset.dis,
                    es_familiar: row.dataset.fam,
                    firma: row.dataset.fir
                })
            }).then(r => {
                if(r.ok) {
                    row.dataset.isSaved = 'true';
                    const badge = row.querySelector('.badge');
                    badge.style.background = 'var(--color-primary-light)';
                    badge.style.color = 'var(--color-primary-dark)';
                    badge.innerText = 'Guardado';
                }
            });
        });

        Promise.all(promises).then(() => {
            showFlashMessage(`Se guardaron ${rows.length} participantes correctamente.`, 'success');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }).catch(() => {
            showFlashMessage('Error al guardar algunos participantes.', 'danger');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
});
</script>
@endsection