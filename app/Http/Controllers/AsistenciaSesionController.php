<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaSesion;
use Illuminate\Http\Request;

class AsistenciaSesionController extends Controller
{
    public function index()
    {
        return AsistenciaSesion::with(['sesion', 'persona'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sesion' => 'required|exists:sesion,id_sesion',
            'id_persona' => 'required|exists:persona,id_persona',
            'firma' => 'required|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $asistencia = AsistenciaSesion::create($request->all());

        return response()->json($asistencia, 201);
    }

    public function show($id_sesion)
    {
        return AsistenciaSesion::where('id_sesion', $id_sesion)->with(['sesion', 'persona'])->get();
    }

    public function update(Request $request, $id_sesion)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona',
            'firma' => 'sometimes|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $asistencia = AsistenciaSesion::where('id_sesion', $id_sesion)
            ->where('id_persona', $request->id_persona)
            ->firstOrFail();

        $asistencia->update($request->only('firma', 'observaciones'));

        return response()->json($asistencia);
    }

    public function destroy(Request $request, $id_sesion)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona',
        ]);

        $asistencia = AsistenciaSesion::where('id_sesion', $id_sesion)
            ->where('id_persona', $request->id_persona)
            ->firstOrFail();

        $asistencia->delete();

        return response()->json(null, 204);
    }
}
