<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Usuario extends Authenticatable
{
    use HasFactory;
    
    // CRÍTICO: Sobrescribimos el nombre de la tabla por defecto ('users')
    protected $table = 'USUARIO';
    
    // Definición de la clave primaria (que es foránea a PERSONA)
    protected $primaryKey = 'id_persona';
    public $incrementing = false; 
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
    
    // CRÍTICO: Ocultamos el campo 'contrasena' y lo definimos como oculto
    protected $hidden = [
        'contrasena',
    ];

    /**
     * CRÍTICO: Sobreescribe el método por defecto de Laravel
     * para que use la columna 'contrasena' en lugar de 'password'.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }


    // Mutator para hashear la contraseña automáticamente al guardar/actualizar
    public function setContrasenaAttribute($value)
    {
        // Solo hashea si el valor no parece ser ya un hash (para evitar doble hasheo)
        if (!\Illuminate\Support\Str::startsWith($value, '$2y$')) {
            $this->attributes['contrasena'] = bcrypt($value);
        } else {
            $this->attributes['contrasena'] = $value;
        }
    }
    
    // Mutator para asegurar que el nombre de usuario esté en minúsculas
    public function setNombreUsuarioAttribute($value)
    {
        $this->attributes['nombre_usuario'] = strtolower($value);
    }

    // Relación con la tabla PERSONA
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    // Relación con AREA_INTERVENCION
    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }
}