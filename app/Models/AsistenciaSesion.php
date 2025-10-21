<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log; // Opcional, solo si quieres logging

class AsistenciaSesion extends Pivot
{
    protected $table = 'ASISTENCIA_SESION';
    
    // Claves primarias compuestas
    protected $primaryKey = ['id_sesion', 'id_persona']; 
    public $incrementing = false;
    public $timestamps = false; 

    protected $fillable = [
        'id_sesion',
        'id_persona',
        'firma',
        'observaciones',
    ];

    protected $casts = [
        'id_sesion' => 'integer',
        'id_persona' => 'integer',
        'firma' => 'boolean',
    ];

    // ************************************************
    // SOLUCIÓN AL ERROR DE CLAVE COMPUESTA (Eliminación directa)
    // ************************************************
    public function delete()
    {
        return static::where('id_sesion', $this->id_sesion)
            ->where('id_persona', $this->id_persona)
            ->delete();
    }
    
    // Sobreescribir setKeysForSaveQuery también es buena práctica para otras operaciones
    protected function setKeysForSaveQuery($query)
    {
        $query->where('id_sesion', $this->getAttribute('id_sesion'))
              ->where('id_persona', $this->getAttribute('id_persona'));

        return $query;
    }

    // Relaciones
    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'id_sesion', 'id_sesion');
    }
    
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}