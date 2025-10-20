<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use Illuminate\Http\Request;

class BeneficiarioController extends Controller
{
    public function index()
    {
        return Beneficiario::with('persona')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:beneficiario,id_persona',
            'tipo_discapacidad' => 'required|string|max:100',
        ]);

        $beneficiario = Beneficiario::create($request->all());

        return response()->json($beneficiario, 201);
    }

    public function show($id)
    {
        return Beneficiario::with('persona')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $beneficiario = Beneficiario::findOrFail($id);

        $request->validate([
            'tipo_discapacidad' => 'sometimes|required|string|max:100',
        ]);

        $beneficiario->update($request->all());

        return response()->json($beneficiario);
    }

    public function destroy($id)
    {
        $beneficiario = Beneficiario::findOrFail($id);
        $beneficiario->delete();

        return response()->json(null, 204);
    }
}
