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
        Schema::create('equipo_contra_incendio', function (Blueprint $table) {
            $table->id();
            $table->boolean('sistema_automatico');
            $table->string('tipo_sistema');
            $table->string('observaciones_sa');
            $table->boolean('red_contra_incendios');
            $table->boolean('hidrantes');
            $table->string('tipo_hidrante');
            $table->double('distancia');
            $table->string('observaciones_hyr');
            $table->boolean('capacitacion');
            $table->string('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_contra_incendio');
    }
};
