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
        Schema::create('qr_asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignatura_id')->constrained()->onDelete('cascade');
            $table->string('token', 64)->unique(); // Token único de 64 caracteres
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_asistencias');
    }
};
