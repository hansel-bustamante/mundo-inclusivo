<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaIntervencion extends Model
{
    protected $table = 'area_intervencion';
    protected $primaryKey = 'codigo_area';
    public $timestamps = true;

    protected $fillable = [
        'nombre_area',
        'municipio',
        'provincia',
        'departamento',
    ];
}

