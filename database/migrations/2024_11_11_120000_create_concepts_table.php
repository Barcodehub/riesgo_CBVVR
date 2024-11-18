<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Ramsey\Uuid\v1;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_concepto');
            $table->unsignedBigInteger('inspeccion_id');
            $table->foreign('inspeccion_id')->references('id')->on('inspections')->onDelete('restrict');
            $table->boolean('favorable');
            $table->timestamps();
            $table->string('recomendaciones');
            $table->unsignedBigInteger('id_info_establecimiento');
            $table->foreign('id_info_establecimiento')->references('id')->on('info_establecimiento')->onDelete('restrict');
            $table->unsignedBigInteger('id_construccion');
            $table->foreign('id_construccion')->references('id')->on('construccion')->onDelete('restrict');
            $table->unsignedBigInteger('id_imagen');
            $table->foreign('id_imagen')->references('id')->on('archivos')->onDelete('restrict');
            $table->unsignedBigInteger('id_sistema_electrico');
            $table->foreign('id_sistema_electrico')->references('id')->on('sistema_electrico')->onDelete('restrict');
            $table->unsignedBigInteger('id_sistema_iluminacion');
            $table->foreign('id_sistema_iluminacion')->references('id')->on('sistema_iluminacion')->onDelete('restrict');
            $table->unsignedBigInteger('id_ruta');
            $table->foreign('id_ruta')->references('id')->on('ruta_evacuacion')->onDelete('restrict');
            $table->unsignedBigInteger('id_otros');
            $table->foreign('id_otros')->references('id')->on('otras_condicones')->onDelete('restrict'); 
            $table->unsignedBigInteger('id_almacenamiento');
            $table->foreign('id_almacenamiento')->references('id')->on('almacenamiento_combustibles')->onDelete('restrict'); 
            ////Falta la foranea a imagenes y demas foraneas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};
