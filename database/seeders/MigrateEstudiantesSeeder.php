<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrateEstudiantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estudiantes = DB::table('estudiantes')->get();

foreach ($estudiantes as $estudiante) {
    $userId = DB::table('users')->insertGetId([
        'name'       => $estudiante->nombre,
        'email'      => $estudiante->correo,
        'password'   => Hash::make($estudiante->cedula), // Usar la cédula como contraseña
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('estudiantes')->where('id', $estudiante->id)->update([
        'user_id' => $userId,
    ]);
}
    }
}
