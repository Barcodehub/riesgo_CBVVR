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
        Schema::create('tipo_botiquin_conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('empresa_recarga');
            $table->date('fecha_vencimiento');
            $table->unsignedBigInteger('tipo_botiquin_id');
            $table->foreign('tipo_botiquin_id')->references('id')->on('type_kits')->onDelete('cascade');
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
        Schema::dropIfExists('tipo_botiquin_conceptos');
    }
};
