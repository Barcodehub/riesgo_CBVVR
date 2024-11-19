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
            $table->string('nombre_establecimiento');
            $table->string('horario_funcionamiento');
            $table->string('representante_legal');
            $table->string('cedula_representante')->unique();
            $table->string('nit')->unique();
            $table->string('direccion');
            $table->string('barrio');
            $table->string('telefono');
            $table->string('email');
            $table->string('actividad_comercial');
            $table->unsignedBigInteger('cliente_id')->nullable(true);
            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('restrict');
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
