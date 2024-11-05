<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrera::create(['nombre' => 'Ingeniería de Sistemas', 'facultad_id' => 1]);
        Carrera::create(['nombre' => 'Ingeniería Civil', 'facultad_id' => 1]);
        Carrera::create(['nombre' => 'Historia', 'facultad_id' => 2]);
        Carrera::create(['nombre' => 'Medicina', 'facultad_id' => 3]);
        Carrera::create(['nombre' => 'Administración de Empresas', 'facultad_id' => 4]);
    }
}
