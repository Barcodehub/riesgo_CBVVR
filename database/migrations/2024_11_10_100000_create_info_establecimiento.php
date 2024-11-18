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
        Schema::create('info_establecimiento', function (Blueprint $table) {
            $table->id();
            $table->integer('num_pisos');
            $table->double('ancho_dimensiones');
            $table->double('largo_dimensiones');
            $table->integer('carga_ocupacional_fija');
            $table->integer('carga_ocupacional_flotante');
            $table->unsignedBigInteger('id_empresa');
            $table->foreign('id_empresa')->references('id')->on('companies')->onDelete('restrict');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_establecimiento');
    }
};