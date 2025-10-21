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
        Schema::create('ficha_registro', function (Blueprint $table) {
            $table->id('id_ficha');
            $table->date('fecha_registro');
            $table->boolean('retraso_en_desarrollo')->default(false);
            $table->boolean('incluido_en_educacion_2025')->default(false);
            $table->unsignedBigInteger('beneficiario_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('area_intervencion_id', 20);
            $table->timestamps();

            $table->foreign('beneficiario_id')->references('id_persona')->on('beneficiario')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id_persona')->on('usuario')->onDelete('cascade');
            $table->foreign('area_intervencion_id')->references('codigo_area')->on('area_intervencion')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ficha_registros');
    }
};
