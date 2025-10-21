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
        Schema::create('actividad', function (Blueprint $table) {
            $table->id('id_actividad');
            $table->string('nombre', 150);
            $table->date('fecha');
            $table->string('lugar', 100);
            $table->text('descripcion')->nullable();
            $table->char('codigo_actividad_id', 2);
            $table->string('area_intervencion_id', 20);
            $table->timestamps();

            $table->foreign('codigo_actividad_id')->references('codigo_actividad')->on('codigo_actividad')->onDelete('cascade');
            $table->foreign('area_intervencion_id')->references('codigo_area')->on('area_intervencion')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividads');
    }
};
