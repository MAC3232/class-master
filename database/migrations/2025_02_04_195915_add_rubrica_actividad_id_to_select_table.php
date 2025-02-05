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
        Schema::table('criterio_desempeno_estudiante', function (Blueprint $table) {
            //

            $table->foreignId('rubrica_actividad_id')->constrained('rubrica_actividad')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterio_desempeno_estudiante', function (Blueprint $table) {
            //
            $table->dropForeign(['rubrica_actividad_id']);
            $table->dropColumn('rubrica_actividad_id');
        });
    }
};
