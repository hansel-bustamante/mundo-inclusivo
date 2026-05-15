<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Persona;
use App\Models\CodigoActividad;
use App\Models\AreaIntervencion;
use App\Models\Institucion;
use App\Models\Participante;
use App\Models\ParticipaEn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    /**
     * Muestra la lista de actividades.
     * FILTRO: Si es registrador, solo ve actividades de su área.
     */
    public function index()
    {
        $usuario = Auth::user();
        
        $query = Actividad::with(['areaIntervencion', 'codigoActividad', 'evaluacion'])
                          ->withCount('participantes');

        // SEGURIDAD: Filtro por área (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                $query->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $query->where('area_intervencion_id', $usuario->area_intervencion_id);
            }
        }

        $actividades = $query->orderBy('fecha', 'desc')->get();

        return view('actividad.index', compact('actividades'));
    }

    public function create()
    {
        $usuario = Auth::user();
        $codigos = CodigoActividad::all();
        
        // FILTRO DE ÁREAS EN EL SELECT (Lógica M6)
        if ($usuario->rol === 'admin') {
            $areas = AreaIntervencion::all();
        } else {
            if ($usuario->area_intervencion_id === 'M6') {
                // M6 puede elegir cualquier sub-área (M6A, M6B...)
                $areas = AreaIntervencion::where('codigo_area', 'LIKE', 'M6%')->get();
            } else {
                // Otros solo ven su propia área
                $areas = AreaIntervencion::where('codigo_area', $usuario->area_intervencion_id)->get();
            }
        }

        return view('actividad.create', compact('codigos', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'codigo_actividad_id' => 'required|exists:codigo_actividad,codigo_actividad',
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ]);

        $data = $request->all();

        // SEGURIDAD: Forzar el área si no es admin (Lógica M6)
        if (Auth::user()->rol !== 'admin') {
            // Si es M6, permitimos que guarde lo que seleccionó (porque pudo elegir M6A, M6B...)
            // Si NO es M6, forzamos su área para evitar hackeos.
            if (Auth::user()->area_intervencion_id !== 'M6') {
                $data['area_intervencion_id'] = Auth::user()->area_intervencion_id;
            }
        }

        Actividad::create($data);

        return redirect()->route('actividad.index')->with('success', 'Actividad creada exitosamente.');
    }

    public function edit($id)
    {
        $actividad = Actividad::findOrFail($id);
        $usuario = Auth::user();

        // SEGURIDAD (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                // Si soy M6, puedo editar si la actividad empieza con M6
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) {
                    abort(403, 'No tiene permiso para editar esta actividad.');
                }
            } else {
                // Si soy otro, coincidencia exacta
                if ($actividad->area_intervencion_id != $usuario->area_intervencion_id) {
                    abort(403, 'No tiene permiso para editar esta actividad.');
                }
            }
        }

        $codigos = CodigoActividad::all();
        
        // Select de áreas (Lógica M6)
        if ($usuario->rol === 'admin') {
            $areas = AreaIntervencion::all();
        } else {
            if ($usuario->area_intervencion_id === 'M6') {
                $areas = AreaIntervencion::where('codigo_area', 'LIKE', 'M6%')->get();
            } else {
                $areas = AreaIntervencion::where('codigo_area', $usuario->area_intervencion_id)->get();
            }
        }
        
        return view('actividad.edit', compact('actividad', 'codigos', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);
        $usuario = Auth::user();

        // SEGURIDAD (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403, 'No autorizado.');
            } else {
                if ($actividad->area_intervencion_id != $usuario->area_intervencion_id) abort(403, 'No autorizado.');
            }
        }

        $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:100',
            'codigo_actividad_id' => 'required|exists:codigo_actividad,codigo_actividad',
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ]);

        $data = $request->all();
        
        // PROTECCIÓN DE ÁREA AL ACTUALIZAR
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id !== 'M6') {
                $data['area_intervencion_id'] = $usuario->area_intervencion_id; 
            }
            // Si es M6, dejamos pasar el área seleccionada (validada por el exists)
        }

        $actividad->update($data);

        return redirect()->route('actividad.index')->with('success', 'Actividad actualizada.');
    }

    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $usuario = Auth::user();

        // SEGURIDAD (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403, 'No autorizado.');
            } else {
                if ($actividad->area_intervencion_id != $usuario->area_intervencion_id) abort(403, 'No autorizado.');
            }
        }

        $actividad->delete();

        return redirect()->route('actividad.index')->with('success', 'Actividad eliminada.');
    }
    
    /**
     * Muestra el formulario para gestionar participantes.
     */
    public function editParticipantes(Actividad $actividad)
    {
        $usuario = Auth::user();
        
        // Seguridad (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                if (!str_starts_with($actividad->area_intervencion_id, 'M6')) abort(403, 'No autorizado.');
            } else {
                if ($actividad->area_intervencion_id != $usuario->area_intervencion_id) abort(403, 'No autorizado.');
            }
        }

        $participantesActuales = $actividad->participantes()->get();
        $instituciones = Institucion::all();

        return view('actividad.participantes', compact('actividad', 'participantesActuales', 'instituciones'));
    }

    /**
     * API EXCLUSIVA para buscar personas en el módulo de Participantes.
     */
    public function searchParticipantes(Request $request)
    {
        try {
            $term = $request->query('q'); 
            $esVisitante = $request->query('es_visitante') === 'true'; 
            $user = \Illuminate\Support\Facades\Auth::user();

            if (empty($term)) return response()->json(['results' => []]);

            $query = Persona::query();

            // --- LÓGICA DE FILTRADO ---

            if ($esVisitante) {
                // CASO 1: VISITANTE (Búsqueda GLOBAL por C.I. EXACTO)
                $query->where('carnet_identidad', $term); 
                
            } else {
                // CASO 2: LOCAL (Lógica M6)
                if ($user->rol !== 'admin') {
                    if ($user->area_intervencion_id) {
                        
                        // --- APLICACIÓN LÓGICA M6 ---
                        if ($user->area_intervencion_id === 'M6') {
                            $query->where('area_intervencion_id', 'LIKE', 'M6%');
                        } else {
                            $query->where('area_intervencion_id', $user->area_intervencion_id);
                        }
                        // ---------------------------

                    } else {
                        return response()->json(['results' => []]); 
                    }
                }

                $query->where(function($q) use ($term) {
                    $q->where('nombre', 'LIKE', "%{$term}%")
                      ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                      ->orWhere('carnet_identidad', 'LIKE', "%{$term}%");
                });
            }

            $personas = $query->limit(20)->get();

            $results = $personas->map(function($persona) use ($user) {
                
                $participante = \App\Models\Participante::where('id_persona', $persona->id_persona)
                                ->with('institucion')
                                ->first(); 

                $nombreInstitucion = $participante && $participante->institucion 
                                     ? $participante->institucion->nombre_institucion 
                                     : null;
                $idInstitucion = $participante ? $participante->id_institucion : null;

                $extra = "";
                if ($user->area_intervencion_id && $persona->area_intervencion_id != $user->area_intervencion_id) {
                     $extra = " (VISITANTE)";
                }

                return [
                    'id' => $persona->id_persona,
                    'text' => $persona->nombre . ' ' . $persona->apellido_paterno . ' (' . $persona->carnet_identidad . ')' . $extra,
                    'ci' => $persona->carnet_identidad,
                    'institucion_id' => $idInstitucion,
                    'institucion_nombre' => $nombreInstitucion
                ];
            });

            return response()->json(['results' => $results]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function addParticipante(Request $request, $id)
    {
        return back(); 
    }
}