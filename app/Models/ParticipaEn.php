<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class ParticipaEn extends Pivot
{
    // *** 1. PROPIEDADES CRÍTICAS ***
    protected $table = 'PARTICIPA_EN'; // Debe coincidir exactamente con tu tabla SQL
    
    public $incrementing = false;
    protected $primaryKey = ['id_persona', 'id_actividad']; 
    public $timestamps = false; 

    protected $fillable = [
        'id_persona',
        'id_actividad',
        'tiene_discapacidad',
        'es_familiar',
        'firma',
    ];

    // *** 2. CORRECCIÓN CRÍTICA DE LA CLAVE ***
    /**
     * Reemplaza la lógica de Laravel para construir la cláusula WHERE de
     * eliminación o actualización en modelos con clave compuesta.
     */
    protected function setKeysForSaveQuery($query)
    {
        Log::info('Ejecutando setKeysForSaveQuery para eliminación: Persona ' . $this->getAttribute('id_persona') . ', Actividad ' . $this->getAttribute('id_actividad'));
        // Usamos where y getAttribute para asegurarnos de que se lean los valores.
        $query->where('id_persona', $this->getAttribute('id_persona'))
              ->where('id_actividad', $this->getAttribute('id_actividad'));

        return $query;
    }
    
    // *** 3. CASTING (ADICIÓN PARA ROBUSTEZ) ***
    // Esto asegura que Laravel siempre vea tus claves como enteros.
    protected $casts = [
        'id_persona' => 'integer',
        'id_actividad' => 'integer',
        'tiene_discapacidad' => 'boolean', 
        'es_familiar' => 'boolean',       
        'firma' => 'boolean',             
    ];

    // ... (Mantén tus relaciones aquí si las tienes) ...
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
    
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }


    public function delete()
    {
        return static::where('id_persona', $this->id_persona)
            ->where('id_actividad', $this->id_actividad)
            ->delete();
    }

    public function afiliacion()
    {
        // ParticipaEn se relaciona con Participante a través de id_persona
        return $this->hasOne(Participante::class, 'id_persona', 'id_persona');
    }
}