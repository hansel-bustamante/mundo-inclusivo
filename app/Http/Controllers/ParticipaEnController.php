<?php

namespace App\Http\Controllers;

use App\Models\ParticipaEn;
use App\Models\Participante; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; // Asegúrate de que Log esté importado

class ParticipaEnController extends Controller
{
    // Método index, si existe...

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:PERSONA,id_persona',
            'id_actividad' => 'required|exists:ACTIVIDAD,id_actividad',
            // 🚨 CAMBIO CRÍTICO: Permitir valor nulo (opcional)
            'id_institucion' => 'nullable|exists:INSTITUCION,id_institucion', 
            'tiene_discapacidad' => 'required|boolean',
            'es_familiar' => 'required|boolean',
            'firma' => 'required|boolean',
        ]);

        try {
            // ** CRÍTICO **: Usar una transacción para guardar en ambas tablas
            $participacion = DB::transaction(function () use ($request) {
                
                // Si no se envía el ID de institución, se guarda NULL.
                // Esto es clave para permitir participantes sin institución.
                $institucion_id = $request->id_institucion ?? null;

                // 1. Guardar/Actualizar la Afiliación a la Institución (Tabla PARTICIPANTE)
                Participante::updateOrCreate(
                    ['id_persona' => $request->id_persona],
                    ['id_institucion' => $institucion_id] 
                );
                
                // 2. Guardar/Actualizar la Participación en la Actividad (Tabla PARTICIPA_EN)
                $data = $request->only([
                    'id_persona', 
                    'id_actividad', 
                    'tiene_discapacidad', 
                    'es_familiar', 
                    'firma'
                ]);
                
                $participacion = ParticipaEn::updateOrCreate(
                    ['id_persona' => $data['id_persona'], 'id_actividad' => $data['id_actividad']],
                    $data
                );
                
                return $participacion;
            });
            
            return response()->json($participacion, 201);
        } catch (\Exception $e) {
            Log::error('Error al guardar participación: ' . $e->getMessage());
            return response()->json(['message' => 'Error de servidor al registrar la participación.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id_persona, $id_actividad)
    {
        // 🚨 CAMBIO CRÍTICO: Permitir nulo y que el campo solo esté presente a veces
        $request->validate([
            'id_institucion' => 'nullable|sometimes|exists:INSTITUCION,id_institucion',
            'tiene_discapacidad' => 'sometimes|boolean',
            'es_familiar' => 'sometimes|boolean',
            'firma' => 'sometimes|boolean',
        ]);

        try {
            $participacion = DB::transaction(function () use ($request, $id_persona, $id_actividad) {
                
                // 1. Encontrar la participación actual
                $participacion = ParticipaEn::where('id_persona', $id_persona)
                    ->where('id_actividad', $id_actividad)
                    ->firstOrFail();

                // 2. Actualizar Afiliación (si se envía el ID de institución)
                if ($request->has('id_institucion')) {
                    // Si el valor es una cadena vacía (del frontend sin selección), se convierte a NULL
                    // Si es '0' o valor vacío, lo tratamos como NULL para desvincular
                    $institucion_id = empty($request->id_institucion) || $request->id_institucion === '0' 
                                      ? null 
                                      : $request->id_institucion;

                    Participante::updateOrCreate(
                        ['id_persona' => $id_persona],
                        ['id_institucion' => $institucion_id]
                    );
                }
                
                // 3. Actualizar Participación
                $participacion->update($request->only(['tiene_discapacidad', 'es_familiar', 'firma'])); 
                
                return $participacion;
            });
            return response()->json($participacion);

        } catch (\Exception $e) {
            Log::error('Error al actualizar participación: ' . $e->getMessage()); 
            return response()->json(['message' => 'Error de servidor al actualizar la participación.'], 500);
        }
    }

    public function destroy($id_persona, $id_actividad)
    {
        try {
            $participa = ParticipaEn::where('id_persona', $id_persona)
                ->where('id_actividad', $id_actividad)
                ->first();

            if (!$participa) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            $participa->delete();
            return response()->json(['message' => 'Eliminado correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar participación: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}