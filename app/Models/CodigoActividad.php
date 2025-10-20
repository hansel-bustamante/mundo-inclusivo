<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoActividad extends Model
{
    protected $table = 'codigo_actividad';
    protected $primaryKey = 'codigo_actividad';
    public $incrementing = false;  // Importante, la PK no es auto-incremental
    protected $keyType = 'string'; // Tipo de la clave primaria

    protected $fillable = [
        'codigo_actividad',
        'nombre_actividad',
        'descripcion',
    ];

    public $timestamps = true;
}
