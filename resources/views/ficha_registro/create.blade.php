@extends('layouts.main')

@section('title', 'Crear Ficha de Registro')

@section('content')
<div class="content-card">
    <h3 class="section-title">Crear Nueva Ficha de Registro</h3>
    <p class="section-subtitle">Datos de diagnóstico y clasificación inicial del Beneficiario.</p>

    @if ($errors->any())
        <div class="error-alert">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    
    <div class="form-container">
        <form action="{{ route('ficha_registro.store') }}" method="POST">
            @csrf
            
            @include('ficha_registro.form')

            <div class="form-actions mt-6">
                <button type="submit" class="btn btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Ficha
                </button>
                <a href="{{ route('ficha_registro.index') }}" class="btn btn-secondary ml-2 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection