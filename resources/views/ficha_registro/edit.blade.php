@extends('layouts.main')

@section('title', 'Editar Ficha de Registro')

@section('content')
<div class="content-card">
    <h3 class="section-title">Editar Ficha de Registro</h3>
    <p class="section-subtitle">Beneficiario: <strong>{{ $ficha_registro->beneficiario->persona->nombre }} {{ $ficha_registro->beneficiario->persona->apellido_paterno }}</strong></p>

    @if ($errors->any())
        <div class="error-alert">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    
    <div class="form-container">
        <form action="{{ route('ficha_registro.update', $ficha_registro->id_ficha) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('ficha_registro.form')

            <div class="form-actions mt-6">
                <button type="submit" class="btn btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Actualizar Ficha
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