<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        return Evaluacion::with(['actividad', 'usuario'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
            'resultado' => 'required|in:Cumplido,No cumplido,Parcial',
            'ponderacion' => 'nullable|numeric',
            'nivel_aceptacion' => 'nullable|numeric',
            'expectativa_cumplida' => 'boolean',
            'actividades_no_cumplidas' => 'nullable|string',
            'actividad_id' => 'required|exists:actividad,id_actividad',
            'usuario_id' => 'required|exists:usuario,id_persona',
        ]);

        $evaluacion = Evaluacion::create($request->all());

        return response()->json($evaluacion, 201);
    }

    public function show($id)
    {
        return Evaluacion::with(['actividad', 'usuario'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        $request->validate([
            'fecha' => 'sometimes|required|date',
            'descripcion' => 'nullable|string',
            'resultado' => 'sometimes|required|in:Cumplido,No cumplido,Parcial',
            'ponderacion' => 'nullable|numeric',
            'nivel_aceptacion' => 'nullable|numeric',
            'expectativa_cumplida' => 'boolean',
            'actividades_no_cumplidas' => 'nullable|string',
            'actividad_id' => 'sometimes|required|exists:actividad,id_actividad',
            'usuario_id' => 'sometimes|required|exists:usuario,id_persona',
        ]);

        $evaluacion->update($request->all());

        return response()->json($evaluacion);
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return response()->json(null, 204);
    }
}
