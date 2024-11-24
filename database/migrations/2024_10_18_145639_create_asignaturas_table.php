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
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo', 50)->unique();
            $table->text('competencia');
            $table->text('descripcion_competencia');
            $table->text('justificacion');
            $table->enum('nivel_formacion', ['doctorado', 'maestria', 'especializacion', 'tecnologia', 'tecnico profesional', 'universitario'])->default('universitario');
            $table->string('catalogo', 50);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('facultad_id')->constrained('facultades')->onDelete('cascade');
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->string('area_formacion');
            $table->string('modalidad');
            $table->string('correquisitos')->nullable();
            $table->string('prerequisitos')->nullable();
            $table->enum('tipo_asignatura', ['practica', 'teorica', 'teorica con laboratorio'])->default('teorica');
            $table->integer('creditos_academicos');
            $table->integer('horas_presenciales');
            $table->integer('horas_independientes');
            $table->integer('horas_totales');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaturas');
    }
};
