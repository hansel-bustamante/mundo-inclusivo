<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'EVALUACION'; 
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
    
    // Relación con Actividad
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id', 'id_actividad');
    }

    // Relación con Usuario (el que realizó la evaluación)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_persona');
    }
}