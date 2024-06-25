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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('horario_funcionamiento');
            $table->string('cedula_representante');
            $table->string('representante_legal');
            $table->string('nit');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('actividad_comercial');
            $table->float('ancho_dimensiones');
            $table->float('largo_dimensiones');
            $table->integer('num_pisos');
            $table->unsignedBigInteger('cliente_id')->nullable(true);
            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
