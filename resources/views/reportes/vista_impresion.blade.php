<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Oficial - {{ $titulo }}</title>
    <style>
        /* ESTILOS GENERALES PARA IMPRESIÓN Y PANTALLA */
        @page {
            margin: 1.5cm;
            size: A4 landscape; /* Hoja horizontal para mejor visualización de tablas */
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background: white;
        }

        /* ENCABEZADO */
        .header-container {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .header-left h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            color: #2c3e50;
        }
        .header-left p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .header-right {
            text-align: right;
            font-size: 11px;
            color: #7f8c8d;
        }

        /* TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 2px solid #ddd;
            padding: 8px 5px;
            text-align: left;
        }
        td {
            padding: 8px 5px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* PIE DE PÁGINA */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }

        /* BOTONES NO IMPRIMIBLES */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .btn {
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            border: none;
        }
        .btn-back { background-color: #95a5a6; margin-left: 10px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Imprimir</button>
        <a href="javascript:history.back()" class="btn btn-back">Volver</a>
    </div>

    <div class="header-container">
        <div class="header-left">
            <h1>{{ $titulo }}</h1>
            <p>Sistema de Gestión Mundo Inclusivo</p>
        </div>
        <div class="header-right">
            <p><strong>Generado por:</strong> {{ $usuario->nombre_usuario ?? 'Sistema' }}</p>
            <p><strong>Fecha:</strong> {{ date('d/m/Y H:i') }}</p>
            @if(isset($areaFiltro) && $areaFiltro != 'all')
                <p><strong>Filtro Área:</strong> {{ $areaFiltro }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                @foreach($columnas as $col)
                    <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    @if($tipo == 'instituciones')
                        <td style="font-weight:bold;">{{ $item->nombre_institucion }}</td>
                        <td>{{ $item->direccion ?? '-' }}</td>
                        <td>{{ $item->telefono_contacto ?? '-' }}</td>
                        <td>{{ $item->tipo_institucion }}</td>

                    @elseif($tipo == 'actividades')
                        <td style="font-weight:bold;">{{ $item->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $item->lugar }}</td>
                        <td>{{ $item->areaIntervencion->nombre_area ?? 'N/A' }}</td>
                        <td><span style="background:#eee; padding:2px 5px; border-radius:3px;">{{ $item->codigoActividad->codigo_actividad ?? '-' }}</span></td>
                    
                    @elseif($tipo == 'personas')
                        <td>{{ $item->nombre }} {{ $item->apellido_paterno }}</td>
                        <td>{{ $item->carnet_identidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->fecha_nacimiento)->age }} años</td>
                        <td>{{ $item->genero }}</td>
                        <td>{{ $item->areaIntervencion->nombre_area ?? '-' }}</td>

                    @elseif($tipo == 'sesiones')
                            <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $item->actividad->nombre }}</td>
                            <td>{{ $item->nro_sesion }}</td>
                            <td>{{ $item->tema }}</td>
                            <td>{{ $item->actividad->areaIntervencion->nombre_area ?? 'N/A' }}</td>

                    @else
                        <td colspan="{{ count($columnas) }}">Datos no formateados para este reporte.</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnas) + 1 }}" style="text-align: center; padding: 30px; color: #7f8c8d;">
                        --- No se encontraron registros para mostrar ---
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Mundo Inclusivo - Reporte generado automáticamente. Documento interno confidencial.
    </div>

</body>
</html>