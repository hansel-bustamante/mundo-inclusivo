<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $table = 'participante';
    protected $primaryKey = 'id_persona';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id_persona',
        'id_institucion',
    ];

    public $timestamps = true;

    // Relaciones (opcional)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id_institucion');
    }
}
