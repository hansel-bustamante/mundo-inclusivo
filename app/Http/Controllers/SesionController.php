<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Actividad; // Necesario para obtener la lista de FK
use Illuminate\Http\Request;
use App\Models\AsistenciaSesion;

class SesionController extends Controller
{
    /**
     * Muestra la lista de sesiones.
     */
    public function index()
    {
        $sesiones = Sesion::with('actividad')->get();
        return view('sesion.index', compact('sesiones'));
    }

    /**
     * Muestra el formulario para crear una nueva sesión.
     */
    public function create()
    {
        // Se necesitan todas las actividades para el campo select de la clave foránea
        $actividades = Actividad::all();
        return view('sesion.create', compact('actividades'));
    }

    /**
     * Almacena una nueva sesión.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nro_sesion' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tema' => 'required|string|max:150',
            // id_actividad es un INT (BIGINT unsigned)
            'id_actividad' => 'required|exists:actividad,id_actividad',
        ]);

        Sesion::create($request->all());

        return redirect()->route('sesion.index')->with('success', 'Sesión creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una sesión.
     */
    public function edit(Sesion $sesion)
    {
        $actividades = Actividad::all();
        return view('sesion.edit', compact('sesion', 'actividades'));
    }

    /**
     * Actualiza una sesión específica.
     */
    public function update(Request $request, Sesion $sesion)
    {
        $request->validate([
            'nro_sesion' => 'sometimes|required|integer|min:1',
            'fecha' => 'sometimes|required|date',
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fin' => 'sometimes|required|date_format:H:i|after:hora_inicio',
            'tema' => 'sometimes|required|string|max:150',
            'id_actividad' => 'sometimes|required|exists:actividad,id_actividad',
        ]);

        $sesion->update($request->all());

        return redirect()->route('sesion.index')->with('success', 'Sesión actualizada exitosamente.');
    }

    /**
     * Elimina una sesión específica.
     */
    public function destroy(Sesion $sesion)
    {
        $sesion->delete();
        return redirect()->route('sesion.index')->with('success', 'Sesión eliminada.');
    }

    public function editAsistencia(Sesion $sesion)
{
    // Cargar la actividad relacionada y sus participantes.
    $participantesActividad = $sesion->actividad->participantes;

    // Obtener todas las asistencias registradas para esta sesión específica
    $asistenciasRegistradas = AsistenciaSesion::where('id_sesion', $sesion->id_sesion)->get();

    return view('sesion.asistencia', [ // ¡Ruta corregida aquí!
        'sesion' => $sesion->load('actividad'),
        'participantesActividad' => $participantesActividad,
        'asistenciasRegistradas' => $asistenciasRegistradas,
    ]);
}
}