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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('documento')->unique();
            $table->string('telefono');
            $table->string('telefono2')->nullable();
            $table->boolean('disponibilidad');
            $table->string('email')->unique();
            $table->string('email2')->nullable();
            $table->string('password');
            //$table->string('acceso_huella')->nullable();
            $table->boolean('acceso_huella')->default(false);
            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('restrict');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
