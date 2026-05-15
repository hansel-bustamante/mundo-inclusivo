<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $query = Seguimiento::with('actividad');

        // SEGURIDAD: Lógica M6
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            if ($areaUsuario) {
                $query->whereHas('actividad', function($q) use ($areaUsuario) {
                    if ($areaUsuario === 'M6') {
                        $q->where('area_intervencion_id', 'LIKE', 'M6%');
                    } else {
                        $q->where('area_intervencion_id', $areaUsuario);
                    }
                });
            } else {
                $query->where('id_seguimiento', 0);
            }
        }

        $seguimientos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('seguimiento.index', compact('seguimientos'));
    }

    public function create()
    {
        $usuario = Auth::user();
        
        // FILTRO CRÍTICO PARA EL SELECT (Lógica M6)
        if ($usuario->rol === 'admin') {
            $actividades = Actividad::orderBy('nombre')->get();
        } else {
            if ($usuario->area_intervencion_id === 'M6') {
                $actividades = Actividad::where('area_intervencion_id', 'LIKE', 'M6%')
                                        ->orderBy('nombre')->get();
            } else {
                $actividades = Actividad::where('area_intervencion_id', $usuario->area_intervencion_id)
                                        ->orderBy('nombre')->get();
            }
        }

        return view('seguimiento.create', compact('actividades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_actividad' => 'required|exists:actividad,id_actividad',
            'descripcion' => 'required|string',
            'fecha_seguimiento' => 'required|date',
            'tipo' => 'required|string'
        ]);

        // Seguridad al guardar (Lógica M6)
        if (Auth::user()->rol !== 'admin') {
            $actividad = Actividad::find($request->id_actividad);
            $areaUsuario = Auth::user()->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }

        Seguimiento::create($request->all());
        return redirect()->route('seguimiento.index')->with('success', 'Seguimiento registrado.');
    }

    public function edit($id)
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $usuario = Auth::user();

        // Seguridad al editar (Lógica M6)
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $areaActividad = $seguimiento->actividad->area_intervencion_id;

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

        return view('seguimiento.edit', compact('seguimiento', 'actividades'));
    }

    public function update(Request $request, $id)
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $usuario = Auth::user();
        
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $areaActividad = $seguimiento->actividad->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($areaActividad, 'M6')) abort(403);
            } else {
                if ($areaActividad != $areaUsuario) abort(403);
            }
        }

        $seguimiento->update($request->all());
        return redirect()->route('seguimiento.index')->with('success', 'Actualizado correctamente.');
    }

    public function destroy($id)
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $usuario = Auth::user();
        
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            $areaActividad = $seguimiento->actividad->area_intervencion_id;

            if ($areaUsuario === 'M6') {
                if (!str_starts_with($areaActividad, 'M6')) abort(403);
            } else {
                if ($areaActividad != $areaUsuario) abort(403);
            }
        }

        $seguimiento->delete();
        return redirect()->route('seguimiento.index')->with('success', 'Eliminado.');
    }
}