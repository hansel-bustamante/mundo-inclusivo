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
        Schema::create('seguimiento', function (Blueprint $table) {
            $table->id('id_seguimiento');
            $table->date('fecha');
            $table->string('tipo', 50);
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('actividad_id');
            $table->timestamps();

            $table->foreign('actividad_id')->references('id_actividad')->on('actividad')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
