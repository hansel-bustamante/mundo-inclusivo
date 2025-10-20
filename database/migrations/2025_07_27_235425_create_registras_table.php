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
        Schema::create('registra', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_actividad');
            $table->date('fecha_registro');

            $table->primary(['id_persona', 'id_actividad']);

            $table->foreign('id_persona')->references('id_persona')->on('usuario')->onDelete('cascade');
            $table->foreign('id_actividad')->references('id_actividad')->on('actividad')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registras');
    }
};
