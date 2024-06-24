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
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_concepto');
            $table->unsignedBigInteger('inspeccion_id');
            $table->foreign('inspeccion_id')->references('id')->on('inspections')->onDelete('cascade');
            $table->integer('carga_ocupacional_fija');
            $table->integer('carga_ocupacional_flotante');
            $table->integer('anios_contruccion');
            $table->boolean('nrs10');
            $table->boolean('sgsst');
            $table->boolean('sist_automatico_incendios');
            $table->string('observaciones_sist_incendios');
            $table->string('descripcion_concepto');
            $table->boolean('hidrante');
            $table->string('tipo_hidrante');
            $table->boolean('capacitacion');
            $table->string('tipo_camilla')->nullable(true);
            $table->string('inmovilizador_vertical')->nullable(true);
            $table->boolean('capacitacion_primeros_auxilios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};
