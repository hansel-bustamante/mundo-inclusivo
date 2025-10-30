@extends('layouts.main')

@section('title', 'Registrar Seguimiento')

@section('content')
<div class="content-card">
    {{-- Encabezado de la tarjeta --}}
    {{-- Nota: El archivo institucion.css no define 'section-subtitle', solo 'section-title'. Mantendré la etiqueta pero eliminaré la clase si no existe, o la reemplazaré si se encuentra una clase similar en dashboard.css --}}
    <h1 class="section-title">Registrar Nuevo Seguimiento</h1> 
    {{-- La clase 'section-title' está en institucion.css y dashboard.css (aunque en dashboard.css es 1.75rem, más adecuada para subtítulos de página). Usaré 'section-title' para el título principal. --}}
    
    {{-- Mensaje introductorio, si es necesario, sin una clase específica de CSS --}}
    <p style="color: var(--color-text-medium); margin-bottom: 2rem;">Detalles de la interacción o gestión asociada a una actividad.</p>

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

    {{-- Contenedor del formulario --}}
    <div class="form-container">
        {{-- Aquí se asume que el partial 'seguimiento.form' contendrá el grid y los inputs con clases 'form-grid', 'form-label', 'form-input', etc., definidas en institucion.css --}}
        <form action="{{ route('seguimiento.store') }}" method="POST">
            @csrf

            {{-- Incluir formulario reutilizable --}}
            @include('seguimiento.form', [
                'seguimiento' => null,
                'actividades' => $actividades ?? [],
                'actividad_seleccionada' => $actividad_seleccionada ?? null
            ])

            <div class="form-actions">
                {{-- Botón guardar: Se eliminan las clases de utilidad (inline-flex, items-center) porque están implícitas en .btn --}}
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Seguimiento
                </button>

                {{-- Botón cancelar: Se usan las clases correctas y se elimina ml-2 --}}
                <a href="{{ route('seguimiento.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection