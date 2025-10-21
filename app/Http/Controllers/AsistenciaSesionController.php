<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaSesion;
use Illuminate\Http\Request;

class AsistenciaSesionController extends Controller
{
    /**
     * Guarda o actualiza un registro de asistencia.
     * Endpoint: POST /api/asistencia-sesion
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sesion' => 'required|exists:sesion,id_sesion',
            'id_persona' => 'required|exists:persona,id_persona',
            'firma' => 'sometimes|boolean',
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Utiliza updateOrCreate para manejar tanto la creación como la actualización
        $asistencia = AsistenciaSesion::updateOrCreate(
            [
                'id_sesion' => $request->id_sesion,
                'id_persona' => $request->id_persona,
            ],
            $request->only(['firma', 'observaciones'])
        );

        return response()->json($asistencia, 200);
    }

    /**
     * Elimina un registro de asistencia (clave compuesta).
     * Endpoint: DELETE /api/asistencia-sesion/{id_sesion}/{id_persona}
     */
    public function destroy($id_sesion, $id_persona)
    {
        // 1. Buscar el registro usando ambas claves
        $asistencia = AsistenciaSesion::where('id_sesion', $id_sesion)
            ->where('id_persona', $id_persona)
            ->first();

        if (!$asistencia) {
            // Si el registro no existe, devolvemos un 404
            return response()->json(['message' => 'El registro de asistencia para la persona '.$id_persona.' en la sesión '.$id_sesion.' no fue encontrado.'], 404);
        }

        // 2. Eliminar el registro
        $asistencia->delete();

        // 3. Devolver una respuesta exitosa sin contenido (204)
        return response()->json(null, 204);
    }
}