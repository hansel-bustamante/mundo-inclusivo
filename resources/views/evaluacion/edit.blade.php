@extends('layouts.main')

@section('title', 'Editar Evaluación')

@section('content')
<div class="content-card">
    <h3 class="section-title">Editar Evaluación</h3>
    <p class="section-subtitle">Actividad: {{ $evaluacion->actividad->nombre }}</p>

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
        <form action="{{ route('evaluacion.update', $evaluacion->id_evaluacion) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Formulario reusable --}}
            @include('evaluacion.form', [
                'evaluacion' => $evaluacion, 
                'actividades' => $actividades
            ])

            {{-- Botones --}}
            <div class="form-actions mt-6">
                <button type="submit" class="btn btn-primary inline-flex items-center">
                    <svg class="btn-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Actualizar Evaluación
                </button>
                <a href="{{ route('evaluacion.index') }}" class="btn btn-secondary ml-2 inline-flex items-center">
                    <svg class="btn-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
