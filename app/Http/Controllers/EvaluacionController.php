<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    /**
     * Muestra una lista de todas las evaluaciones.
     */
    public function index()
    {
        // Cargamos las evaluaciones junto con la actividad asociada y el usuario evaluador
        $evaluaciones = Evaluacion::with(['actividad', 'usuario'])->get();
        return view('evaluacion.index', compact('evaluaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva evaluación.
     */
    public function create(Request $request)
    {
        // Obtenemos solo las actividades que aún no han sido evaluadas
        $actividadesConEvaluacion = Evaluacion::pluck('actividad_id');
        $actividades = Actividad::whereNotIn('id_actividad', $actividadesConEvaluacion)
                                ->orderBy('fecha', 'desc')->get();
        
        // Obtenemos el ID de la actividad si viene de la URL (al pulsar "Evaluar" desde el listado de Actividades)
        $actividad_id = $request->get('actividad_id');

        // Si el usuario intentó ir a evaluar una actividad ya evaluada, redirigimos
        if ($actividad_id && $actividadesConEvaluacion->contains($actividad_id)) {
             return redirect()->route('actividad.index')->with('error', 'La actividad seleccionada ya cuenta con una evaluación registrada.');
        }

        return view('evaluacion.create', compact('actividades', 'actividad_id'));
    }

    /**
     * Almacena una nueva evaluación en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
            'resultado' => 'required|in:Cumplido,No cumplido,Parcial', 
            'ponderacion' => 'nullable|numeric|between:0,5.00',
            'nivel_aceptacion' => 'nullable|numeric|between:0,100.00',
            'expectativa_cumplida' => 'nullable|boolean',
            'actividades_no_cumplidas' => 'nullable|string',
            'actividad_id' => 'required|exists:actividad,id_actividad|unique:evaluacion,actividad_id', // CRÍTICO: UNIQUE
            'usuario_id' => 'required|exists:usuario,id_persona', 
        ]);

        $data = $request->all();
        // El checkbox no se envía si no está marcado, forzamos 0 si no existe el campo
        $data['expectativa_cumplida'] = $request->has('expectativa_cumplida') ? 1 : 0;
        
        Evaluacion::create($data);

        return redirect()->route('evaluacion.index')->with('success', 'Evaluación registrada con éxito.');
    }

    /**
     * Muestra el formulario para editar una evaluación existente.
     */
    public function edit($id)
    {
        $evaluacion = Evaluacion::with('actividad')->findOrFail($id);
        // Para el formulario de edición, pasamos solo la actividad evaluada para que el select la muestre
        $actividades = Actividad::where('id_actividad', $evaluacion->actividad_id)->get();
        
        return view('evaluacion.edit', compact('evaluacion', 'actividades'));
    }

    /**
     * Actualiza una evaluación en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        $request->validate([
            'fecha' => 'sometimes|required|date',
            'descripcion' => 'nullable|string',
            'resultado' => 'sometimes|required|in:Cumplido,No cumplido,Parcial',
            'ponderacion' => 'nullable|numeric|between:0,5.00',
            'nivel_aceptacion' => 'nullable|numeric|between:0,100.00',
            'expectativa_cumplida' => 'nullable|boolean',
            'actividades_no_cumplidas' => 'nullable|string',
            'usuario_id' => 'sometimes|required|exists:usuario,id_persona', 
        ]);
        
        $data = $request->all();
        $data['expectativa_cumplida'] = $request->has('expectativa_cumplida') ? 1 : 0;

        $evaluacion->update($data);

        return redirect()->route('evaluacion.index')->with('success', 'Evaluación actualizada con éxito.');
    }

    /**
     * Elimina una evaluación de la base de datos.
     */
    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return redirect()->route('evaluacion.index')->with('success', 'Evaluación eliminada con éxito.');
    }
}