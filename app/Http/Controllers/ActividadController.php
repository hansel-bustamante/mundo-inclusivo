<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Persona;
use App\Models\CodigoActividad; // <- AÑADIDO
use App\Models\AreaIntervencion; // <- AÑADIDO
use Illuminate\Http\Request;
use App\Models\Institucion;  // <-- ¡ESTA ES LA LÍNEA FALTANTE!

class ActividadController extends Controller
{
    public function index()
    {
        // Añadimos withCount para obtener el número de participantes de forma eficiente
        $actividades = Actividad::with(['codigoActividad', 'areaIntervencion'])
                            ->withCount('participantes') 
                            ->get();

        return view('actividad.index', compact('actividades'));
    }

    /**
     * Muestra el formulario para crear una nueva actividad. (FALTANTE)
     */
    public function create()
    {
        // Se asume que estos modelos existen para cargar los selectores en la vista de formulario.
        $codigos = CodigoActividad::all();
        $areas = AreaIntervencion::all();
        return view('actividad.create', compact('codigos', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'codigo_actividad_id' => 'required|string|min:1|max:2|exists:codigo_actividad,codigo_actividad',
            
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ]);

        $actividad = Actividad::create($request->all());

        // Después de la creación, lo más probable es que quieras redirigir a la lista
        return redirect()->route('actividad.index')->with('success', 'Actividad registrada exitosamente.');
    }

    public function show($id)
    {
        return Actividad::with(['codigoActividad', 'areaIntervencion'])->findOrFail($id);
    }

    /**
     * Muestra el formulario para editar una actividad existente. (FALTANTE)
     */
    public function edit(Actividad $actividad)
    {
        $codigos = CodigoActividad::all();
        $areas = AreaIntervencion::all();
        return view('actividad.edit', compact('actividad', 'codigos', 'areas'));
    }


    public function update(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'fecha' => 'sometimes|required|date',
            'lugar' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'codigo_actividad_id' => 'required|string|min:1|max:2|exists:codigo_actividad,codigo_actividad',
            
            'area_intervencion_id' => 'sometimes|required|exists:area_intervencion,codigo_area',
        ]);

        $actividad->update($request->all());

        // Tras la actualización, redirigir
        return redirect()->route('actividad.index')->with('success', 'Actividad actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividad.index')->with('success', 'Actividad eliminada exitosamente.');
    }
    
    /**
     * Muestra el formulario para gestionar los participantes de una actividad (UI Layer).
     */
public function editParticipantes(Actividad $actividad)
{
    // ...
    $participantesActuales = $actividad->participantes()->get();
    $personasDisponibles = Persona::whereDoesntHave('usuario')->orderBy('apellido_paterno')->get();
    
    // ** CRÍTICO **: Obtener instituciones
    $instituciones = Institucion::all(); 

    return view('actividad.participantes', compact('actividad', 'participantesActuales', 'personasDisponibles', 'instituciones'));
}
}