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
        Schema::create('asistencia_sesion', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sesion');
            $table->unsignedBigInteger('id_persona');
            $table->boolean('firma')->default(false);
            $table->text('observaciones')->nullable();

            $table->primary(['id_sesion', 'id_persona']);

            $table->foreign('id_sesion')->references('id_sesion')->on('sesion')->onDelete('cascade');
            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_sesions');
    }
};
