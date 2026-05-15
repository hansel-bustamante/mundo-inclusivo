<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'PERSONA';
    protected $primaryKey = 'id_persona';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'carnet_identidad',
        'celular',
        'procedencia',
        'genero',
        'area_intervencion_id', // <--- ¡ESTO FALTABA! Sin esto, no se guarda.
    ];

    public $timestamps = true;

    // ... (resto de tu modelo: mutators y relaciones) ...
    
    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_persona', 'id_persona');
    }

    public function beneficiario()
    {
        return $this->hasOne(Beneficiario::class, 'id_persona', 'id_persona');
    }
    
    public function afiliacion()
    {
        // Una persona tiene un registro único de afiliación en la tabla PARTICIPANTE
        return $this->hasOne(Participante::class, 'id_persona', 'id_persona');
    }
    
    // Si tienes otra función llamada 'participaciones' apuntando a Participante, ELIMÍNALA.
    
    public function participaEnActividades()
    {
        return $this->hasMany(ParticipaEn::class, 'id_persona', 'id_persona');
    }
}