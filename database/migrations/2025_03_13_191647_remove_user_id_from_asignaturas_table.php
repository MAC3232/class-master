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
        Schema::table('asignaturas', function (Blueprint $table) {
            //

            $table->dropForeign(['user_id']);
            // Elimina la columna user_id
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignaturas', function (Blueprint $table) {
             // Vuelve a agregar la columna user_id
             $table->unsignedBigInteger('user_id');
             // Vuelve a agregar la clave foránea
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
