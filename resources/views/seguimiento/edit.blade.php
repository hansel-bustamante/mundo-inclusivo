@extends('layouts.main')

@section('title', 'Editar Seguimiento')

@section('content')
<div class="content-card">
    {{-- Título principal --}}
    <h1 class="section-title">Editar Seguimiento #{{ $seguimiento->id_seguimiento }}</h1>
    
    {{-- Subtítulo para contexto. Se usa un estilo simple, ya que 'section-subtitle' no está definida. --}}
    <p style="color: var(--color-text-medium); margin-bottom: 2rem;">
        Actividad: <strong>{{ $seguimiento->actividad->nombre }}</strong>
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
        <form action="{{ route('seguimiento.update', $seguimiento->id_seguimiento) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- El partial 'seguimiento.form' debe recibir las variables necesarias --}}
            @include('seguimiento.form', [
                'seguimiento' => $seguimiento,
                'actividades' => $actividades ?? [], // Asegúrate de pasar las actividades si es necesario
                'actividad_seleccionada' => $seguimiento->id_actividad // Pasa la ID de la actividad actual
            ])

            {{-- Contenedor de acciones del formulario --}}
            <div class="form-actions">
                {{-- Botón de Actualizar: Se eliminan clases de utilidad redundantes --}}
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Actualizar Seguimiento
                </button>
                
                {{-- Botón Cancelar: Se eliminan clases de utilidad redundantes --}}
                <a href="{{ route('seguimiento.index') }}" class="btn btn-secondary">
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