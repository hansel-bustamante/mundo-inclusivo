<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'SEGUIMIENTO';
    protected $primaryKey = 'id_seguimiento';
    public $timestamps = false; // No tiene timestamps en la tabla

    protected $fillable = [
        'fecha',
        'tipo',
        'observaciones',
        'actividad_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function actividad()
    {
        // El seguimiento está asociado a una actividad
        return $this->belongsTo(Actividad::class, 'actividad_id', 'id_actividad');
    }
}