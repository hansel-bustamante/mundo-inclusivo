<?php

namespace App\Http\Controllers;

use App\Models\Registra;
use Illuminate\Http\Request;

class RegistraController extends Controller
{
    public function index()
    {
        return Registra::with(['usuario', 'actividad'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:usuario,id_persona',
            'id_actividad' => 'required|exists:actividad,id_actividad',
            'fecha_registro' => 'required|date'
        ]);

        $registro = Registra::create($request->all());

        return response()->json($registro, 201);
    }

    public function show($id_persona)
    {
        return Registra::where('id_persona', $id_persona)->with(['usuario', 'actividad'])->get();
    }

    public function update(Request $request, $id_persona)
    {
        $request->validate([
            'id_actividad' => 'required|exists:actividad,id_actividad',
            'fecha_registro' => 'required|date'
        ]);

        $registro = Registra::where('id_persona', $id_persona)
            ->where('id_actividad', $request->id_actividad)
            ->firstOrFail();

        $registro->update($request->only('fecha_registro'));

        return response()->json($registro);
    }

    public function destroy(Request $request, $id_persona)
    {
        $request->validate([
            'id_actividad' => 'required|exists:actividad,id_actividad'
        ]);

        $registro = Registra::where('id_persona', $id_persona)
            ->where('id_actividad', $request->id_actividad)
            ->firstOrFail();

        $registro->delete();

        return response()->json(null, 204);
    }
}
