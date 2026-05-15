@extends('layouts.main')

@section('title', 'Reporte de Beneficiarios')

@section('content')
<div class="content-card">
    <div class="page-header no-print" style="display:flex; justify-content:space-between; align-items:center; gap:10px;">
        <h1 class="section-title">Reporte de Beneficiarios</h1>
        <div style="display:flex; gap:10px;">
            <button onclick="window.print()" class="btn btn-secondary">Imprimir</button>
        </div>
    </div>

    {{-- FILTROS MEJORADOS --}}
    <form method="GET" class="form-grid-3-col no-print" style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 25px; align-items: end;">
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
            <label class="form-label">Año:</label>
            <select name="anio" class="form-select">
                @foreach($aniosDisponibles as $ad)
                    <option value="{{ $ad }}" {{ $anioFiltro == $ad ? 'selected' : '' }}>{{ $ad }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Periodo:</label>
            <select name="periodo" class="form-select">
                <option value="annual" {{ $periodoFiltro == 'annual' ? 'selected' : '' }}>Anual (Todo el año)</option>
                <option value="q1" {{ $periodoFiltro == 'q1' ? 'selected' : '' }}>1er Trimestre (Ene-Mar)</option>
                <option value="q2" {{ $periodoFiltro == 'q2' ? 'selected' : '' }}>2do Trimestre (Abr-Jun)</option>
                <option value="q3" {{ $periodoFiltro == 'q3' ? 'selected' : '' }}>3er Trimestre (Jul-Sep)</option>
                <option value="q4" {{ $periodoFiltro == 'q4' ? 'selected' : '' }}>4to Trimestre (Oct-Dic)</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Aplicar Filtros
            </button>
        </div>
    </form>

    {{-- SECCIÓN 1: MATRIZ RESUMEN --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
        <h3 style="margin:0;">Cuadro Resumen Estadístico</h3>
        <button onclick="exportTableToExcel('tablaResumen', 'Resumen_Beneficiarios')" class="btn-sm btn-success no-print" style="background:#107c41; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Excel Resumen</button>
    </div>
    
    <div class="table-wrapper">
        <table class="data-table" id="tablaResumen" style="text-align: center;">
            <thead>
                <tr style="background-color: #f3f4f6;">
                    <th style="text-align: left;">Categoría</th>
                    <th>Niño (M <18)</th>
                    <th>Niña (F <18)</th>
                    <th>Hombre (M 18+)</th>
                    <th>Mujer (F 18+)</th>
                    <th style="background-color: #e5e7eb;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats as $cat => $val)
                    <tr style="{{ $cat == 'Total' ? 'font-weight:bold; background:#f9fafb; border-top:2px solid #ddd;' : '' }}">
                        <td style="text-align: left;">{{ $cat }}</td>
                        <td>{{ $val['Nino'] }}</td>
                        <td>{{ $val['Nina'] }}</td>
                        <td>{{ $val['Hombre'] }}</td>
                        <td>{{ $val['Mujer'] }}</td>
                        <td style="background-color: {{ $cat == 'Total' ? '#e5e7eb' : '#f3f4f6' }};">{{ $val['Total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SECCIÓN 2: LISTADO DETALLADO --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top: 40px; margin-bottom: 10px;">
        <h3 style="margin:0;">Detalle Nominal de Beneficiarios</h3>
        <button onclick="exportTableToExcel('tablaDetalle', 'Lista_Beneficiarios')" class="btn-sm btn-success no-print" style="background:#107c41; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Excel Detallado</button>
    </div>

    <div class="table-wrapper">
        <table class="data-table" id="tablaDetalle">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nombre Completo</th>
                    <th>C.I.</th>
                    <th>Edad</th>
                    <th>Género</th>
                    <th>Discapacidad</th>
                    <th>Retraso</th>
                    <th>Fecha Reg.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fichas as $i => $f)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $f->beneficiario->persona->nombre }} {{ $f->beneficiario->persona->apellido_paterno }}</td>
                        <td>{{ $f->beneficiario->persona->carnet_identidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->beneficiario->persona->fecha_nacimiento)->age }}</td>
                        <td>{{ $f->beneficiario->persona->genero }}</td>
                        <td>{{ $f->beneficiario->tipo_discapacidad }}</td>
                        <td>{{ $f->retraso_en_desarrollo ? 'Sí' : 'No' }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->fecha_registro)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">No se encontraron registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print { .no-print { display: none !important; } }
    .form-grid-3-col { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
</style>

{{-- SCRIPTS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename){
        var tableSelect = document.getElementById(tableID);
        var wb = XLSX.utils.table_to_book(tableSelect, {sheet: "Hoja 1"});
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>
@endsection