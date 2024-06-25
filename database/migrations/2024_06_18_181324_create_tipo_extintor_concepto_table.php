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
        Schema::create('tipo_extintor_concepto', function (Blueprint $table) {
            $table->id();
            $table->string('empresa_recarga');
            $table->date('fecha_vencimiento');
            $table->unsignedBigInteger('tipo_extintor_id');
            $table->foreign('tipo_extintor_id')->references('id')->on('type_extinguishers')->onDelete('cascade');
            $table->unsignedBigInteger('concepto_id');
            $table->foreign('concepto_id')->references('id')->on('concepts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_extintor_concepto');
    }
};
