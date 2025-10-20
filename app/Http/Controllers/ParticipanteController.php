<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index()
    {
        return Participante::with(['persona', 'institucion'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:participante,id_persona',
            'id_institucion' => 'required|exists:institucion,id_institucion',
        ]);

        $participante = Participante::create($request->all());

        return response()->json($participante, 201);
    }

    public function show($id)
    {
        return Participante::with(['persona', 'institucion'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $participante = Participante::findOrFail($id);

        $request->validate([
            'id_institucion' => 'sometimes|required|exists:institucion,id_institucion',
        ]);

        $participante->update($request->all());

        return response()->json($participante);
    }

    public function destroy($id)
    {
        $participante = Participante::findOrFail($id);
        $participante->delete();

        return response()->json(null, 204);
    }
}
