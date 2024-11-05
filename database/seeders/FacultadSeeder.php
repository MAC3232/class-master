<?php

namespace Database\Seeders;

use App\Models\Facultad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Facultad::create(['nombre' => 'Facultad de Ciencias e Ingenierías']);
        Facultad::create(['nombre' => 'Facultad de Humanidades']);
        Facultad::create(['nombre' => 'Facultad de Medicina']);
        Facultad::create(['nombre' => 'Facultad de Ciencias Económicas']);
    }
}
