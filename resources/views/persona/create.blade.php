@extends('layouts.main')

@section('title', 'Crear Persona')

@section('content')
<div class="content-card">

    <h3 class="section-title">Registrar Nueva Persona</h3>
    <p class="section-subtitle">Datos base para Beneficiarios y Usuarios del sistema.</p>

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

    <form action="{{ route('persona.store') }}" method="POST">
        @csrf
        
        <div class="form-grid" style="grid-template-columns: repeat(2, 1fr);">
            
            {{-- Columna 1: Nombres --}}
            <div class="form-column">
                <h4 class="form-column-title">Nombres y Apellidos</h4>

                {{-- NOMBRE --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre(s) (*)</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="{{ old('nombre') }}" required 
                           class="form-input @error('nombre') is-invalid @enderror">
                    @error('nombre')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- APELLIDO_PATERNO --}}
                <div class="form-group">
                    <label for="apellido_paterno" class="form-label">Apellido Paterno (*)</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" 
                           value="{{ old('apellido_paterno') }}" required 
                           class="form-input @error('apellido_paterno') is-invalid @enderror">
                    @error('apellido_paterno')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- APELLIDO_MATERNO --}}
                <div class="form-group">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno" 
                           value="{{ old('apellido_materno') }}" 
                           class="form-input @error('apellido_materno') is-invalid @enderror">
                    @error('apellido_materno')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Columna 2: Otros Datos --}}
            <div class="form-column">
                <h4 class="form-column-title">Información de Contacto y Nacimiento</h4>
                
                {{-- FECHA_NACIMIENTO --}}
                <div class="form-group">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento (*)</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento') }}" required 
                           class="form-input @error('fecha_nacimiento') is-invalid @enderror">
                    @error('fecha_nacimiento')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CARNET_IDENTIDAD --}}
                <div class="form-group">
                    <label for="carnet_identidad" class="form-label">Carnet de Identidad (C.I.)</label>
                    <input type="text" id="carnet_identidad" name="carnet_identidad" 
                           value="{{ old('carnet_identidad') }}" 
                           class="form-input @error('carnet_identidad') is-invalid @enderror">
                    @error('carnet_identidad')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CELULAR --}}
                <div class="form-group">
                    <label for="celular" class="form-label">Celular</label>
                    <input type="text" id="celular" name="celular" 
                           value="{{ old('celular') }}" 
                           class="form-input @error('celular') is-invalid @enderror">
                    @error('celular')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- PROCEDENCIA --}}
                <div class="form-group">
                    <label for="procedencia" class="form-label">Lugar de Procedencia</label>
                    <input type="text" id="procedencia" name="procedencia" 
                           value="{{ old('procedencia') }}" 
                           class="form-input @error('procedencia') is-invalid @enderror">
                    @error('procedencia')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- GENERO --}}
                <div class="form-group">
                    <label for="genero" class="form-label">Género (*)</label>
                    <select id="genero" name="genero" required class="form-input @error('genero') is-invalid @enderror">
                        <option value="">-- Seleccione Género --</option>
                        <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('genero')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('persona.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Guardar Persona
            </button>
        </div>
    </form>
</div>
@endsection