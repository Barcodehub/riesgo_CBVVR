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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_solicitud');
            $table->unsignedBigInteger('establecimiento_id');
            $table->foreign('establecimiento_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('inspector_id');
            $table->foreign('inspector_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('estado');
            $table->double('valor')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
