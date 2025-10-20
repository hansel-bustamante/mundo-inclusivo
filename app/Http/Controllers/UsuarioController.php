<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return Usuario::with(['persona', 'areaIntervencion'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona|unique:usuario,id_persona',
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario',
            'contrasena' => 'required|string|min:6',
            'rol' => 'required|in:admin,registrador,coordinador',
            'correo' => 'nullable|email|max:100',
            'area_intervencion_id' => 'nullable|exists:area_intervencion,codigo_area',
        ]);

        $usuario = Usuario::create($request->all());

        return response()->json($usuario, 201);
    }

    public function show($id)
    {
        return Usuario::with(['persona', 'areaIntervencion'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre_usuario' => 'sometimes|required|string|max:50|unique:usuario,nombre_usuario,' . $id . ',id_persona',
            'contrasena' => 'sometimes|required|string|min:6',
            'rol' => 'sometimes|required|in:admin,registrador,coordinador',
            'correo' => 'nullable|email|max:100',
            'area_intervencion_id' => 'nullable|exists:area_intervencion,codigo_area',
        ]);

        $data = $request->all();

        // Si contraseña está vacía no actualizarla
        if (empty($data['contrasena'])) {
            unset($data['contrasena']);
        }

        $usuario->update($data);

        return response()->json($usuario);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(null, 204);
    }
}
