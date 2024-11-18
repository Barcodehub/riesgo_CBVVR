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
        Schema::create('sistema_electrico', function (Blueprint $table) {
            $table->id();
            $table->boolean('caja_distribucion_breker');
            $table->boolean('encuentra_identificados');
            $table->boolean('sistema_cableado_protegido');
            $table->boolean('toma_corriente_corto');
            $table->boolean('toma_corriente_sobrecarga');
            $table->boolean('identificacion_voltaje');
            $table->boolean('cajetines_asegurados');
            $table->boolean('boton_emergencia');
            $table->boolean('mantenimiento_preventivo');
            $table->string('periodicidad');
            $table->boolean('personal_idoneo');
            $table->string('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sistema_electrico');
    }
};
