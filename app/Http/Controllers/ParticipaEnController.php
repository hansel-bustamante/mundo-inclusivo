<?php

namespace App\Http\Controllers;

use App\Models\ParticipaEn;
use App\Models\Participante; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class ParticipaEnController extends Controller
{
    // Método index, si existe...

    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:PERSONA,id_persona',
            'id_actividad' => 'required|exists:ACTIVIDAD,id_actividad',
            'id_institucion' => 'required|exists:INSTITUCION,id_institucion', 
            'tiene_discapacidad' => 'required|boolean',
            'es_familiar' => 'required|boolean',
            'firma' => 'required|boolean',
        ]);

        try {
            // ** CRÍTICO **: Usar una transacción para guardar en ambas tablas
            $participacion = DB::transaction(function () use ($request) {

                // 1. Guardar/Actualizar la Afiliación a la Institución (Tabla PARTICIPANTE)
                Participante::updateOrCreate(
                    ['id_persona' => $request->id_persona],
                    ['id_institucion' => $request->id_institucion]
                );
                
                // 2. Guardar/Actualizar la Participación en la Actividad (Tabla PARTICIPA_EN)
                $data = $request->only([
                    'id_persona', 
                    'id_actividad', 
                    'tiene_discapacidad', 
                    'es_familiar', 
                    'firma'
                ]);

                return ParticipaEn::updateOrCreate(
                    [
                        'id_persona' => $data['id_persona'],
                        'id_actividad' => $data['id_actividad'],
                    ],
                    $data
                );
            });

            return response()->json($participacion, 201);

        } catch (\Exception $e) {
            \Log::error('Error al guardar participación: ' . $e->getMessage()); 
            
            return response()->json([
                'message' => 'Error de servidor al guardar la participación.', 
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id_persona, $id_actividad)
    {
        $participacion = ParticipaEn::where('id_persona', $id_persona)
            ->where('id_actividad', $id_actividad)
            ->firstOrFail();

        $request->validate([
            'tiene_discapacidad' => 'sometimes|required|boolean',
            'es_familiar' => 'sometimes|required|boolean',
            'firma' => 'sometimes|required|boolean',
            'id_institucion' => 'sometimes|required|exists:INSTITUCION,id_institucion', 
        ]);

        try {
            $participacion = DB::transaction(function () use ($request, $id_persona, $id_actividad, $participacion) {

                // 1. Actualizar Afiliación (si se envía el ID de institución)
                if ($request->has('id_institucion')) {
                    Participante::updateOrCreate(
                        ['id_persona' => $id_persona],
                        ['id_institucion' => $request->id_institucion]
                    );
                }
                
                // 2. Actualizar Participación
                $participacion->update($request->only(['tiene_discapacidad', 'es_familiar', 'firma'])); 
                
                return $participacion;
            });
            return response()->json($participacion);

        } catch (\Exception $e) {
            \Log::error('Error al actualizar participación: ' . $e->getMessage()); 
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
        \Log::error('Error al eliminar participación: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}