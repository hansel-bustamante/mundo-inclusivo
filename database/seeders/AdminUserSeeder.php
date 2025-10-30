<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario; 
use App\Models\Persona; // Asegúrate de que este modelo exista y esté correctamente configurado

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. CREAR REGISTRO EN PERSONA
        // Incluimos todos los campos obligatorios ('NOT NULL') que causaron error: 
        // apellido_materno, celular y procedencia.
        
        // El método firstOrCreate intentará encontrar el registro. Si no existe, lo creará.
        $personaAdmin = Persona::firstOrCreate(
            ['id_persona' => 100], // Clave de búsqueda: Buscamos si el ID 1 ya existe
            [
                'nombre' => 'Admin',
                'apellido_paterno' => 'Global',
                'apellido_materno' => 'Sistema', // Solución al primer error
                'carnet_identidad' => '00000000',
                'genero' => 'M',
                'fecha_nacimiento' => '1990-01-01',
                'celular' => '59170000000',      // Solución al error de celular
                'procedencia' => 'Sede Central', // Solución al error de procedencia
            ]
        );

        // 2. VERIFICAR SI EL USUARIO YA EXISTE
        $existingAdmin = Usuario::where('nombre_usuario', 'admin_global')->first();

        if (!$existingAdmin) {
            
            // 3. CREAR EL USUARIO
            Usuario::create([
                'id_persona' => $personaAdmin->id_persona, 
                'nombre_usuario' => 'admin_global',
                // La contraseña se hasheará automáticamente gracias al mutator en el modelo Usuario
                'contrasena' => 'contrasena123', 
                'rol' => 'admin',
                'correo' => 'admin@mundoinclusivo.com',
                'area_intervencion_id' => null, // El admin global no necesita un área
            ]);
            
            echo "¡Usuario Administrador 'admin_global' creado con éxito (Contraseña: contrasena123)!\n";
        } else {
             echo "El usuario administrador ya existe.\n";
        }
    }
}