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
        Schema::create('almacenamiento_combustibles', function (Blueprint $table) {
            $table->id();
            $table->boolean('material_solido_ordinario');
            $table->boolean('zona_almacenamiento_1');
            $table->string('observaciones_1');
            $table->double('cantidad_1');
            $table->boolean('material_liquido_inflamable');
            $table->boolean('zona_almacenamiento_2');
            $table->string('observaciones_2');
            $table->double('cantidad_2');
            $table->boolean('material_gaseoso_inflamable');
            $table->boolean('zona_almacenamiento_3');
            $table->string('observaciones_3');
            $table->double('cantidad_3');
            $table->boolean('otros_quimicos');
            $table->boolean('zona_almacenamiento_4');
            $table->string('observaciones_4');
            $table->double('cantidad_4');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacenamiento_combustibles');
    }
};
