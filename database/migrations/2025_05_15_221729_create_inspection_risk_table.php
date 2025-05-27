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
       Schema::create('inspection_risk', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
    $table->foreignId('risk_id')->constrained()->onDelete('cascade');
    $table->text('observations')->nullable();
    $table->string('severity')->default('media');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_risk');
    }
};
