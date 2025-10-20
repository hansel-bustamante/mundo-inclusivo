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
        Schema::create('sesion', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->integer('nro_sesion');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('tema', 150)->nullable();
            $table->unsignedBigInteger('id_actividad');
            $table->timestamps();

            $table->foreign('id_actividad')->references('id_actividad')->on('actividad')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesions');
    }
};
