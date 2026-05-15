<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use App\Models\FichaRegistro;
use App\Models\Persona;
use App\Models\AreaIntervencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class FichaBeneficiarioController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $usuario = Auth::user();

        $query = FichaRegistro::with(['beneficiario.persona', 'areaIntervencion']);

        // SEGURIDAD: Filtro por área (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                $query->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $query->where('area_intervencion_id', $usuario->area_intervencion_id);
            }
        }

        if ($busqueda) {
            $query->whereHas('beneficiario.persona', function($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('apellido_paterno', 'LIKE', "%{$busqueda}%")
                  ->orWhere('carnet_identidad', 'LIKE', "%{$busqueda}%");
            });
        }

        $fichas = $query->orderBy('fecha_registro', 'desc')->paginate(15);

        return view('ficha_beneficiario.index', compact('fichas', 'busqueda'));
    }

    public function create(Request $request)
    {
        $usuarioActual = Auth::user();

        // FILTRAR ÁREAS (Lógica M6)
        if ($usuarioActual->rol === 'admin') {
            $areas = AreaIntervencion::orderBy('nombre_area')->get();
        } else {
            if ($usuarioActual->area_intervencion_id === 'M6') {
                $areas = AreaIntervencion::where('codigo_area', 'LIKE', 'M6%')->get();
            } else {
                $areas = AreaIntervencion::where('codigo_area', $usuarioActual->area_intervencion_id)->get();
            }
        }

        $personaPreseleccionada = null;
        if ($request->has('id_persona')) {
            $personaPreseleccionada = Persona::find($request->id_persona);
        }
        
        return view('ficha_beneficiario.create', compact('areas', 'personaPreseleccionada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|integer|exists:PERSONA,id_persona|unique:BENEFICIARIO,id_persona',
            'tipo_discapacidad' => 'nullable|string|max:100',
            'fecha_registro' => 'required|date',
            'retraso_en_desarrollo' => 'required|boolean',
            'incluido_en_educacion_2025' => 'required|boolean',
            'area_intervencion_id' => 'required|exists:AREA_INTERVENCION,codigo_area',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $beneficiario = Beneficiario::create([
                    'id_persona' => $request->id_persona,
                    'tipo_discapacidad' => $request->tipo_discapacidad,
                ]);

                FichaRegistro::create([
                    'fecha_registro' => $request->fecha_registro,
                    'retraso_en_desarrollo' => $request->retraso_en_desarrollo,
                    'incluido_en_educacion_2025' => $request->incluido_en_educacion_2025,
                    'beneficiario_id' => $beneficiario->id_persona,
                    'usuario_id' => Auth::id() ?? 1,
                    'area_intervencion_id' => $request->area_intervencion_id,
                ]);
            });

            return redirect()->route('ficha_beneficiario.index')->with('success', 'Ficha registrada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ficha = FichaRegistro::with(['beneficiario.persona'])->findOrFail($id);
        $usuarioActual = Auth::user();

        // SEGURIDAD (Lógica M6)
        if ($usuarioActual->rol !== 'admin') {
            if ($usuarioActual->area_intervencion_id === 'M6') {
                if (!str_starts_with($ficha->area_intervencion_id, 'M6')) abort(403, 'No autorizado.');
            } else {
                if ($ficha->area_intervencion_id != $usuarioActual->area_intervencion_id) abort(403, 'No autorizado.');
            }
        }

        // FILTRAR ÁREAS (Lógica M6)
        if ($usuarioActual->rol === 'admin') {
            $areas = AreaIntervencion::orderBy('nombre_area')->get();
        } else {
            if ($usuarioActual->area_intervencion_id === 'M6') {
                $areas = AreaIntervencion::where('codigo_area', 'LIKE', 'M6%')->get();
            } else {
                $areas = AreaIntervencion::where('codigo_area', $usuarioActual->area_intervencion_id)->get();
            }
        }

        return view('ficha_beneficiario.edit', compact('ficha', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $ficha = FichaRegistro::findOrFail($id);
        
        $request->validate([
            'tipo_discapacidad' => 'nullable|string|max:100',
            'fecha_registro' => 'required|date',
            'area_intervencion_id' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request, $ficha) {
                $ficha->update($request->only(['fecha_registro', 'retraso_en_desarrollo', 'incluido_en_educacion_2025', 'area_intervencion_id']));
                $ficha->beneficiario->update(['tipo_discapacidad' => $request->tipo_discapacidad]);
            });
            return redirect()->route('ficha_beneficiario.index')->with('success', 'Actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ficha = FichaRegistro::findOrFail($id);
        $usuario = Auth::user();

        // SEGURIDAD (Lógica M6)
        if ($usuario->rol !== 'admin') {
            if ($usuario->area_intervencion_id === 'M6') {
                if (!str_starts_with($ficha->area_intervencion_id, 'M6')) abort(403, 'No autorizado.');
            } else {
                if ($ficha->area_intervencion_id != $usuario->area_intervencion_id) abort(403, 'No autorizado.');
            }
        }

        try {
            DB::transaction(function () use ($ficha) {
                $beneficiario = $ficha->beneficiario;
                $ficha->delete();
                if ($beneficiario) $beneficiario->delete();
            });
            return redirect()->route('ficha_beneficiario.index')->with('success', 'Eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function searchPersonas(Request $request)
    {
        try {
            $term = $request->query('q');
            $esVisitante = $request->query('es_visitante') === 'true'; 
            $user = Auth::user();

            if (empty($term)) return response()->json(['results' => []]);

            $query = Persona::query();

            if ($esVisitante || $user->rol === 'admin') {
                $query->where(function($q) use ($term) {
                    $q->where('carnet_identidad', 'LIKE', "{$term}%")
                      ->orWhere('nombre', 'LIKE', "%{$term}%")
                      ->orWhere('apellido_paterno', 'LIKE', "%{$term}%");
                });
            } 
            else {
                // MODO LOCAL (RESTRINGIDO - Lógica M6)
                if ($user->area_intervencion_id) {
                    if ($user->area_intervencion_id === 'M6') {
                        $query->where('area_intervencion_id', 'LIKE', 'M6%');
                    } else {
                        $query->where('area_intervencion_id', $user->area_intervencion_id);
                    }
                } else {
                    return response()->json(['results' => []]);
                }

                $query->where(function($q) use ($term) {
                    $q->where('nombre', 'LIKE', "%{$term}%")
                      ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                      ->orWhere('carnet_identidad', 'LIKE', "%{$term}%");
                });
            }

            $beneficiariosIds = Beneficiario::pluck('id_persona')->toArray();
            if (!empty($beneficiariosIds)) {
                $query->whereNotIn('id_persona', $beneficiariosIds);
            }

            $personas = $query->limit(20)->get();

            $results = $personas->map(function($persona) {
                return [
                    'id' => $persona->id_persona,
                    'text' => $persona->nombre . ' ' . $persona->apellido_paterno . ' (CI: ' . $persona->carnet_identidad . ')'
                ];
            });

            return response()->json(['results' => $results]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}