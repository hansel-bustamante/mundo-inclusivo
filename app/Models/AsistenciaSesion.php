<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaSesion extends Model
{
    protected $table = 'asistencia_sesion';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_sesion',
        'id_persona',
        'firma',
        'observaciones'
    ];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'id_sesion', 'id_sesion');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}
