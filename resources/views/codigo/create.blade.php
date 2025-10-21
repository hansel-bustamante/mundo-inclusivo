@extends('layouts.main')

@section('title', 'Crear Nuevo Código de Actividad')

@section('content')
    
    <div class="content-card">

        <h3 class="section-title">Crear Nuevo Código de Actividad</h3> 
        <p class="section-subtitle">Define un código único de 2 caracteres y el nombre de la actividad que representa.</p>

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

        <form action="{{ route('codigo.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="codigo_actividad" class="form-label">Código (2 caracteres) (*)</label>
                    <input type="text" 
                           id="codigo_actividad" 
                           name="codigo_actividad" 
                           class="form-input @error('codigo_actividad') is-invalid @enderror"
                           value="{{ old('codigo_actividad') }}" 
                           required 
                           maxlength="2"
                           minlength="1"
                           style="text-transform: uppercase;"
                           placeholder="Ej: AB, S1">
                    @error('codigo_actividad')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group form-span-2">
                    <label for="nombre_actividad" class="form-label">Nombre de la Actividad (*)</label>
                    <input type="text" 
                           id="nombre_actividad" 
                           name="nombre_actividad" 
                           class="form-input @error('nombre_actividad') is-invalid @enderror"
                           value="{{ old('nombre_actividad') }}" 
                           required 
                           maxlength="100">
                    @error('nombre_actividad')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group form-span-2">
                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              class="form-input @error('descripcion') is-invalid @enderror"
                              rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('codigo.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Código
                </button>
            </div>
        </form>

    </div>
@endsection