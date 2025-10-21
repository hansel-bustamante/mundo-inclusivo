<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    // Nombre de la tabla
    protected $table = 'beneficiario';
    
    // Clave primaria (es una FK a Persona)
    protected $primaryKey = 'id_persona';
    
    // Desactivamos el auto-incremento, ya que la PK viene de Persona
    public $incrementing = false;
    
    // Clave primaria es de tipo INT
    protected $keyType = 'int';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'id_persona',
        'tipo_discapacidad', // Campo específico de la tabla BENEFICIARIO
    ];

    public $timestamps = true;

    /**
     * Relación: Un beneficiario ES una Persona.
     * Usa belongsTo porque 'id_persona' es la clave foránea en esta tabla.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}
