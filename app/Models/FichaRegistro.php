<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaRegistro extends Model
{
    protected $table = 'FICHA_REGISTRO';
    protected $primaryKey = 'id_ficha';
    public $timestamps = false; 

    protected $fillable = [
        'fecha_registro',
        'retraso_en_desarrollo',
        'incluido_en_educacion_2025',
        'beneficiario_id',
        'usuario_id',
        'area_intervencion_id',
    ];

    protected $casts = [
        // Laravel convierte los booleanos de la DB (0/1) a true/false
        'retraso_en_desarrollo' => 'boolean',
        'incluido_en_educacion_2025' => 'boolean',
    ];

    // Relaciones
    public function beneficiario()
    {
        // Se relaciona con Participante (tabla PARTICIPANTE), usando id_persona
        return $this->belongsTo(Participante::class, 'beneficiario_id', 'id_persona');
    }

    public function usuario()
    {
        // Se relaciona con Usuario (tabla USUARIO), usando id_persona
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_persona');
    }

    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }
}