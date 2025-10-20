<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaRegistro extends Model
{
    protected $table = 'ficha_registro';
    protected $primaryKey = 'id_ficha';

    protected $fillable = [
        'fecha_registro',
        'retraso_en_desarrollo',
        'incluido_en_educacion_2025',
        'beneficiario_id',
        'usuario_id',
        'area_intervencion_id',
    ];

    public $timestamps = true;

    // Relaciones
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id', 'id_persona');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_persona');
    }

    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }
}
