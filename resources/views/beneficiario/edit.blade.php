@extends('layouts.main')

@section('title', 'Editar Tipo de Discapacidad')

@section('content')
<div class="content-card">

    <h3 class="section-title">Editar Tipo de Discapacidad</h3>
    <p class="section-subtitle">Beneficiario: 
        <strong>{{ $beneficiario->persona->nombre ?? 'N/A' }} {{ $beneficiario->persona->apellido_paterno ?? '' }}</strong>
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

    <form action="{{ route('beneficiario.update', $beneficiario->id_persona) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid" style="grid-template-columns: repeat(1, 1fr);">
            
            {{-- Campo Tipo Discapacidad --}}
            <div class="form-group">
                <label for="tipo_discapacidad" class="form-label">Tipo de Discapacidad (*)</label>
                <input type="text" id="tipo_discapacidad" name="tipo_discapacidad" 
                       value="{{ old('tipo_discapacidad', $beneficiario->tipo_discapacidad) }}" required 
                       class="form-input @error('tipo_discapacidad') is-invalid @enderror">
                @error('tipo_discapacidad')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('beneficiario.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Actualizar Tipo de Discapacidad
            </button>
        </div>
    </form>
</div>
@endsection