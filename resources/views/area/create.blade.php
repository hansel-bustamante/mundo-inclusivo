@extends('layouts.main')

@section('title', 'Crear Nueva Área de Intervención')

@section('content')
    
    <div class="content-card">
        <h3 class="section-title">Crear Nueva Área de Intervención</h3> 
        <p class="section-subtitle">Complete el código, nombre y datos geográficos del área.</p>

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

        <form action="{{ route('area.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="codigo_area" class="form-label">Código del Área (*)</label>
                    <input type="text" 
                           id="codigo_area" 
                           name="codigo_area" 
                           class="form-input @error('codigo_area') is-invalid @enderror"
                           value="{{ old('codigo_area') }}" 
                           required 
                           maxlength="20"
                           placeholder="Ej: SAL-01, EDU-05">
                    @error('codigo_area')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nombre_area" class="form-label">Nombre del Área (*)</label>
                    <input type="text" 
                           id="nombre_area" 
                           name="nombre_area" 
                           class="form-input @error('nombre_area') is-invalid @enderror"
                           value="{{ old('nombre_area') }}" 
                           required 
                           maxlength="100">
                    @error('nombre_area')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="municipio" class="form-label">Municipio (*)</label>
                    <input type="text" 
                           id="municipio" 
                           name="municipio" 
                           class="form-input @error('municipio') is-invalid @enderror"
                           value="{{ old('municipio') }}" 
                           required 
                           maxlength="100">
                    @error('municipio')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="provincia" class="form-label">Provincia (*)</label>
                    <input type="text" 
                           id="provincia" 
                           name="provincia" 
                           class="form-input @error('provincia') is-invalid @enderror"
                           value="{{ old('provincia') }}" 
                           required 
                           maxlength="100">
                    @error('provincia')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group form-span-2">
                    <label for="departamento" class="form-label">Departamento (*)</label>
                    <input type="text" 
                           id="departamento" 
                           name="departamento" 
                           class="form-input @error('departamento') is-invalid @enderror"
                           value="{{ old('departamento') }}" 
                           required 
                           maxlength="100">
                    @error('departamento')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('area.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Área
                </button>
            </div>
        </form>

    </div>
@endsection