<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    public function index()
    {
        return Institucion::all();
    }

    public function store(Request $request)
    {
        $institucion = Institucion::create($request->all());
        return response()->json($institucion, 201);
    }

    public function show($id)
    {
        return Institucion::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $institucion = Institucion::findOrFail($id);
        $institucion->update($request->all());
        return response()->json($institucion, 200);
    }

    public function destroy($id)
    {
        $institucion = Institucion::findOrFail($id);
        $institucion->delete();
        return response()->json(null, 204);
    }
}
