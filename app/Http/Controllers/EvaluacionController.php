<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $query = Evaluacion::with('actividad');

        // Seguridad Lógica M6
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $query->whereHas('actividad', function($q) use ($areaUsuario) {
                if ($areaUsuario === 'M6') {
                    $q->where('area_intervencion_id', 'LIKE', 'M6%');
                } else {
                    $q->where('area_intervencion_id', $areaUsuario);
                }
            });
        }

        $evaluaciones = $query->get();
        return view('evaluacion.index', compact('evaluaciones'));
    }

    public function create()
    {
        $usuario = Auth::user();
        
        if ($usuario->rol === 'admin') {
            $actividades = Actividad::orderBy('nombre', 'asc')->get();
        } else {
            if ($usuario->area_intervencion_id === 'M6') {
                $actividades = Actividad::where('area_intervencion_id', 'LIKE', 'M6%')
                                        ->orderBy('nombre', 'asc')
                                        ->get();
            } else {
                $actividades = Actividad::where('area_intervencion_id', $usuario->area_intervencion_id)
                                        ->orderBy('nombre', 'asc')
                                        ->get();
            }
        }

        return view('evaluacion.create', compact('actividades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'actividad_id' => 'required|exists:actividad,id_actividad',
            'fecha' => 'required|date',
            'resultados_logrados' => 'nullable|string',
            'dificultades' => 'nullable|string',
            'recomendaciones' => 'nullable|string',
        ]);

        // Seguridad (Lógica M6)
        if (Auth::user()->rol !== 'admin') {
            $actividad = Actividad::find($request->actividad_id);
            $areaUsuario = Auth::user()->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }

        Evaluacion::create($request->all());
        return redirect()->route('evaluacion.index')->with('success', 'Evaluación registrada.');
    }

    public function edit($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $usuario = Auth::user();

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $areaActividad = $evaluacion->actividad->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($areaActividad, 'M6')) abort(403);
                $actividades = Actividad::where('area_intervencion_id', 'LIKE', 'M6%')->get();
            } else {
                if ($areaActividad != $areaUsuario) abort(403);
                $actividades = Actividad::where('area_intervencion_id', $areaUsuario)->get();
            }
        } else {
            $actividades = Actividad::all();
        }
        
        return view('evaluacion.edit', compact('evaluacion', 'actividades'));
    }

    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        
        $usuario = Auth::user();
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $areaActividad = $evaluacion->actividad->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($areaActividad, 'M6')) abort(403);
            } else {
                if ($areaActividad != $areaUsuario) abort(403);
            }
        }

        $evaluacion->update($request->all());
        return redirect()->route('evaluacion.index')->with('success', 'Evaluación actualizada.');
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $usuario = Auth::user();

        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $areaActividad = $evaluacion->actividad->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($areaActividad, 'M6')) abort(403);
            } else {
                if ($areaActividad != $areaUsuario) abort(403);
            }
        }

        $evaluacion->delete();
        return redirect()->route('evaluacion.index')->with('success', 'Evaluación eliminada.');
    }
}