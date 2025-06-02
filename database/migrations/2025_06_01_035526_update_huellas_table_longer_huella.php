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
    Schema::table('huellas', function (Blueprint $table) {
        // Cambiar de VARCHAR a TEXT o LONGTEXT si necesitas más espacio
        $table->text('huella')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('huellas', function (Blueprint $table) {
            //
        });
    }
};
