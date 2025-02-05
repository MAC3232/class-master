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
        Schema::table('niveles_desempeno_actividad', function (Blueprint $table) {

            //
            $table->renameColumn('puntaje_inicial', 'puntos');


            $table->dropColumn('puntaje_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('niveles_desempeno_actividad', function (Blueprint $table) {
            //
            $table->renameColumn('puntos', 'puntaje_inicial');

            // Restaurar el campo eliminado (ajusta el tipo de dato según corresponda)
            $table->integer('puntaje_final')->nullable();
        });
    }
};
