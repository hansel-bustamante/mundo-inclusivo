<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\AreaIntervencion;
use App\Models\CodigoActividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    /**
     * Muestra la lista de actividades.
     */
    public function index()
    {
        // Carga todas las actividades con sus relaciones para mostrarlas en la tabla
        $actividades = Actividad::with(['codigoActividad', 'areaIntervencion'])->get();
        return view('actividad.index', compact('actividades'));
    }

    /**
     * Muestra el formulario para crear una nueva actividad.
     */
    public function create()
    {
        // Necesitamos los datos de los catálogos para los campos select
        $areas = AreaIntervencion::all();
        $codigos = CodigoActividad::all();

        return view('actividad.create', compact('areas', 'codigos'));
    }

    /**
     * Almacena una nueva actividad en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            // El código es un CHAR(2), se valida como string.
            'codigo_actividad_id' => 'required|string|min:1|max:2|exists:codigo_actividad,codigo_actividad',
            
            // CORREGIDO: Quitamos 'numeric' para evitar el error de validación, 
            // ya que la columna es VARCHAR(20). Solo verificamos que exista.
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area', 
        ]);

        Actividad::create($request->all());

        // Redirige a la lista
        return redirect()->route('actividad.index')->with('success', 'Actividad creada exitosamente.');
    }

    // El método show(Actividad $actividad) no se usa en este CRUD web
    public function show(Actividad $actividad)
    {
        return redirect()->route('actividad.index');
    }

    /**
     * Muestra el formulario para editar una actividad específica.
     */
    public function edit(Actividad $actividad)
    {
        $areas = AreaIntervencion::all();
        $codigos = CodigoActividad::all();

        return view('actividad.edit', compact('actividad', 'areas', 'codigos'));
    }

    /**
     * Actualiza una actividad específica en la base de datos.
     */
    public function update(Request $request, Actividad $actividad)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'fecha' => 'sometimes|required|date',
            'lugar' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'codigo_actividad_id' => 'sometimes|required|string|min:1|max:2|exists:codigo_actividad,codigo_actividad',
            
            // CORREGIDO: Quitamos 'numeric' para evitar el error de validación.
            'area_intervencion_id' => 'sometimes|required|exists:area_intervencion,codigo_area',
        ]);

        $actividad->update($request->all());

        return redirect()->route('actividad.index')->with('success', 'Actividad actualizada exitosamente.');
    }

    /**
     * Elimina una actividad específica de la base de datos.
     */
    public function destroy(Actividad $actividad)
    {
        $actividad->delete();

        return redirect()->route('actividad.index')->with('success', 'Actividad eliminada exitosamente.');
    }
}