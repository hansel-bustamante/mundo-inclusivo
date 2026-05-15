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
            'firma' => 'present', // 'present' asegura que el campo venga, aunque sea null o 0
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Convertimos explícitamente a entero (1 o 0) para evitar problemas con booleanos/strings
        // El truco: filter_var con FILTER_VALIDATE_BOOLEAN convierte "true", "1", "on" a true, y lo demás a false.
        $firmaValor = filter_var($request->firma, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        // Utiliza updateOrCreate para manejar tanto la creación como la actualización
        // Usamos DB::table o el modelo, pero forzando el valor
        $asistencia = AsistenciaSesion::updateOrCreate(
            [
                'id_sesion' => $request->id_sesion,
                'id_persona' => $request->id_persona,
            ],
            [
                'firma' => $firmaValor, 
                'observaciones' => $request->observaciones
            ]
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