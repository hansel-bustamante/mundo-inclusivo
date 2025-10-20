<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipaEn extends Model
{
    protected $table = 'participa_en';
    public $incrementing = false; // porque la PK es compuesta
    protected $primaryKey = null; // no hay clave primaria simple
    public $timestamps = true;

    protected $fillable = [
        'id_persona',
        'id_actividad',
        'tiene_discapacidad',
        'es_familiar',
        'firma',
    ];

    // Para desactivar timestamps si quieres (opcional)
    // public $timestamps = false;

    // Relaciones (opcional)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }
}
