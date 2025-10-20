<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'seguimiento';
    protected $primaryKey = 'id_seguimiento';

    protected $fillable = [
        'fecha',
        'tipo',
        'observaciones',
        'actividad_id',
    ];

    public $timestamps = true;

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id', 'id_actividad');
    }
}
