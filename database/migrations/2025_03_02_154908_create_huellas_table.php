<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('huellas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');  // Clave foránea
            $table->string('huella');  // Campo para almacenar la huella
            $table->timestamps();

            // Definir la clave foránea
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('huellas');
    }
};
