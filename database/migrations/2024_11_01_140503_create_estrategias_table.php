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
        Schema::create('estrategias', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion'); // Descripción de la estrategia
            $table->unsignedBigInteger('ra_id'); // Relación con RA
            $table->timestamps();

            // Clave foránea para RA
            $table->foreign('ra_id')->references('id')->on('ra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estrategias');
    }
};
