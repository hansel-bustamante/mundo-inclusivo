@extends('layouts.main')

@section('title', 'Reporte de Personas')

@section('content')
<div class="content-card">
    <div class="page-header no-print" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1 class="section-title">Reporte General de Personas</h1>
        
        <div style="display:flex; gap:10px;">
            {{-- Botón Excel --}}
            <button onclick="exportTableToExcel('tablaPersonas', 'Reporte_Personas_' + new Date().toISOString().slice(0,10))" 
                    class="btn" style="background-color: #107c41; color: white; border:none;">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Exportar Excel
            </button>
            
            {{-- Botón Imprimir --}}
            <button onclick="window.print()" class="btn btn-secondary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir / PDF
            </button>
        </div>
    </div>

    {{-- FILTRO DE ÁREA (Solo Admin) --}}
    @if(Auth::user()->rol === 'admin')
    <form method="GET" class="form-inline no-print" style="margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 8px; border:1px solid #e5e7eb;">
        <label style="margin-right: 10px; font-weight: 600; color:#374151;">Filtrar por Área:</label>
        <select name="area" onchange="this.form.submit()" class="form-select" style="width: auto; display: inline-block;">
            <option value="all" {{ $areaFiltro == 'all' ? 'selected' : '' }}>Todas las Áreas</option>
            @foreach($areas as $a)
                <option value="{{ $a->codigo_area }}" {{ $areaFiltro == $a->codigo_area ? 'selected' : '' }}>{{ $a->nombre_area }}</option>
            @endforeach
        </select>
    </form>
    @endif

    {{-- CUADRO DE RESUMEN (Estilo Mejorado) --}}
    <div class="stats-grid no-print">
        <div class="stat-card bg-blue"><span>Total</span><strong>{{ $totales['General'] }}</strong></div>
        <div class="stat-card bg-green"><span>Hombres</span><strong>{{ $totales['Hombres'] }}</strong></div>
        <div class="stat-card bg-pink"><span>Mujeres</span><strong>{{ $totales['Mujeres'] }}</strong></div>
        <div class="stat-card bg-gray"><span>Mayores (18+)</span><strong>{{ $totales['Mayores18'] }}</strong></div>
        <div class="stat-card bg-yellow"><span>Menores (-18)</span><strong>{{ $totales['Menores18'] }}</strong></div>
    </div>

    <div class="table-wrapper">
        <table class="data-table" id="tablaPersonas">
            <thead>
                <tr>
                    <th style="width: 40px;">N°</th>
                    <th>Nombre Completo</th>
                    <th>C.I.</th>
                    <th>Fecha Nac.</th>
                    <th>Edad</th>
                    <th>Género</th>
                    <th>Celular</th>
                    <th>Procedencia</th>
                    <th>Área</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personas as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->nombre }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                        <td>{{ $p->carnet_identidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->age }}</td>
                        <td>{{ $p->genero }}</td>
                        <td>{{ $p->celular }}</td>
                        <td>{{ $p->procedencia }}</td>
                        <td>{{ $p->areaIntervencion->nombre_area ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">Sin registros encontrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ESTILOS EXTRA --}}
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 25px; }
    .stat-card { padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05); }
    .stat-card span { display: block; font-size: 0.85rem; color: #555; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-card strong { font-size: 1.5rem; color: #111; }
    .bg-blue { background: #eff6ff; border-bottom: 3px solid #3b82f6; }
    .bg-green { background: #f0fdf4; border-bottom: 3px solid #22c55e; }
    .bg-pink { background: #fdf2f8; border-bottom: 3px solid #ec4899; }
    .bg-gray { background: #f9fafb; border-bottom: 3px solid #6b7280; }
    .bg-yellow { background: #fefce8; border-bottom: 3px solid #eab308; }
    
    @media print {
        .no-print { display: none !important; }
        .content-card { box-shadow: none; border: none; padding: 0; }
        table { font-size: 10px; width: 100%; }
        th { background-color: #eee !important; -webkit-print-color-adjust: exact; }
    }
</style>

{{-- SCRIPTS PARA EXPORTAR A EXCEL --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename = 'Reporte'){
        var downloadLink;
        var dataType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        // Crear libro de trabajo
        var wb = XLSX.utils.table_to_book(tableSelect, {sheet: "Datos"});
        // Escribir archivo y descargar
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>
@endsection