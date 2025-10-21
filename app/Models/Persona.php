<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    
    // Sobrescribimos el nombre de la tabla
    protected $table = 'PERSONA';
    
    // Definición de la clave primaria
    protected $primaryKey = 'id_persona';
    public $incrementing = true; 
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'carnet_identidad',
        'celular',
        'procedencia',
        'genero',
    ];

    public $timestamps = true;
    
    // Relación inversa con Beneficiario (Si es un beneficiario)
    public function beneficiario()
    {
        return $this->hasOne(Beneficiario::class, 'id_persona', 'id_persona');
    }

    // Relación inversa con Usuario (Si es un usuario/staff)
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_persona', 'id_persona');
    }

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'PARTICIPA_EN', 'id_persona', 'id_actividad')
                    ->using(ParticipaEn::class)
                    ->withPivot(['tiene_discapacidad', 'es_familiar', 'firma']);
    }
}