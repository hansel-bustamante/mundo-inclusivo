<?php

namespace App\Http\Controllers;

use App\Models\AreaIntervencion;
use Illuminate\Http\Request;

class AreaIntervencionController extends Controller
{
    public function index()
    {
        return AreaIntervencion::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_area' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
        ]);

        $area = AreaIntervencion::create($request->all());

        return response()->json($area, 201);
    }

    public function show($id)
    {
        return AreaIntervencion::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $area = AreaIntervencion::findOrFail($id);

        $request->validate([
            'nombre_area' => 'sometimes|required|string|max:100',
            'municipio' => 'sometimes|required|string|max:100',
            'provincia' => 'sometimes|required|string|max:100',
            'departamento' => 'sometimes|required|string|max:100',
        ]);

        $area->update($request->all());

        return response()->json($area);
    }

    public function destroy($id)
    {
        $area = AreaIntervencion::findOrFail($id);
        $area->delete();

        return response()->json(null, 204);
    }
}
