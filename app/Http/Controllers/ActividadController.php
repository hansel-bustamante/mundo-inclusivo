<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        return Actividad::with(['codigoActividad', 'areaIntervencion'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'codigo_actividad_id' => 'required|string|size:2|exists:codigo_actividad,codigo_actividad',
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ]);

        $actividad = Actividad::create($request->all());

        return response()->json($actividad, 201);
    }

    public function show($id)
    {
        return Actividad::with(['codigoActividad', 'areaIntervencion'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'fecha' => 'sometimes|required|date',
            'lugar' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'codigo_actividad_id' => 'sometimes|required|string|size:2|exists:codigo_actividad,codigo_actividad',
            'area_intervencion_id' => 'sometimes|required|exists:area_intervencion,codigo_area',
        ]);

        $actividad->update($request->all());

        return response()->json($actividad);
    }

    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();

        return response()->json(null, 204);
    }
}
