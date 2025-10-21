<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaIntervencion extends Model
{
    use HasFactory; 
    
    protected $table = 'area_intervencion';
    protected $primaryKey = 'codigo_area';
    
    // CAMBIO CLAVE: Indica que la clave primaria NO es autoincremental
    public $incrementing = false; 
    
    protected $fillable = [
        'codigo_area', // Debe ser fillable para poder asignarse masivamente
        'nombre_area',
        'municipio',
        'provincia',
        'departamento',
    ];
}