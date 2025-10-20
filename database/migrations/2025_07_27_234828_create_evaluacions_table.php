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
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->id('id_evaluacion');
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->enum('resultado', ['Cumplido', 'No cumplido', 'Parcial']);
            $table->decimal('ponderacion', 5, 2)->nullable();
            $table->decimal('nivel_aceptacion', 5, 2)->nullable();
            $table->boolean('expectativa_cumplida')->default(false);
            $table->text('actividades_no_cumplidas')->nullable();
            $table->unsignedBigInteger('actividad_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('actividad_id')->references('id_actividad')->on('actividad')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id_persona')->on('usuario')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacions');
    }
};
