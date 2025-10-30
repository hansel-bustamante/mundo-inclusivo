@extends('layouts.main')

@section('title', 'Crear Ficha de Registro')

@section('content')
<div class="content-card">
    {{-- Título principal. Se cambia <h3> a <h1> --}}
    <h1 class="section-title">Crear Nueva Ficha de Registro</h1>
    
    {{-- Subtítulo para contexto. Se usa un estilo simple, ya que 'section-subtitle' no está definida en tu CSS. --}}
    <p style="color: var(--color-text-medium); margin-bottom: 2rem;">
        Datos de diagnóstico y clasificación inicial del Beneficiario.
    </p>

    {{-- Mostrar errores de validación (usando la estructura de error-alert de institucion.css) --}}
    @if ($errors->any())
        <div class="error-alert">
            <div class="error-header">
                {{-- Icono de alerta para el encabezado --}}
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Error de Validación
            </div>
            {{-- La lista de errores requiere la clase 'error-list' --}}
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="form-container">
        <form action="{{ route('ficha_registro.store') }}" method="POST">
            @csrf
            
            {{-- Incluir formulario reutilizable. Se asume que requiere las variables $beneficiarios y $areas --}}
            @include('ficha_registro.form')

            {{-- Contenedor de acciones del formulario --}}
            <div class="form-actions">
                {{-- Botón de Guardar: Se eliminan clases de utilidad redundantes (mt-6, inline-flex, items-center, etc.) --}}
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Guardar Ficha
                </button>
                
                {{-- Botón Cancelar: Se eliminan clases de utilidad redundantes (ml-2, inline-flex, items-center) --}}
                <a href="{{ route('ficha_registro.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

