<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    // Usamos el nombre en MAYÚSCULAS para coincidir con tu esquema SQL
    protected $table = 'PARTICIPANTE'; 
    
    protected $primaryKey = 'id_persona';
    public $incrementing = false; 
    protected $keyType = 'int';

    protected $fillable = [
        'id_persona',
        'id_institucion',
    ];

    // ************************************************
    // ¡CRÍTICO! Deshabilitar timestamps, ya que no existen en la tabla.
    public $timestamps = false; 
    // ************************************************

    protected $casts = [
        'id_persona' => 'integer',
        'id_institucion' => 'integer',
    ];
        // Relaciones
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id_institucion');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}