@extends('layouts.main')

@section('title', 'Reporte de Cumplimiento')

@section('content')
<div class="content-card">
    <div class="page-header no-print">
        <h1 class="section-title">Reporte de Cumplimiento y Asistencia</h1>
        <p class="section-subtitle">Seleccione una actividad para analizar el nivel de participación.</p>
    </div>

    {{-- SELECCIÓN DE ACTIVIDAD --}}
    <form method="GET" class="no-print" style="background: #fefce8; padding: 20px; border-radius: 8px; border: 1px solid #fde047; margin-bottom: 30px;">
        <div class="form-group" style="display:flex; gap:15px; align-items:end;">
            <div style="flex:1;">
                <label class="form-label">Seleccionar Actividad a Evaluar:</label>
                <select name="actividad_id" class="form-select">
                    <option value="">-- Seleccione --</option>
                    @foreach($actividades as $act)
                        <option value="{{ $act->id_actividad }}" {{ $actividadId == $act->id_actividad ? 'selected' : '' }}>
                            {{ $act->nombre }} ({{ \Carbon\Carbon::parse($act->fecha)->format('d/m/Y') }}) - {{ $act->areaIntervencion->nombre_area ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generar Análisis</button>
        </div>
    </form>

    @if($actividadSeleccionada)
        <div class="page-header no-print" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3>Resultados para: <span style="color:#2563eb;">{{ $actividadSeleccionada->nombre }}</span></h3>
            <div style="display:flex; gap:10px;">
                <button onclick="exportTableToExcel('tablaCumplimiento', 'Cumplimiento_{{ Str::slug($actividadSeleccionada->nombre) }}')" class="btn" style="background:#107c41; color:white; border:none;">Excel</button>
                <button onclick="window.print()" class="btn btn-secondary">Imprimir</button>
            </div>
        </div>

        <div style="margin-bottom: 20px; display:flex; gap:20px;" class="no-print">
            <div class="stat-card bg-blue">Total Sesiones: <strong>{{ $totalSesiones }}</strong></div>
            <div class="stat-card bg-green">Total Inscritos: <strong>{{ count($datosReporte) }}</strong></div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="tablaCumplimiento">
                <thead>
                    <tr>
                        <th>Participante</th>
                        <th>C.I.</th>
                        <th>Sesiones Asistidas</th>
                        <th>% Asistencia</th>
                        <th>Estado de Cumplimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datosReporte as $dato)
                        <tr>
                            <td>{{ $dato['nombre'] }}</td>
                            <td>{{ $dato['ci'] }}</td>
                            <td style="text-align:center;">{{ $dato['asistencias'] }} / {{ $totalSesiones }}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="flex:1; background:#e5e7eb; height:8px; border-radius:4px; overflow:hidden;">
                                        <div style="width:{{ $dato['porcentaje'] }}%; background:{{ $dato['porcentaje'] == 100 ? '#22c55e' : ($dato['porcentaje'] >= 50 ? '#3b82f6' : '#ef4444') }}; height:100%;"></div>
                                    </div>
                                    <span style="font-size:0.85rem; font-weight:bold;">{{ $dato['porcentaje'] }}%</span>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                @if($dato['cumplio'])
                                    <span class="badge badge-success">★ ASISTENCIA PERFECTA</span>
                                @elseif($dato['porcentaje'] >= 80)
                                    <span class="badge badge-info">APROBADO</span>
                                @else
                                    <span class="badge badge-danger">INSUFICIENTE</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No hay participantes inscritos en esta actividad.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center; padding:50px; color:#6b7280; border:2px dashed #e5e7eb; border-radius:10px;">
            <svg style="width:50px; height:50px; margin:0 auto 10px; color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <p>Por favor, seleccione una actividad arriba para ver el reporte de cumplimiento.</p>
        </div>
    @endif
</div>

<style>
    @media print { .no-print { display: none !important; } }
    .stat-card { padding: 10px 20px; border-radius: 8px; background: #f3f4f6; border: 1px solid #e5e7eb; }
    .bg-blue { background: #eff6ff; color: #1e40af; }
    .bg-green { background: #f0fdf4; color: #166534; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename){
        var wb = XLSX.utils.table_to_book(document.getElementById(tableID), {sheet: "Cumplimiento"});
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>
@endsection