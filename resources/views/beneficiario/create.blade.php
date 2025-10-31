@extends('layouts.main')

@section('title', 'Registrar Beneficiario')

@section('content')
<div class="content-card">

    <h3 class="section-title">Registrar Extensión de Beneficiario</h3>
    <p class="section-subtitle">Convierta una Persona existente en un Beneficiario.</p>

    {{-- BLOQUE DE ERRORES --}}
    @if ($errors->any())
        <div class="error-alert">
            <div class="error-header">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Hubo errores al procesar el formulario:</span>
            </div>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('beneficiario.store') }}" method="POST">
        @csrf
        
        <div class="form-grid" style="grid-template-columns: repeat(1, 1fr);">
            
            {{-- Campo ID_PERSONA - MODIFICADO A SELECT con AJAX --}}
            <div class="form-group">
                <label for="id_persona" class="form-label">Persona a Convertir en Beneficiario (*)</label>

                {{-- El select debe estar vacío inicialmente --}}
                <select id="id_persona" name="id_persona" required 
                        class="form-input @error('id_persona') is-invalid @enderror">
                    
                    {{-- Dejamos una opción por defecto para el placeholder --}}
                    <option value="" disabled selected>-- Escribe el nombre o C.I. de la persona --</option>
                    
                    {{-- El contenido se llenará por AJAX --}}
                    
                    {{-- Si hubo un error de validación, cargamos el elemento previamente seleccionado: --}}
                    @if (old('id_persona') && isset($personaSeleccionada))
                        <option value="{{ old('id_persona') }}" selected>
                            {{ $personaSeleccionada->nombre }} {{ $personaSeleccionada->apellido_paterno }} (C.I.: {{ $personaSeleccionada->carnet_identidad }})
                        </option>
                    @endif
                    
                </select>
                
                <small class="form-text-muted">Busque a la persona por su nombre o carnet de identidad.</small>

                @error('id_persona')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Campo Tipo Discapacidad --}}
            <div class="form-group">
                <label for="tipo_discapacidad" class="form-label">Tipo de Discapacidad (*)</label>
                <input type="text" id="tipo_discapacidad" name="tipo_discapacidad" 
                       value="{{ old('tipo_discapacidad') }}" required 
                       class="form-input @error('tipo_discapacidad') is-invalid @enderror">
                @error('tipo_discapacidad')
                    <p class="input-error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('beneficiario.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Guardar Beneficiario
            </button>
        </div>
    </form>
</div>
@endsection

{{-- Dentro de resources/views/beneficiario/create.blade.php --}}

@section('scripts')
<script>
    $(document).ready(function() {
        
        // ¡Implementación con AJAX!
        $('#id_persona').select2({
            placeholder: "Escribe el nombre o C.I. de la persona...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 3, // Opcional: Empieza a buscar solo después de 3 caracteres
            
            ajax: {
                // Apunta a la nueva ruta que creamos
                url: "{{ route('beneficiario.search_personas') }}",
                dataType: 'json',
                delay: 250, // Pequeño retraso para evitar inundar el servidor
                
                data: function (params) {
                    return {
                        q: params.term, // Parámetro de búsqueda, Select2 lo llama 'term'
                        page: params.page
                    };
                },
                
                processResults: function (data, params) {
                    // Seleccionamos los resultados del JSON que retorna el controlador
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more // Si hay más resultados para la paginación
                        }
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection