<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Institucion;
use App\Models\Persona;
use App\Models\FichaRegistro;
use App\Models\Actividad;
use App\Models\Seguimiento;
use App\Models\Evaluacion;
use App\Models\AreaIntervencion;
use App\Models\Sesion; 
use App\Models\AsistenciaSesion; 

class ReporteController extends Controller
{
    public function index()
    {
        $rol = Auth::user()->rol;
        $reportes = [];

        $reportes[] = [
            'titulo' => 'Reporte de Instituciones',
            'ruta' => route('reporte.generar', 'instituciones'),
            'desc' => 'Listado general de instituciones aliadas.',
            'color' => 'blue'
        ];

        if (in_array($rol, ['admin', 'registrador'])) {
            $reportes[] = [
                'titulo' => 'Reporte de Personas',
                'ruta' => route('reportes.personas'),
                'desc' => 'Población base registrada por género y edad.',
                'color' => 'green'
            ];
            $reportes[] = [
                'titulo' => 'Reporte de Beneficiarios',
                'ruta' => route('reportes.beneficiarios'),
                'desc' => 'Estadísticas de discapacidad y desarrollo.',
                'color' => 'teal'
            ];
            $reportes[] = [
                'titulo' => 'Reporte de Actividades',
                'ruta' => route('reporte.generar', 'actividades'),
                'desc' => 'Cronograma general de actividades.',
                'color' => 'yellow'
            ];
            $reportes[] = [
                'titulo' => 'Reporte de Sesiones',
                'ruta' => route('reportes.sesiones'),
                'desc' => 'Detalle de sesiones impartidas y temas.',
                'color' => 'indigo'
            ];
            $reportes[] = [
                'titulo' => 'Cumplimiento y Asistencia',
                'ruta' => route('reportes.asistencia'),
                'desc' => 'Análisis de asistencia perfecta por actividad.',
                'color' => 'orange'
            ];
        }

        if (in_array($rol, ['admin', 'coordinador'])) {
            $reportes[] = [
                'titulo' => 'Reporte de Seguimiento',
                'ruta' => route('reportes.seguimiento'),
                'desc' => 'Monitoreo de avances e hitos.',
                'color' => 'purple'
            ];
            $reportes[] = [
                'titulo' => 'Reporte de Evaluaciones',
                'ruta' => route('reportes.evaluaciones'),
                'desc' => 'Resultados de impacto cualitativo.',
                'color' => 'pink'
            ];
        }

        return view('reportes.index', compact('reportes'));
    }

    // -------------------------------------------------------------------
    // 1. REPORTE DE PERSONAS (Lógica M6)
    // -------------------------------------------------------------------
    public function personas(Request $request)
    {
        $usuario = Auth::user();
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        $areaFiltro = $request->input('area', 'all');

        $query = Persona::with('areaIntervencion');

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                $query->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $query->where('area_intervencion_id', $areaUsuario);
            }
            $areaFiltro = $areaUsuario;
        } elseif ($areaFiltro !== 'all') {
            $query->where('area_intervencion_id', $areaFiltro);
        }

        $personas = $query->orderBy('apellido_paterno')->get();

        $totales = [
            'Hombres' => 0, 'Mujeres' => 0,
            'Mayores18' => 0, 'Menores18' => 0,
            'General' => $personas->count()
        ];

        foreach ($personas as $p) {
            $edad = Carbon::parse($p->fecha_nacimiento)->age;
            if ($p->genero === 'M') $totales['Hombres']++; else $totales['Mujeres']++;
            if ($edad >= 18) $totales['Mayores18']++; else $totales['Menores18']++;
        }

        return view('reportes.personas', compact('personas', 'areas', 'areaFiltro', 'totales'));
    }

    // -------------------------------------------------------------------
    // 2. REPORTE DE BENEFICIARIOS (Lógica M6)
    // -------------------------------------------------------------------
    public function beneficiarios(Request $request)
    {
        $usuario = Auth::user();
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        
        $areaFiltro = $request->input('area', 'all');
        $anioFiltro = $request->input('anio', Carbon::now()->year);
        $periodoFiltro = $request->input('periodo', 'annual');

        $query = FichaRegistro::with(['beneficiario.persona', 'areaIntervencion']);

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                $query->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $query->where('area_intervencion_id', $areaUsuario);
            }
            $areaFiltro = $areaUsuario;
        } elseif ($areaFiltro !== 'all') {
            $query->where('area_intervencion_id', $areaFiltro);
        }

        $query->whereYear('fecha_registro', $anioFiltro);
        
        if (str_starts_with($periodoFiltro, 'q')) {
            $trimestre = (int)substr($periodoFiltro, 1);
            $mesInicio = (($trimestre - 1) * 3) + 1;
            $mesFin = $trimestre * 3;
            $query->whereMonth('fecha_registro', '>=', $mesInicio)
                  ->whereMonth('fecha_registro', '<=', $mesFin);
        }

        $fichas = $query->get();

        $stats = [
            'Total' => ['Nino' => 0, 'Nina' => 0, 'Hombre' => 0, 'Mujer' => 0, 'Total' => 0],
            'Retraso' => ['Nino' => 0, 'Nina' => 0, 'Hombre' => 0, 'Mujer' => 0, 'Total' => 0],
            'Discapacidad' => ['Nino' => 0, 'Nina' => 0, 'Hombre' => 0, 'Mujer' => 0, 'Total' => 0],
        ];

        foreach ($fichas as $ficha) {
            $p = $ficha->beneficiario->persona;
            $edad = Carbon::parse($p->fecha_nacimiento)->age;
            $esHombre = $p->genero === 'M';
            $esNino = $edad < 18;

            if ($esNino && $esHombre) $col = 'Nino';
            elseif ($esNino && !$esHombre) $col = 'Nina';
            elseif (!$esNino && $esHombre) $col = 'Hombre';
            else $col = 'Mujer';

            $stats['Total'][$col]++;
            $stats['Total']['Total']++;

            if ($ficha->retraso_en_desarrollo) {
                $stats['Retraso'][$col]++;
                $stats['Retraso']['Total']++;
            }

            if (!empty($ficha->beneficiario->tipo_discapacidad)) {
                $stats['Discapacidad'][$col]++;
                $stats['Discapacidad']['Total']++;
            }
        }

        $aniosDisponibles = FichaRegistro::selectRaw('YEAR(fecha_registro) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');
            
        if($aniosDisponibles->isEmpty()) $aniosDisponibles = [Carbon::now()->year];

        return view('reportes.beneficiarios', compact(
            'fichas', 'stats', 'areas', 'areaFiltro', 
            'anioFiltro', 'periodoFiltro', 'aniosDisponibles'
        ));
    }

    // -------------------------------------------------------------------
    // 3. REPORTE EVALUACIONES (Lógica M6)
    // -------------------------------------------------------------------
    public function evaluaciones(Request $request)
    {
        $usuario = Auth::user();
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        $areaFiltro = $request->input('area', 'all');

        $query = Evaluacion::with(['actividad.areaIntervencion']);

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $query->whereHas('actividad', function($q) use ($areaUsuario) {
                if ($areaUsuario === 'M6') {
                    $q->where('area_intervencion_id', 'LIKE', 'M6%');
                } else {
                    $q->where('area_intervencion_id', $areaUsuario);
                }
            });
            $areaFiltro = $areaUsuario;
        } elseif ($areaFiltro !== 'all') {
            $query->whereHas('actividad', function($q) use ($areaFiltro) {
                $q->where('area_intervencion_id', $areaFiltro);
            });
        }

        $evaluaciones = $query->orderBy('fecha', 'desc')->get();
        
        return view('reportes.evaluaciones', compact('evaluaciones', 'areas', 'areaFiltro'));
    }

    // -------------------------------------------------------------------
    // 4. REPORTE SEGUIMIENTO (Lógica M6)
    // -------------------------------------------------------------------
    public function seguimiento(Request $request)
    {
        $usuario = Auth::user();
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        $areaFiltro = $request->input('area', 'all');

        $query = Seguimiento::with(['actividad.areaIntervencion']);

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $query->whereHas('actividad', function($q) use ($areaUsuario) {
                if ($areaUsuario === 'M6') {
                    $q->where('area_intervencion_id', 'LIKE', 'M6%');
                } else {
                    $q->where('area_intervencion_id', $areaUsuario);
                }
            });
            $areaFiltro = $areaUsuario;
        } elseif ($areaFiltro !== 'all') {
            $query->whereHas('actividad', function($q) use ($areaFiltro) {
                $q->where('area_intervencion_id', $areaFiltro);
            });
        }

        $seguimientos = $query->orderBy('fecha', 'desc')->get();
        
        return view('reportes.seguimiento', compact('seguimientos', 'areas', 'areaFiltro'));
    }

    // -------------------------------------------------------------------
    // 5. REPORTE DE SESIONES (Lógica M6)
    // -------------------------------------------------------------------
    public function sesiones(Request $request)
    {
        $usuario = Auth::user();
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        
        $areaFiltro = $request->input('area', 'all');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Sesion::with(['actividad.areaIntervencion']);

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $query->whereHas('actividad', function($q) use ($areaUsuario) {
                if ($areaUsuario === 'M6') {
                    $q->where('area_intervencion_id', 'LIKE', 'M6%');
                } else {
                    $q->where('area_intervencion_id', $areaUsuario);
                }
            });
            $areaFiltro = $areaUsuario;
        } elseif ($areaFiltro !== 'all') {
            $query->whereHas('actividad', function($q) use ($areaFiltro) {
                $q->where('area_intervencion_id', $areaFiltro);
            });
        }

        if ($fechaInicio) $query->where('fecha', '>=', $fechaInicio);
        if ($fechaFin) $query->where('fecha', '<=', $fechaFin);

        $sesiones = $query->orderBy('fecha', 'desc')->get();

        foreach($sesiones as $sesion) {
            $sesion->cantidad_asistentes = AsistenciaSesion::where('id_sesion', $sesion->id_sesion)->count();
        }

        return view('reportes.sesiones', compact('sesiones', 'areas', 'areaFiltro', 'fechaInicio', 'fechaFin'));
    }

    // -------------------------------------------------------------------
    // 6. REPORTE DE CUMPLIMIENTO (Lógica M6)
    // -------------------------------------------------------------------
    public function asistencia(Request $request)
    {
        $usuario = Auth::user();
        $actividadId = $request->input('actividad_id');
        
        $actividadesQuery = Actividad::orderBy('fecha', 'desc');
        
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                $actividadesQuery->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $actividadesQuery->where('area_intervencion_id', $usuario->area_intervencion_id);
            }
        }
        
        $actividades = $actividadesQuery->get();

        $datosReporte = [];
        $actividadSeleccionada = null;
        $totalSesiones = 0;

        if ($actividadId) {
            $actividadSeleccionada = Actividad::with('areaIntervencion')->find($actividadId);
            
            // Seguridad extra
            if ($usuario->rol !== 'admin') {
                if ($usuario->area_intervencion_id === 'M6') {
                    if (!str_starts_with($actividadSeleccionada->area_intervencion_id, 'M6')) abort(403);
                } else {
                    if ($actividadSeleccionada->area_intervencion_id != $usuario->area_intervencion_id) abort(403);
                }
            }

            if ($actividadSeleccionada) {
                $totalSesiones = Sesion::where('id_actividad', $actividadId)->count();
                $participantes = $actividadSeleccionada->participantes; 
                $idsSesiones = Sesion::where('id_actividad', $actividadId)->pluck('id_sesion');

                foreach ($participantes as $p) {
                    $asistenciasCount = AsistenciaSesion::whereIn('id_sesion', $idsSesiones)
                                                        ->where('id_persona', $p->id_persona)
                                                        ->count();
                    
                    $porcentaje = ($totalSesiones > 0) ? round(($asistenciasCount / $totalSesiones) * 100, 0) : 0;
                    
                    $datosReporte[] = [
                        'nombre' => $p->nombre . ' ' . $p->apellido_paterno,
                        'ci' => $p->carnet_identidad,
                        'asistencias' => $asistenciasCount,
                        'porcentaje' => $porcentaje,
                        'cumplio' => ($asistenciasCount == $totalSesiones && $totalSesiones > 0)
                    ];
                }
            }
        }

        return view('reportes.asistencia', compact('actividades', 'actividadSeleccionada', 'datosReporte', 'totalSesiones', 'actividadId'));
    }

    // -------------------------------------------------------------------
    // 7. GENERADOR GENÉRICO (Lógica M6)
    // -------------------------------------------------------------------
    public function generar($tipo)
    {
        $usuario = Auth::user();
        $data = [];
        $titulo = ucfirst($tipo);
        $columnas = [];
        
        $areaId = ($usuario->rol !== 'admin') ? $usuario->area_intervencion_id : null;
        $areaFiltro = $areaId ?? 'Todas';
        $anioFiltro = null;
        $periodoFiltro = null;

        switch ($tipo) {
            case 'instituciones':
                $data = Institucion::all();
                $columnas = ['Nombre', 'Dirección', 'Contacto', 'Tipo'];
                $titulo = "Listado de Instituciones";
                break;

            case 'actividades':
                $q = Actividad::with('areaIntervencion');
                if ($areaId) {
                    if ($areaId === 'M6') {
                        $q->where('area_intervencion_id', 'LIKE', 'M6%');
                    } else {
                        $q->where('area_intervencion_id', $areaId);
                    }
                }
                $data = $q->orderBy('fecha', 'desc')->get();
                $columnas = ['Nombre', 'Fecha', 'Lugar', 'Área', 'Código'];
                $titulo = "Cronograma de Actividades";
                break;
            
            default: abort(404);
        }

        return view('reportes.vista_impresion', compact(
            'data', 'titulo', 'columnas', 'tipo', 'usuario', 
            'areaFiltro', 'anioFiltro', 'periodoFiltro'
        ));
    }
}