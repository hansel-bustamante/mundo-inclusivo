<?php

namespace App\Http\Controllers;

use App\Models\CodigoActividad;
use Illuminate\Http\Request;

class CodigoActividadController extends Controller
{
    public function index()
    {
        return CodigoActividad::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_actividad' => 'required|string|size:2|unique:codigo_actividad,codigo_actividad',
            'nombre_actividad' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $actividad = CodigoActividad::create($request->all());

        return response()->json($actividad, 201);
    }

    public function show($id)
    {
        return CodigoActividad::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $actividad = CodigoActividad::findOrFail($id);

        $request->validate([
            'codigo_actividad' => 'string|size:2|unique:codigo_actividad,codigo_actividad,' . $id . ',codigo_actividad',
            'nombre_actividad' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $actividad->update($request->all());

        return response()->json($actividad);
    }

    public function destroy($id)
    {
        $actividad = CodigoActividad::findOrFail($id);
        $actividad->delete();

        return response()->json(null, 204);
    }
}

