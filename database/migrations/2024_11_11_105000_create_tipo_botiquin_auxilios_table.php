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
        Schema::create('tipo_botiquin_Auxilios', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->unsignedBigInteger('tipo_botiquin_id');
            $table->foreign('tipo_botiquin_id')->references('id')->on('type_kits')->onDelete('restrict');
            $table->unsignedBigInteger('id_primeros_auxilios');
            $table->foreign('id_primeros_auxilios')->references('id')->on('primeros_auxilios')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_botiquin_Auxilios');
    }
};
