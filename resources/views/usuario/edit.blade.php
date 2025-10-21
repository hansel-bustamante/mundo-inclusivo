@extends('layouts.main')

@section('title', 'Editar Usuario')

@section('content')
<div class="content-card">

    <h3 class="section-title">Editar Usuario del Sistema</h3>
    <p class="section-subtitle">
        Persona: <strong>{{ $usuario->persona->nombre ?? 'N/A' }} {{ $usuario->persona->apellido_paterno ?? '' }}</strong>
    </p>

    {{-- BLOQUE DE ERRORES --}}
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

    <form action="{{ route('usuario.update', $usuario->id_persona) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid" style="grid-template-columns: repeat(2, 1fr);">
            
            {{-- Columna 1: Datos de Acceso --}}
            <div class="form-column">
                <h4 class="form-column-title">Datos de Acceso</h4>
                
                {{-- ID_PERSONA (Mostrar, no editable) --}}
                <div class="form-group">
                    <label for="id_persona" class="form-label">ID de Persona</label>
                    <input type="text" value="{{ $usuario->id_persona }}" disabled class="form-input form-disabled">
                </div>
                
                {{-- NOMBRE_USUARIO --}}
                <div class="form-group">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario (*)</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" 
                           value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" required 
                           class="form-input @error('nombre_usuario') is-invalid @enderror">
                    @error('nombre_usuario')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CONTRASENA --}}
                <div class="form-group">
                    <label for="contrasena" class="form-label">Contraseña (Dejar vacío para no cambiar)</label>
                    {{-- La contraseña es OPCIONAL en la edición --}}
                    <input type="password" id="contrasena" name="contrasena" 
                           class="form-input @error('contrasena') is-invalid @enderror">
                    @error('contrasena')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Columna 2: Datos de Rol y Contacto --}}
            <div class="form-column">
                <h4 class="form-column-title">Rol y Área</h4>
                
                {{-- ROL --}}
                <div class="form-group">
                    <label for="rol" class="form-label">Rol (*)</label>
                    <select id="rol" name="rol" required class="form-input @error('rol') is-invalid @enderror">
                        <option value="">-- Seleccione un Rol --</option>
                        @php $currentRol = old('rol', $usuario->rol); @endphp
                        <option value="admin" {{ $currentRol == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="coordinador" {{ $currentRol == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="registrador" {{ $currentRol == 'registrador' ? 'selected' : '' }}>Registrador</option>
                    </select>
                    @error('rol')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- AREA_INTERVENCION_ID --}}
                <div class="form-group">
                    <label for="area_intervencion_id" class="form-label">Área de Intervención (*)</label>
                    <select id="area_intervencion_id" name="area_intervencion_id" required 
                            class="form-input @error('area_intervencion_id') is-invalid @enderror">
                        <option value="">-- Seleccione Área --</option>
                        @php $currentArea = old('area_intervencion_id', $usuario->area_intervencion_id); @endphp
                        @foreach ($areas as $area)
                            <option value="{{ $area->codigo_area }}" 
                                    {{ $currentArea == $area->codigo_area ? 'selected' : '' }}>
                                {{ $area->nombre_area }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_intervencion_id')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CORREO --}}
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico (*)</label>
                    <input type="email" id="correo" name="correo" 
                           value="{{ old('correo', $usuario->correo) }}" required 
                           class="form-input @error('correo') is-invalid @enderror">
                    @error('correo')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('usuario.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Actualizar Usuario
            </button>
        </div>
    </form>
</div>
@endsection