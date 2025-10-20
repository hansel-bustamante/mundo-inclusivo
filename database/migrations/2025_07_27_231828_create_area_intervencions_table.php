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
        Schema::create('area_intervencion', function (Blueprint $table) {
            $table->id('codigo_area');
            $table->string('nombre_area', 100);
            $table->string('municipio', 100);
            $table->string('provincia', 100);
            $table->string('departamento', 100);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_intervencions');
    }
};
