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

    // Relaciones de Catálogos
    public function codigoActividad()
    {
        return $this->belongsTo(CodigoActividad::class, 'codigo_actividad_id', 'codigo_actividad');
    }

    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }

    // Relación Muchos a Muchos (Participantes)
    public function participantes()
    {
        return $this->belongsToMany(Persona::class, 'PARTICIPA_EN', 'id_actividad', 'id_persona')
                    ->using(ParticipaEn::class) // Usamos el modelo pivote
                    ->withPivot(['tiene_discapacidad', 'es_familiar', 'firma']);
    }

    // ******************************************************
    // RELACIÓN UNO A MUCHOS (ACTUALIZACIÓN PARA CORREGIR EL ERROR)
    // ******************************************************
    /**
     * Una Actividad tiene muchas Sesiones.
     * La clave foránea es 'id_actividad' en el modelo Sesion.
     */
    public function sesiones() // <-- ¡ESTA ES LA RELACIÓN QUE FALTABA!
    {
        // Se relaciona con Sesion, usando 'id_actividad' como clave foránea en la tabla SESION,
        // y 'id_actividad' como clave local en esta tabla (ACTVIDAD).
        return $this->hasMany(Sesion::class, 'id_actividad', 'id_actividad');
    }

    // ******************************************************
    // ¡CORRECCIÓN! RELACIÓN UNO A UNO CON EVALUACION (AÑADIDA)
    // ******************************************************
    public function evaluacion()
    {
        // Una Actividad puede tener como máximo una Evaluación
        return $this->hasOne(Evaluacion::class, 'actividad_id', 'id_actividad');
    }
}