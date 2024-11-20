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
        Schema::create('ruta_evacuacion', function (Blueprint $table) {
            $table->id();
            $table->boolean('ruta_evacuacion');
            $table->boolean('salidas_emergencia');
            $table->string('observaciones');
            $table->boolean('escaleras');
            $table->boolean('señalizadas');
            $table->boolean('barandas');
            $table->string('condicion_escaleras');
            $table->string('condicion_señalizadas');
            $table->string('condicion_barandas');
            $table->string('condicion_antideslizante');
            $table->string('observaciones_escaleras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_evacuacion');
    }
};
