<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacion';
    protected $primaryKey = 'id_evaluacion';

    protected $fillable = [
        'fecha',
        'descripcion',
        'resultado',
        'ponderacion',
        'nivel_aceptacion',
        'expectativa_cumplida',
        'actividades_no_cumplidas',
        'actividad_id',
        'usuario_id'
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id', 'id_actividad');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_persona');
    }
}
