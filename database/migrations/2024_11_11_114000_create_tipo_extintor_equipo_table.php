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
        Schema::create('tipo_extintor_equipo', function (Blueprint $table) {
            $table->id();
            $table->string('empresa_recarga');
            $table->date('fecha_recarga');
            $table->date('fecha_vencimiento');
            $table->integer('cantidad');
            $table->unsignedBigInteger('tipo_extintor_id');
            $table->foreign('tipo_extintor_id')->references('id')->on('type_extinguishers')->onDelete('restrict');
            $table->unsignedBigInteger('id_equipo_contra_incendio');
            $table->foreign('id_equipo_contra_incendio')->references('id')->on('equipo_contra_incendio')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_extintor_equipo');
    }
};
