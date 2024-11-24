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
        Schema::create('niveles_desempeno_actividad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('rubrica_actividad_id')->constrained('rubrica_actividad')->onDelete('cascade');
            $table->float('puntaje_inicial')->nullable();
            $table->float('puntaje_final')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveles_desempeno_actividad');
    }
};
