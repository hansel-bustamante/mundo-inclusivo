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
        Schema::create('participa_en', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_actividad');
            $table->boolean('tiene_discapacidad')->default(false);
            $table->boolean('es_familiar')->default(false);
            $table->boolean('firma')->default(false);

            $table->primary(['id_persona', 'id_actividad']);

            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreign('id_actividad')->references('id_actividad')->on('actividad')->onDelete('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participa_ens');
    }
};
