<?php

namespace App\Http\Controllers;

use App\Models\FichaRegistro;
use Illuminate\Http\Request;

class FichaRegistroController extends Controller
{
    public function index()
    {
        return FichaRegistro::with(['beneficiario', 'usuario', 'areaIntervencion'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_registro' => 'required|date',
            'retraso_en_desarrollo' => 'required|boolean',
            'incluido_en_educacion_2025' => 'required|boolean',
            'beneficiario_id' => 'required|exists:beneficiario,id_persona',
            'usuario_id' => 'required|exists:usuario,id_persona',
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ]);

        $ficha = FichaRegistro::create($request->all());

        return response()->json($ficha, 201);
    }

    public function show($id)
    {
        return FichaRegistro::with(['beneficiario', 'usuario', 'areaIntervencion'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $ficha = FichaRegistro::findOrFail($id);

        $request->validate([
            'fecha_registro' => 'sometimes|required|date',
            'retraso_en_desarrollo' => 'sometimes|required|boolean',
            'incluido_en_educacion_2025' => 'sometimes|required|boolean',
            'beneficiario_id' => 'sometimes|required|exists:beneficiario,id_persona',
            'usuario_id' => 'sometimes|required|exists:usuario,id_persona',
            'area_intervencion_id' => 'sometimes|required|exists:area_intervencion,codigo_area',
        ]);

        $ficha->update($request->all());

        return response()->json($ficha);
    }

    public function destroy($id)
    {
        $ficha = FichaRegistro::findOrFail($id);
        $ficha->delete();

        return response()->json(null, 204);
    }
}
