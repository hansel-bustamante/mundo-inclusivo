<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaSesion;
use Illuminate\Http\Request;

class AsistenciaSesionController extends Controller
{
    // Método para registrar/actualizar la asistencia
    public function store(Request $request)
    {
        $request->validate([
            'id_sesion' => 'required|exists:SESION,id_sesion',
            'id_persona' => 'required|exists:PERSONA,id_persona',
            'firma' => 'required|boolean',
            'observaciones' => 'nullable|string',
        ]);

        try {
            // Usa updateOrCreate para registrar la asistencia o actualizarla si ya existe
            $asistencia = AsistenciaSesion::updateOrCreate(
                [
                    'id_sesion' => $request->id_sesion,
                    'id_persona' => $request->id_persona,
                ],
                $request->only(['firma', 'observaciones'])
            );

            return response()->json($asistencia, 201);
        } catch (\Exception $e) {
            \Log::error('Error al guardar asistencia a sesión: ' . $e->getMessage());
            return response()->json(['message' => 'Error de servidor al guardar la asistencia.'], 500);
        }
    }

    // Método para eliminar la asistencia (clave compuesta)
    public function destroy($id_sesion, $id_persona)
    {
        try {
            // 1. Buscar el registro pivote
            $asistencia = AsistenciaSesion::where('id_sesion', $id_sesion)
                ->where('id_persona', $id_persona)
                ->first();

            if (!$asistencia) {
                return response()->json(['message' => 'Asistencia no encontrada.'], 404);
            }

            // 2. Eliminar el registro (funciona gracias al método delete() en el modelo)
            $asistencia->delete();

            // 3. Respuesta exitosa sin contenido
            return response()->json(null, 204);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar asistencia a sesión: ' . $e->getMessage());
            return response()->json(['message' => 'Error de servidor al eliminar la asistencia.'], 500);
        }
    }
}