@extends('layouts.main')

@section('title', 'Editar Institución: ' . $institucion->nombre_institucion)

@section('content')
    
    <div class="page-header">
        <h1 class="section-title">Editar Institución: **{{ $institucion->nombre_institucion }}**</h1>
        
        <a href="{{ route('institucion.index') }}" class="btn btn-secondary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
            Volver al Listado
        </a>
    </div>

    <div class="content-card">

        @if ($errors->any())
            <div class="error-alert">
                <div class="error-header">
                    <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.3 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="error-title">Parece que hay problemas con la información proporcionada.</span>
                </div>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('institucion.update', $institucion) }}" method="POST">
            @csrf
            @method('PUT') 

            <div class="form-grid">
                
                {{-- CAMPO REQUERIDO: nombre_institucion --}}
                <div class="form-group">
                    <label for="nombre_institucion" class="form-label">Nombre de la Institución</label>
                    <input type="text" id="nombre_institucion" name="nombre_institucion" 
                           class="form-input @error('nombre_institucion') is-invalid @enderror" 
                           value="{{ old('nombre_institucion', $institucion->nombre_institucion) }}" 
                           required>
                    @error('nombre_institucion')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CAMPO REQUERIDO: tipo --}}
                <div class="form-group">
                    <label for="tipo" class="form-label">Tipo de Institución</label>
                    <select id="tipo" name="tipo" 
                             class="form-input @error('tipo') is-invalid @enderror" 
                             required>
                        @php $currentType = old('tipo', $institucion->tipo); @endphp
                        <option value="" disabled>Seleccione un tipo</option>
                        <option value="Privada" {{ $currentType == 'Privada' ? 'selected' : '' }}>Privada</option>
                        <option value="Pública" {{ $currentType == 'Pública' ? 'selected' : '' }}>Pública</option>
                        <option value="ONG" {{ $currentType == 'ONG' ? 'selected' : '' }}>ONG / Fundación</option>
                        <option value="Mixta" {{ $currentType == 'Mixta' ? 'selected' : '' }}>Mixta</option>
                    </select>
                    @error('tipo')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CAMPO REQUERIDO: municipio --}}
                <div class="form-group">
                    <label for="municipio" class="form-label">Municipio</label>
                    <input type="text" id="municipio" name="municipio" 
                           class="form-input @error('municipio') is-invalid @enderror" 
                           value="{{ old('municipio', $institucion->municipio) }}" 
                           required>
                    @error('municipio')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- CAMPO REQUERIDO: telefono (¡CAMBIO A REQUIRED!) --}}
                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" 
                           class="form-input @error('telefono') is-invalid @enderror" 
                           value="{{ old('telefono', $institucion->telefono) }}"
                           required> {{-- Se añade el atributo required --}}
                    @error('telefono')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CAMPO OPCIONAL: direccion --}}
                <div class="form-group form-span-2">
                    <label for="direccion" class="form-label">Dirección (Opcional)</label>
                    <input type="text" id="direccion" name="direccion" 
                           class="form-input @error('direccion') is-invalid @enderror" 
                           value="{{ old('direccion', $institucion->direccion) }}">
                    @error('direccion')
                        <p class="input-error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.047 12.004 12.004 0 00-1.29 11.026 12.004 12.004 0 001.29 11.026A11.955 11.955 0 0112 2.944z"></path></svg>
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
@endsection