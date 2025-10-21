<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividad';
    protected $primaryKey = 'id_actividad';

    protected $fillable = [
        'nombre',
        'fecha',
        'lugar',
        'descripcion',
        'codigo_actividad_id',
        'area_intervencion_id',
    ];

    public $timestamps = true;

    // Relaciones
    public function codigoActividad()
    {
        return $this->belongsTo(CodigoActividad::class, 'codigo_actividad_id', 'codigo_actividad');
    }

    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }


    public function participantes()
    {
        return $this->belongsToMany(Persona::class, 'PARTICIPA_EN', 'id_actividad', 'id_persona')
                    ->using(ParticipaEn::class) // Usamos el modelo pivote
                    ->withPivot(['tiene_discapacidad', 'es_familiar', 'firma']);
    }

}
