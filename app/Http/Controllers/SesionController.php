<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;

class SesionController extends Controller
{
    public function index()
    {
        return Sesion::with('actividad')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nro_sesion' => 'required|integer',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tema' => 'nullable|string|max:150',
            'id_actividad' => 'required|exists:actividad,id_actividad',
        ]);

        $sesion = Sesion::create($request->all());

        return response()->json($sesion, 201);
    }

    public function show($id)
    {
        return Sesion::with('actividad')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $sesion = Sesion::findOrFail($id);

        $request->validate([
            'nro_sesion' => 'sometimes|required|integer',
            'fecha' => 'sometimes|required|date',
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fin' => 'sometimes|required|date_format:H:i|after:hora_inicio',
            'tema' => 'nullable|string|max:150',
            'id_actividad' => 'sometimes|required|exists:actividad,id_actividad',
        ]);

        $sesion->update($request->all());

        return response()->json($sesion);
    }

    public function destroy($id)
    {
        $sesion = Sesion::findOrFail($id);
        $sesion->delete();

        return response()->json(null, 204);
    }
}
