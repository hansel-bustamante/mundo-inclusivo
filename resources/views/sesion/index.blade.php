@extends('layouts.main')

@section('title', 'Listado de Sesiones')

@section('content')
    
    {{-- Mensajes de Éxito (usando clase personalizada 'flash-message') --}}
    @if (session('success'))
        <div class="flash-message flash-success">
             <svg class="w-6 h-6 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
             {{ session('success') }}
        </div>
    @endif

    {{-- Contenedor principal usando la clase que funciona en Actividad --}}
    <div class="content-card"> 

        <div class="header-container">
            <h1 class="section-title">Listado de Sesiones</h1>
            <a href="{{ route('sesion.create') }}" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nueva Sesión
            </a>
        </div>

        <div class="table-wrapper table-responsive">
            {{-- Tabla usando la clase que funciona en Actividad --}}
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nro / Tema</th>
                        <th>Actividad</th>
                        <th>Fecha / Horario</th>
                        <th class="actions-cell">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sesiones as $sesion)
                    <tr>
                        <td>{{ $sesion->id_sesion }}</td>
                        <td>
                            <p class="font-medium">{{ $sesion->nro_sesion }}</p>
                            <p class="text-sm text-gray-600">{{ $sesion->tema }}</p>
                        </td>
                        <td>{{ $sesion->actividad->nombre ?? 'Actividad Eliminada' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}<br>
                            <span class="text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                
                                {{-- ************************************ --}}
                                {{-- !!! NUEVA ACCIÓN: GESTIONAR ASISTENCIA !!! --}}
                                {{-- ************************************ --}}
                                <a href="{{ route('sesion.asistencia.edit', $sesion->id_sesion) }}" class="action-link link-info" title="Tomar Asistencia">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </a>

                                {{-- Botón Editar usando clases personalizadas --}}
                                <a href="{{ route('sesion.edit', $sesion->id_sesion) }}" class="action-link link-edit" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                
                                {{-- Formulario Eliminar usando clases personalizadas --}}
                                <form action="{{ route('sesion.destroy', $sesion->id_sesion) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar la Sesión Nro. {{ $sesion->nro_sesion }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link link-delete" title="Eliminar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-message">
                        <td colspan="5">
                            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            No hay sesiones registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection