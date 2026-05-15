<?php

namespace App\Models;

// 1. IMPORTACIONES OBLIGATORIAS
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // <--- ESTA ES LA QUE TE FALTABA
use Laravel\Sanctum\HasApiTokens;        // <--- ESTA ES PARA EL LOGIN APP

class Usuario extends Authenticatable
{
    // 2. USO DE LOS TRAITS
    use HasApiTokens, HasFactory, Notifiable; 

    // CONFIGURACIÓN DE LA TABLA
    protected $table = 'USUARIO';
    
    // Definición de la clave primaria (si no es 'id')
    protected $primaryKey = 'id_persona';
    public $incrementing = false; 
    protected $keyType = 'int';

    protected $fillable = [
        'id_persona',
        'nombre_usuario',
        'contrasena',
        'rol',
        'correo',
        'area_intervencion_id',
        'must_change_password'
    ];

    public $timestamps = true;
    
    protected $hidden = [
        'contrasena',
        'remember_token',
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
        // NOTA: Para CRUDS de gestión, a menudo se usa un 'setter' que hashea siempre, 
        // pero este enfoque previene el doble hasheo si se pasa un hash existente.
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
    // NOTA: Asumo que AreaIntervencion tiene una clave primaria 'codigo_area' (VARCHAR o INT)
    public function areaIntervencion()
    {
        return $this->belongsTo(AreaIntervencion::class, 'area_intervencion_id', 'codigo_area');
    }
}
