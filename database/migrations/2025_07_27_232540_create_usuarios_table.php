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
        Schema::create('usuario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona')->primary();
            $table->string('nombre_usuario', 50)->unique();
            $table->string('contrasena', 255);
            $table->enum('rol', ['admin', 'registrador', 'coordinador']);
            $table->string('correo', 100)->nullable();
            $table->unsignedBigInteger('area_intervencion_id')->nullable();

            // Foreign keys
            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreign('area_intervencion_id')->references('codigo_area')->on('area_intervencion')->onDelete('set null');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
