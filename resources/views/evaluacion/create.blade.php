@extends('layouts.main')

@section('title', 'Registrar Nueva Evaluación')

@section('content')
<div class="content-card">
    <h3 class="section-title">Registrar Nueva Evaluación</h3>
    <p class="section-subtitle">Complete los campos para registrar una nueva evaluación.</p>

    {{-- Manejo de errores de validación --}}
    @if ($errors->any())
        <div class="error-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('evaluacion.store') }}" method="POST">
            @csrf

            {{-- Formulario reusable --}}
            @include('evaluacion.form', [
                'evaluacion' => null, 
                'actividades' => $actividades, 
                'actividad_seleccionada' => $actividad_seleccionada ?? null
            ])

            {{-- Botones --}}
            <div class="form-actions mt-6">
                <button type="submit" class="btn btn-primary inline-flex items-center">
                    <svg class="btn-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.111a9.004 9.004 0 01-2.435 9.412 9.004 9.004 0 01-11.776-2.435A9.004 9.004 0 016.38 5.467"/>
                    </svg>
                    Guardar Evaluación
                </button>
                <a href="{{ route('evaluacion.index') }}" class="btn btn-secondary ml-2 inline-flex items-center">
                    <svg class="btn-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Evaluaciones
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
