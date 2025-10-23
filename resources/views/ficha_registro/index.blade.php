@extends('layouts.main')

@section('title', 'Gestión de Fichas de Registro')

@section('content')
<div class="content-card">
    <h3 class="section-title">Fichas de Registro de Beneficiarios</h3>
    <a href="{{ route('ficha_registro.create') }}" class="btn btn-primary mb-4">
        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9 5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        Crear Nueva Ficha
    </a>

    @if (session('success'))
        <div class="success-alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error-alert">{{ session('error') }}</div>
    @endif

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Beneficiario</th>
                    <th>C.I.</th>
                    <th>Fecha Registro</th>
                    <th>Área Intervención</th>
                    <th>Retraso</th>
                    <th>Registrado Por</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fichas as $ficha)
                    <tr>
                        <td>{{ $ficha->id_ficha }}</td>
                        <td>{{ $ficha->beneficiario->persona->nombre }} {{ $ficha->beneficiario->persona->apellido_paterno }}</td>
                        <td>{{ $ficha->beneficiario->persona->carnet_identidad ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($ficha->fecha_registro)->format('d/m/Y') }}</td>
                        <td>{{ $ficha->areaIntervencion->nombre_area }}</td>
                        <td>
                            <span class="badge {{ $ficha->retraso_en_desarrollo ? 'badge-danger' : 'badge-success' }}">
                                {{ $ficha->retraso_en_desarrollo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>{{ $ficha->usuario->persona->nombre ?? 'Sistema' }}</td>
                        <td class="text-center actions">
                            <a href="{{ route('ficha_registro.edit', $ficha->id_ficha) }}" class="btn-action edit">
                                Editar
                            </a>
                            <button type="button" class="btn-action delete" 
                                onclick="openDeleteModal('{{ route('ficha_registro.destroy', $ficha->id_ficha) }}')">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay Fichas de Registro creadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    /**
     * Abre un diálogo de confirmación y, si es aceptado, crea y envía
     * un formulario con el método DELETE para eliminar un recurso.
     * @param {string} deleteUrl La URL de la ruta destroy de Laravel.
     */
    function openDeleteModal(deleteUrl) {
        if (confirm("¿Estás seguro de que deseas eliminar este registro? Esta acción es irreversible.")) {
            
            // 1. Crear un formulario dinámico
            const form = document.createElement('form');
            form.action = deleteUrl;
            form.method = 'POST'; // Usamos POST para el spoofing de método
            
            // 2. Método Spoofing (_method='DELETE')
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // 3. CSRF Token (_token) - CRÍTICO para la seguridad en Laravel
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            // Leemos el token de la metaetiqueta en layouts/main.blade.php
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            tokenInput.value = csrfToken;
            form.appendChild(tokenInput);

            // 4. Adjuntar el formulario al body y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

@endsection