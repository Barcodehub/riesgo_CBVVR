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
        Schema::create('construccion', function (Blueprint $table) {
            $table->id();
            $table->string('anio_construccion');
            $table->boolean('nrs');
            $table->boolean('sst');
            $table->unsignedBigInteger('id_info_establecimiento');
            $table->foreign('id_info_establecimiento')->references('id')->on('info_establecimiento')->onDelete('restrict');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construccion');
    }
};
