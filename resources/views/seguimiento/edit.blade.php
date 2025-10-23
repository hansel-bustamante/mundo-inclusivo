@extends('layouts.main')

@section('title', 'Editar Seguimiento')

@section('content')
<div class="content-card">
    <h3 class="section-title">Editar Seguimiento #{{ $seguimiento->id_seguimiento }}</h3>
    <p class="section-subtitle">Actividad: <strong>{{ $seguimiento->actividad->nombre }}</strong></p>

    @if ($errors->any())
        <div class="error-alert">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    
    <div class="form-container">
        <form action="{{ route('seguimiento.update', $seguimiento->id_seguimiento) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('seguimiento.form')

            <div class="form-actions mt-6">
                <button type="submit" class="btn btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Actualizar Seguimiento
                </button>
                <a href="{{ route('seguimiento.index') }}" class="btn btn-secondary ml-2 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection