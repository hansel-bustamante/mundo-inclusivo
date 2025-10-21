@extends('layouts.main')

@section('title', 'Editar Sesión: ' . $sesion->nro_sesion)

@section('content')
<div class="content-card">

    <h3 class="section-title">Editar Sesión Nro. {{ $sesion->nro_sesion }}</h3>
    <p class="section-subtitle">Modifique los campos necesarios y guarde los cambios.</p>

    {{-- BLOQUE DE ERRORES (Copiado de Actividad) --}}
    @if ($errors->any())
        <div class="error-alert">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Hubo errores al procesar el formulario:</span>
            </div>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sesion.update', $sesion->id_sesion) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid-3-col">
            
            {{-- Campo Nro. Sesión --}}
            <div class="form-group">
                <label for="nro_sesion" class="form-label">Número de Sesión (*)</label>
                <input type="number" id="nro_sesion" name="nro_sesion" 
                       value="{{ old('nro_sesion', $sesion->nro_sesion) }}" required 
                       class="form-input @error('nro_sesion') is-invalid @enderror">
                @error('nro_sesion')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Campo Fecha --}}
            <div class="form-group">
                <label for="fecha" class="form-label">Fecha (*)</label>
                <input type="date" id="fecha" name="fecha" 
                       value="{{ old('fecha', $sesion->fecha) }}" required 
                       class="form-input @error('fecha') is-invalid @enderror">
                @error('fecha')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Campo Hora Inicio (CORRECCIÓN: Formatear a H:i) --}}
            <div class="form-group">
                <label for="hora_inicio" class="form-label">Hora de Inicio (*)</label>
                <input type="time" id="hora_inicio" name="hora_inicio" 
                       value="{{ old('hora_inicio', \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i')) }}" required 
                       class="form-input @error('hora_inicio') is-invalid @enderror">
                @error('hora_inicio')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Campo Hora Fin (CORRECCIÓN: Formatear a H:i) --}}
            <div class="form-group">
                <label for="hora_fin" class="form-label">Hora de Fin (*)</label>
                <input type="time" id="hora_fin" name="hora_fin" 
                       value="{{ old('hora_fin', \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i')) }}" required 
                       class="form-input @error('hora_fin') is-invalid @enderror">
                @error('hora_fin')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo Actividad (Ocupa 2 columnas) --}}
            <div class="form-group form-span-2">
                <label for="id_actividad" class="form-label">Actividad Relacionada (*)</label>
                <select id="id_actividad" name="id_actividad" required 
                        class="form-input @error('id_actividad') is-invalid @enderror">
                    <option value="">-- Seleccione una Actividad --</option>
                    @isset($actividades)
                        @foreach ($actividades as $actividad)
                            <option value="{{ $actividad->id_actividad }}" 
                                {{ old('id_actividad', $sesion->id_actividad) == $actividad->id_actividad ? 'selected' : '' }}>
                                {{ $actividad->nombre }} ({{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    @endisset
                </select>
                @error('id_actividad')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Campo Tema (Ocupa 3 columnas) --}}
            <div class="form-group form-span-3">
                <label for="tema" class="form-label">Tema de la Sesión (*)</label>
                <input type="text" id="tema" name="tema" 
                       value="{{ old('tema', $sesion->tema) }}" required maxlength="150" 
                       class="form-input @error('tema') is-invalid @enderror">
                @error('tema')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('sesion.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Actualizar Sesión
            </button>
        </div>
    </form>
</div>
@endsection