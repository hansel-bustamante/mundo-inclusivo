<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $table = 'sesion';
    protected $primaryKey = 'id_sesion';

    protected $fillable = [
        'nro_sesion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'tema',
        'id_actividad'
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }
}
