@extends('layouts.main')

@section('title', 'Registrar Nueva Sesión')

@section('content')
<div class="content-card">

    {{-- HEADER MEJORADO --}}
    <div class="header-container">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                {{-- BOTÓN DE VOLVER MEJORADO --}}
                @if(isset($actividad))
                    <a href="{{ route('sesion.por_actividad', $actividad->id_actividad) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Sesiones de la Actividad
                    </a>
                @else
                    <a href="{{ route('sesion.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Sesiones
                    </a>
                @endif
                <h3 class="section-title">Registrar Nueva Sesión</h3>
            </div>
            <p class="section-subtitle" style="color: var(--color-text-medium); margin: 0;">
                Complete los campos para planificar una sesión de actividad
            </p>
        </div>
    </div>

    {{-- BLOQUE DE ERRORES --}}
    @if ($errors->any())
        <div class="error-alert" style="margin-bottom: 2rem;">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="error-title">Errores en el formulario</span>
            </div>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INFORMACIÓN CONTEXTUAL --}}
    @if(isset($actividad))
    <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h5 style="font-family: var(--font-heading); font-weight: 600; color: var(--color-text-dark); margin-bottom: 0.25rem;">Creando sesión para actividad existente</h5>
                <p style="color: var(--color-text-medium); margin: 0; font-weight: 500;">
                    {{ $actividad->nombre }} • 
                    {{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }} • 
                    {{ $actividad->lugar }} • 
                    {{ $actividad->areaIntervencion->nombre_area ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('sesion.store') }}" method="POST">
        @csrf
        
        <div class="form-grid">
            
            {{-- COLUMNA 1: INFORMACIÓN BÁSICA --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9-5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Información Básica
                </h4>

                {{-- AVISO DE AUTO-INCREMENTO (Reemplaza el input numérico) --}}
                <div class="form-group" style="background-color: #e3f2fd; padding: 1rem; border-radius: 8px; border: 1px solid #bbdefb; margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 0.5rem; align-items: center; color: var(--color-info);">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span style="font-weight: 600; font-size: 0.9rem;">Asignación Automática</span>
                    </div>
                    <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: var(--color-text-dark);">
                        El <strong>Número de Sesión</strong> se calculará automáticamente al guardar, siguiendo la secuencia de la actividad (Ej: Sesión N° 3, N° 4...).
                    </p>
                </div>
                
                {{-- Campo Actividad --}}
                <div class="form-group">
                    <label for="id_actividad" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Actividad
                    </label>
                    
                    @if(isset($actividad))
                        <div style="position: relative;">
                            <input type="text" 
                                   value="{{ $actividad->nombre }}" 
                                   class="form-input form-disabled" 
                                   disabled
                                   style="background-color: var(--color-bg-light); padding-right: 2.5rem;">
                            <svg style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <input type="hidden" name="id_actividad" value="{{ $actividad->id_actividad }}">
                    @else
                        <select id="id_actividad" 
                                name="id_actividad" 
                                class="form-input @error('id_actividad') is-invalid @enderror"
                                required>
                            <option value="">-- Seleccione una actividad --</option>
                            @foreach ($actividades as $act)
                                <option value="{{ $act->id_actividad }}" 
                                        {{ old('id_actividad') == $act->id_actividad ? 'selected' : '' }}>
                                    {{ $act->nombre }} ({{ \Carbon\Carbon::parse($act->fecha)->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_actividad')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    @endif
                </div>
            </div>

            {{-- COLUMNA 2: FECHA Y HORARIO --}}
            <div class="form-column" style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border);">
                <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-info); display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Fecha y Horario
                </h4>
                
                {{-- Campo Fecha --}}
                <div class="form-group">
                    <label for="fecha" class="form-label">
                        <span style="color: var(--color-danger);">*</span> Fecha
                    </label>
                    <input type="date" 
                           id="fecha" 
                           name="fecha" 
                           value="{{ old('fecha') }}" 
                           class="form-input @error('fecha') is-invalid @enderror"
                           required>
                    @error('fecha')
                        <div class="form-error-message">
                            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Campos de Hora --}}
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label for="hora_inicio" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Hora Inicio
                        </label>
                        <input type="time" 
                               id="hora_inicio" 
                               name="hora_inicio" 
                               value="{{ old('hora_inicio') }}" 
                               class="form-input @error('hora_inicio') is-invalid @enderror"
                               required>
                        @error('hora_inicio')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="hora_fin" class="form-label">
                            <span style="color: var(--color-danger);">*</span> Hora Fin
                        </label>
                        <input type="time" 
                               id="hora_fin" 
                               name="hora_fin" 
                               value="{{ old('hora_fin') }}" 
                               class="form-input @error('hora_fin') is-invalid @enderror"
                               required>
                        @error('hora_fin')
                            <div class="form-error-message">
                                <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Indicador de Duración --}}
                <div id="duracion-indicator" style="background: rgba(30, 136, 229, 0.05); padding: 0.75rem; border-radius: var(--border-radius); border: 1px solid rgba(30, 136, 229, 0.2); margin-top: 1rem; display: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-info);">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span id="duracion-text">Duración calculada: </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMPO TEMA (MODIFICADO) --}}
        <div style="background: var(--color-bg-light); padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid var(--color-border); margin: 2rem 0;">
            <h4 class="form-column-title" style="font-family: var(--font-heading); font-size: 1.125rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--color-warning); display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                Contenido de la Sesión
            </h4>

            <div class="form-group">
                <label for="tema" class="form-label">
                    Tema de la Sesión <span style="font-weight: normal; color: var(--color-text-light); font-size: 0.85rem; margin-left: 0.5rem;">(Opcional)</span>
                </label>
                <input type="text" 
                       id="tema" 
                       name="tema" 
                       value="{{ old('tema') }}" 
                       class="form-input @error('tema') is-invalid @enderror"
                       placeholder="Deje en blanco para generar automáticamente (Ej: Sesión N° X)"
                       maxlength="150">
                @error('tema')
                    <div class="form-error-message">
                        <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
                <div style="font-size: 0.75rem; color: var(--color-text-light); margin-top: 0.25rem;">
                    Si no escribe un tema, se asignará el nombre "Sesión N° X" automáticamente.
                </div>
            </div>
        </div>

        {{-- ADVERTENCIA DE VALIDACIÓN --}}
        <div style="background: #fff3cd; padding: 1.5rem; border-radius: var(--border-radius); border: 1px solid #ffeaa7; margin: 2rem 0;">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #856404; flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <h5 style="font-family: var(--font-heading); font-weight: 600; color: #856404; margin-bottom: 0.5rem;">Antes de guardar</h5>
                    <p style="color: #856404; font-size: 0.875rem; margin: 0;">
                        Verifique que la fecha y horario de la sesión sean coherentes con la actividad seleccionada.
                        Asegúrese de que no existan conflictos de horario con otras sesiones.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-actions" style="border-top: 1px solid var(--color-border); padding-top: 2rem; margin-top: 2rem;">
            @if(isset($actividad))
                <a href="{{ route('sesion.por_actividad', $actividad->id_actividad) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
            @else
                <a href="{{ route('sesion.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
            @endif
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Guardar Sesión
            </button>
        </div>
    </form>
</div>

{{-- Estilos y Scripts --}}
<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .form-column {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    
    .form-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.1);
        transform: translateY(-1px);
    }
    
    .form-input.is-invalid {
        border-color: var(--color-danger);
        background-color: #fef2f2;
    }
    
    .form-disabled {
        background-color: var(--color-bg-light) !important;
        color: var(--color-text-medium) !important;
        cursor: not-allowed;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .form-column {
            padding: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const temaInput = document.getElementById('tema');
    
    // Capitalizar tema
    temaInput.addEventListener('blur', function() {
        if (this.value) {
            this.value = this.value.replace(/\b\w/g, function(l) {
                return l.toUpperCase();
            });
        }
    });

    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    const duracionIndicator = document.getElementById('duracion-indicator');
    const duracionText = document.getElementById('duracion-text');

    function calcularDuracion() {
        if (horaInicio.value && horaFin.value) {
            const [inicioH, inicioM] = horaInicio.value.split(':').map(Number);
            const [finH, finM] = horaFin.value.split(':').map(Number);
            
            let totalMinutos = (finH * 60 + finM) - (inicioH * 60 + inicioM);
            
            if (totalMinutos < 0) {
                duracionText.textContent = '⚠️ Hora fin debe ser posterior a hora inicio';
                duracionIndicator.style.display = 'block';
                duracionIndicator.style.background = 'rgba(229, 57, 53, 0.05)';
                duracionIndicator.style.borderColor = 'rgba(229, 57, 53, 0.2)';
                duracionText.style.color = 'var(--color-danger)';
                return;
            }
            
            const horas = Math.floor(totalMinutos / 60);
            const minutos = totalMinutos % 60;
            
            let duracionStr = '';
            if (horas > 0) {
                duracionStr += `${horas} hora${horas > 1 ? 's' : ''}`;
            }
            if (minutos > 0) {
                if (horas > 0) duracionStr += ' ';
                duracionStr += `${minutos} minuto${minutos > 1 ? 's' : ''}`;
            }
            
            duracionText.textContent = `Duración: ${duracionStr}`;
            duracionIndicator.style.display = 'block';
            duracionIndicator.style.background = 'rgba(67, 160, 71, 0.05)';
            duracionIndicator.style.borderColor = 'rgba(67, 160, 71, 0.2)';
            duracionText.style.color = 'var(--color-primary)';
        } else {
            duracionIndicator.style.display = 'none';
        }
    }

    horaInicio.addEventListener('change', calcularDuracion);
    horaFin.addEventListener('change', calcularDuracion);

    const fechaInput = document.getElementById('fecha');
    const today = new Date().toISOString().split('T')[0];
    fechaInput.min = today;

    const requiredInputs = document.querySelectorAll('input[required], select[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endsection