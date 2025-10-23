@extends('layouts.main')

@section('title', 'Crear Seguimiento')

@section('content')
<div class="content-card">
    <h3 class="section-title">Registrar Nuevo Seguimiento</h3>
    <p class="section-subtitle">Detalles de la interacción o gestión asociada a una actividad.</p>

    {{-- Mostrar errores de validación --}}
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
        <form action="{{ route('seguimiento.store') }}" method="POST">
            @csrf

            {{-- Incluir formulario reutilizable --}}
            @include('seguimiento.form', [
                'seguimiento' => null,
                'actividades' => $actividades ?? [],
                'actividad_seleccionada' => $actividad_seleccionada ?? null
            ])

            <div class="form-actions mt-6">
                {{-- Botón guardar --}}
                <button type="submit" class="btn btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                               d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Seguimiento
                </button>

                {{-- Botón cancelar --}}
                <a href="{{ route('seguimiento.index') }}" class="btn btn-secondary ml-2 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
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
