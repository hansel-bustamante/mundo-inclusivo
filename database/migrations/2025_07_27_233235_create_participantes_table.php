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
        Schema::create('participante', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona')->primary();
            $table->unsignedBigInteger('id_institucion');
            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreign('id_institucion')->references('id_institucion')->on('institucion')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
