<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registra extends Model
{
    protected $table = 'registra';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_persona',
        'id_actividad',
        'fecha_registro'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_persona', 'id_persona');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }
}
