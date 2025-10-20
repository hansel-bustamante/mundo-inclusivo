<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
    {
        return Seguimiento::with('actividad')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'actividad_id' => 'required|exists:actividad,id_actividad',
        ]);

        $seguimiento = Seguimiento::create($request->all());

        return response()->json($seguimiento, 201);
    }

    public function show($id)
    {
        return Seguimiento::with('actividad')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $seguimiento = Seguimiento::findOrFail($id);

        $request->validate([
            'fecha' => 'sometimes|required|date',
            'tipo' => 'sometimes|required|string|max:50',
            'observaciones' => 'nullable|string',
            'actividad_id' => 'sometimes|required|exists:actividad,id_actividad',
        ]);

        $seguimiento->update($request->all());

        return response()->json($seguimiento);
    }

    public function destroy($id)
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $seguimiento->delete();

        return response()->json(null, 204);
    }
}
