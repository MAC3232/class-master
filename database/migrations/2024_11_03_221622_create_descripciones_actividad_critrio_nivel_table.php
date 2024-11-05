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
        Schema::create('descripciones_actividad_critrio_nivel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterio_id')->constrained('criterios_actividad');
            $table->foreignId('nivel_desempeno_id')->constrained('niveles_desempeno_actividad')->onDelete('cascade');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descripciones_actividad_critrio_nivel');
    }
};
