@extends('layouts.main')

@section('title', 'Reporte de Seguimientos')

@section('content')
<div class="content-card">
    
    {{-- ENCABEZADO --}}
    <div class="page-header no-print" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1 class="section-title">Reporte de Seguimiento y Monitoreo</h1>
        <div style="display:flex; gap:10px;">
            <button onclick="exportTableToExcel('tablaSeguimiento', 'Seguimientos')" class="btn" style="background:#107c41; color:white; border:none;">Excel</button>
            <button onclick="window.print()" class="btn btn-secondary">Imprimir</button>
        </div>
    </div>

    {{-- FILTRO DE ÁREA (Solo para Admin) --}}
    @if(Auth::user()->rol === 'admin')
        <form method="GET" action="{{ route('reportes.seguimiento') }}" class="form-inline no-print" style="margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 10px;">
                <label for="area" class="form-label" style="margin-bottom: 0;">Filtrar por Área:</label>
                <select name="area" id="area" class="form-select" style="width: auto; min-width: 250px;">
                    <option value="all" {{ $areaFiltro == 'all' ? 'selected' : '' }}>Todas las Áreas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->codigo_area }}" {{ $areaFiltro == $area->codigo_area ? 'selected' : '' }}>
                            {{ $area->nombre_area }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            </div>
        </form>
    @endif

    {{-- TABLA DE DATOS --}}
    <div class="table-wrapper" style="margin-top: 20px;">
        <table class="data-table" id="tablaSeguimiento">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Actividad</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Observaciones</th>
                    <th>Área</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seguimientos as $index => $seguimiento)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $seguimiento->actividad->nombre ?? 'Actividad Eliminada' }}</td>
                        <td>{{ \Carbon\Carbon::parse($seguimiento->fecha)->format('d/m/Y') }}</td>
                        <td><span class="badge badge-info">{{ $seguimiento->tipo ?? 'General' }}</span></td>
                        <td>{{ $seguimiento->descripcion ?? $seguimiento->observaciones }}</td>
                        <td>{{ $seguimiento->actividad->areaIntervencion->nombre_area ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding: 30px;">No hay registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>@media print { .no-print { display: none !important; } }</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename){
        var wb = XLSX.utils.table_to_book(document.getElementById(tableID), {sheet: "Seguimientos"});
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>
@endsection