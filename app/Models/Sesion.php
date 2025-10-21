<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $table = 'SESION';
    protected $primaryKey = 'id_sesion';
    public $timestamps = false; // Asumiendo que SESION no tiene timestamps

    protected $fillable = [
        'nro_sesion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'tema',
        'id_actividad',
    ];

    protected $casts = [
        'fecha' => 'date',
        // Otros casts si son necesarios
    ];

    // Relación de uno a muchos con Actividad
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }

    // Relación de muchos a muchos con Persona (Asistencia)
    public function asistentes()
    {
        return $this->belongsToMany(Persona::class, 'ASISTENCIA_SESION', 'id_sesion', 'id_persona')
                    ->using(AsistenciaSesion::class) // Usar el modelo Pivot
                    ->withPivot(['firma', 'observaciones']);
    }
}