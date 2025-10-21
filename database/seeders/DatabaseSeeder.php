<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Asegúrate de que este seeder exista en database/seeders/
            // Este crea al usuario 'admin_global'
            AdminUserSeeder::class, 
            
            // Puedes agregar aquí otros seeders que necesites para catálogos:
            // InstitucionSeeder::class,
            // AreaIntervencionSeeder::class,
        ]);
    }
}
