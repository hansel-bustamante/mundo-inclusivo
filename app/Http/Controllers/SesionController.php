<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Actividad;
use App\Models\AsistenciaSesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $query = Sesion::with('actividad');

        // Seguridad (Lógica M6)
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

        // CAMBIO: Usar paginate() en lugar de get()
        $sesiones = $query->orderBy('fecha', 'desc')->paginate(15); // 15 items por página
        return view('sesion.index', compact('sesiones'));
    }

    public function indexPorActividad(Actividad $actividad)
    {
        $usuario = Auth::user();
        
        // Seguridad
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }

        // CAMBIO: Usar paginate() en lugar de get()
        $sesiones = $actividad->sesiones()->orderBy('nro_sesion', 'asc')->paginate(15);
        return view('sesion.index', compact('sesiones', 'actividad'));
    }

    public function create()
    {
        $usuario = Auth::user();
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
        return view('sesion.create', compact('actividades'));
    }

    public function createPorActividad(Actividad $actividad)
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }
        $maxNro = $actividad->sesiones()->max('nro_sesion');
        $nro_sesion_siguiente = $maxNro ? ($maxNro + 1) : 1;

        // 3. Pasamos la variable a la vista
        $actividades = collect([$actividad]); 
        return view('sesion.create', compact('actividades', 'actividad', 'nro_sesion_siguiente'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_actividad' => 'required|exists:actividad,id_actividad',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'tema' => 'nullable|string|max:150', // Ahora es opcional
            // 'nro_sesion' => 'nullable|integer', // Ya no lo pedimos, lo calculamos
        ]);

        // 1. Calcular el siguiente número de sesión para ESTA actividad
        // Buscamos el número más alto registrado actualmente. Si no hay, devuelve null (0).
        $maxNumero = Sesion::where('id_actividad', $request->id_actividad)->max('nro_sesion');
        $siguienteNumero = intval($maxNumero) + 1;

        // 2. Generar el Tema automáticamente si viene vacío
        $tema = $request->tema;
        if (empty($tema)) {
            $tema = 'Sesión N° ' . $siguienteNumero;
        }

        // 3. Crear la sesión
        Sesion::create([
            'id_actividad' => $request->id_actividad,
            'tema' => $tema,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'nro_sesion' => $siguienteNumero // Guardamos el número calculado
        ]);

        return redirect()->route('sesion.por_actividad', $request->id_actividad)
            ->with('success', 'Sesión N° ' . $siguienteNumero . ' creada correctamente.');
    }

    public function edit(Sesion $sesion)
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'admin') {
            $areaUsuario = $usuario->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                if (!str_starts_with($sesion->actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($sesion->actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }
        
        if ($usuario->rol === 'admin') {
            $actividades = Actividad::all();
        } else {
            if ($usuario->area_intervencion_id === 'M6') {
                $actividades = Actividad::where('area_intervencion_id', 'LIKE', 'M6%')->get();
            } else {
                $actividades = Actividad::where('area_intervencion_id', $usuario->area_intervencion_id)->get();
            }
        }

        return view('sesion.edit', compact('sesion', 'actividades'));
    }

    public function update(Request $request, Sesion $sesion)
    {
        if (Auth::user()->rol !== 'admin') {
            $areaUsuario = Auth::user()->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                if (!str_starts_with($sesion->actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($sesion->actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }
        $sesion->update($request->all());
        return redirect()->route('sesion.por_actividad', $sesion->id_actividad)->with('success', 'Actualizado.');
    }

    public function destroy(Sesion $sesion)
    {
        if (Auth::user()->rol !== 'admin') {
            $areaUsuario = Auth::user()->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                if (!str_starts_with($sesion->actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($sesion->actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }
        $idActividad = $sesion->id_actividad;
        $sesion->delete();
        return redirect()->route('sesion.por_actividad', $idActividad)->with('success', 'Eliminado.');
    }

    public function editAsistencia(Sesion $sesion)
    {
        if (Auth::user()->rol !== 'admin') {
            $areaUsuario = Auth::user()->area_intervencion_id;
            if ($areaUsuario === 'M6') {
                if (!str_starts_with($sesion->actividad->area_intervencion_id, 'M6')) abort(403);
            } else {
                if ($sesion->actividad->area_intervencion_id != $areaUsuario) abort(403);
            }
        }

        $participantesActividad = $sesion->actividad->participantes()->orderBy('apellido_paterno')->get();
        $asistenciasRegistradas = AsistenciaSesion::where('id_sesion', $sesion->id_sesion)->get()->keyBy('id_persona');

        return view('sesion.asistencia', compact('sesion', 'participantesActividad', 'asistenciasRegistradas'));
    }
}