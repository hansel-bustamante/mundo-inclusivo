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
        Schema::create('beneficiario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona')->primary();
            $table->string('tipo_discapacidad', 100);
            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiarios');
    }
};
