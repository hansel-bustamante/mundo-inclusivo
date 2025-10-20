<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // si usarás autenticación

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_persona';
    public $incrementing = false; // porque es PK no autoincremental
    protected $keyType = 'int';

    protected $fillable = [
        'id_persona',
        'nombre_usuario',
        'contrasena',
        'rol',
        'correo',
        'area_intervencion_id'
    ];

    public $timestamps = true;

    // Mutator para hashear la contraseña automáticamente al guardar
    public function setContraseñaAttribute($value)
    {
        $this->attributes['contrasena'] = bcrypt($value);
    }

    // Relaciones (opcional)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }
}

