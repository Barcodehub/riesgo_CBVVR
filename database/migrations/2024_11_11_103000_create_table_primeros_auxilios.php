<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('primeros_auxilios', function (Blueprint $table) {
            $table->id();
            $table->boolean('camilla');
            $table->boolean('inmovilizador_cervical');
            $table->boolean('inmovilizador_extremidades');
            $table->boolean('capacitacion_primeros_auxilios');
            $table->string('tipo_camilla');
            $table->string('tipo_inm_cervical');
            $table->string('tipo_inm_extremidades');
            $table->string('tipo_capacitacion');
            $table->string('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('primeros_auxilios');
    }
};
