<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'beneficiario';
    protected $primaryKey = 'id_persona';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id_persona',
        'tipo_discapacidad',
    ];

    public $timestamps = true;

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}
