<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id('id_persona'); // Clave primaria
            $table->string('nombre', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable(); // A veces no tienen segundo apellido
            $table->date('fecha_nacimiento');
            $table->string('carnet_identidad', 20); // Debería ser unique, pero lo manejas en validación
            $table->string('celular', 20)->nullable();
            $table->string('procedencia', 100)->nullable();
            $table->enum('genero', ['M', 'F']);
            
            // --- NUEVO CAMPO: ÁREA DE ORIGEN ---
            // Usamos string(20) porque tu tabla area_intervencion usa 'codigo_area' (varchar)
            $table->string('area_intervencion_id', 20)->nullable(); 
            
            // Definimos la llave foránea (opcional pero recomendada)
            $table->foreign('area_intervencion_id')
                  ->references('codigo_area')
                  ->on('area_intervencion')
                  ->onDelete('set null'); // Si borran el área, la persona no se borra, queda "sin área"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ CORRECCIÓN: Deshabilitar temporalmente la verificación de FK
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('persona');

        // ✅ Volver a habilitar
        Schema::enableForeignKeyConstraints();
    }
};