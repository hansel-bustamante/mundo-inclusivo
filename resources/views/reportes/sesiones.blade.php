@extends('layouts.main')

@section('title', 'Reporte de Sesiones')

@section('content')
<div class="content-card">
    <div class="page-header no-print" style="display:flex; justify-content:space-between; align-items:center;">
        <h1 class="section-title">Reporte de Sesiones Ejecutadas</h1>
        <div style="display:flex; gap:10px;">
            <button onclick="exportTableToExcel('tablaSesiones', 'Reporte_Sesiones')" class="btn" style="background:#107c41; color:white; border:none;">Excel</button>
            <button onclick="window.print()" class="btn btn-secondary">Imprimir</button>
        </div>
    </div>

    {{-- FILTROS --}}
    <form method="GET" class="form-grid-3-col no-print" style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 25px; align-items:end;">
        @if(Auth::user()->rol === 'admin')
            <div class="form-group">
                <label class="form-label">Área:</label>
                <select name="area" class="form-select">
                    <option value="all">Todas las Áreas</option>
                    @foreach($areas as $a) <option value="{{ $a->codigo_area }}" {{ $areaFiltro == $a->codigo_area ? 'selected' : '' }}>{{ $a->nombre_area }}</option> @endforeach
                </select>
            </div>
        @endif
        <div class="form-group">
            <label class="form-label">Desde:</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Hasta:</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-input">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" style="width:100%;">Filtrar</button>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="data-table" id="tablaSesiones">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Actividad</th>
                    <th>Sesión N°</th>
                    <th>Tema Abordado</th>
                    <th>Horario</th>
                    <th>Asistentes</th>
                    <th>Área</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiones as $s)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y') }}</td>
                        <td style="font-weight:bold;">{{ $s->actividad->nombre }}</td>
                        <td>{{ $s->nro_sesion }}</td>
                        <td>{{ $s->tema }}</td>
                        <td>{{ \Carbon\Carbon::parse($s->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->hora_fin)->format('H:i') }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-info">{{ $s->cantidad_asistentes }} pers.</span>
                        </td>
                        <td>{{ $s->actividad->areaIntervencion->nombre_area ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No hay sesiones registradas en este periodo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>@media print { .no-print { display: none !important; } }</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename){
        var wb = XLSX.utils.table_to_book(document.getElementById(tableID), {sheet: "Sesiones"});
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>
@endsection