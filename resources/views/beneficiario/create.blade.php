@extends('layouts.main')

@section('title', 'Registrar Beneficiario')

@section('content')
<div class="content-card">

    <h3 class="section-title">Registrar Extensión de Beneficiario</h3>
    <p class="section-subtitle">Convierta una Persona existente en un Beneficiario.</p>

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

    <form action="{{ route('beneficiario.store') }}" method="POST">
        @csrf
        
        <div class="form-grid" style="grid-template-columns: repeat(1, 1fr);">
            
            {{-- Campo ID_PERSONA --}}
            <div class="form-group">
                <label for="id_persona" class="form-label">ID de la Persona Existente (*)</label>
                {{-- NOTA: En un sistema real, esto sería un select 2 o un buscador por C.I. --}}
                <input type="number" id="id_persona" name="id_persona" 
                       value="{{ old('id_persona') }}" required 
                       class="form-input @error('id_persona') is-invalid @enderror">
                @error('id_persona')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Campo Tipo Discapacidad --}}
            <div class="form-group">
                <label for="tipo_discapacidad" class="form-label">Tipo de Discapacidad (*)</label>
                <input type="text" id="tipo_discapacidad" name="tipo_discapacidad" 
                       value="{{ old('tipo_discapacidad') }}" required 
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
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Guardar Beneficiario
            </button>
        </div>
    </form>
</div>
@endsection