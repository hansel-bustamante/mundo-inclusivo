<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'institucion';
    protected $primaryKey = 'id_institucion';
    public $timestamps = true;

    protected $fillable = [
        'nombre_institucion',
        'tipo',
        'direccion',
        'telefono',
        'municipio'
    ];
}
