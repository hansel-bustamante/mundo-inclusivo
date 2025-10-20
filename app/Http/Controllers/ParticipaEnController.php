<?php

namespace App\Http\Controllers;

use App\Models\ParticipaEn;
use Illuminate\Http\Request;

class ParticipaEnController extends Controller
{
    public function index()
    {
        return ParticipaEn::with(['persona', 'actividad'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona',
            'id_actividad' => 'required|exists:actividad,id_actividad',
            'tiene_discapacidad' => 'required|boolean',
            'es_familiar' => 'required|boolean',
            'firma' => 'required|boolean',
        ]);

        // Para crear o actualizar (upsert), primero chequeamos si existe
        $participacion = ParticipaEn::updateOrCreate(
            [
                'id_persona' => $request->id_persona,
                'id_actividad' => $request->id_actividad,
            ],
            $request->only(['tiene_discapacidad', 'es_familiar', 'firma'])
        );

        return response()->json($participacion, 201);
    }

    public function show($id_persona, $id_actividad)
    {
        $participacion = ParticipaEn::where('id_persona', $id_persona)
            ->where('id_actividad', $id_actividad)
            ->with(['persona', 'actividad'])
            ->firstOrFail();

        return $participacion;
    }

    public function update(Request $request, $id_persona, $id_actividad)
    {
        $participacion = ParticipaEn::where('id_persona', $id_persona)
            ->where('id_actividad', $id_actividad)
            ->firstOrFail();

        $request->validate([
            'tiene_discapacidad' => 'sometimes|required|boolean',
            'es_familiar' => 'sometimes|required|boolean',
            'firma' => 'sometimes|required|boolean',
        ]);

        $participacion->update($request->all());

        return response()->json($participacion);
    }

    public function destroy($id_persona, $id_actividad)
    {
        $participacion = ParticipaEn::where('id_persona', $id_persona)
            ->where('id_actividad', $id_actividad)
            ->firstOrFail();

        $participacion->delete();

        return response()->json(null, 204);
    }
}
