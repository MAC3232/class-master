<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'estudiante']);
        Role::firstOrCreate(['name' => 'docente']);
        Role::firstOrCreate(['name' => 'super-admin']);


        // $this->call(FacultadSeeder::class);
        // $this->call(CarreraSeeder::class);
        //crear el usuario inicial
       $existingUser = User::where('email', 'admin@google.com')->first();

        if (!$existingUser) {
            // Si el usuario no existe, lo creamos
            $user = User::factory()->create([
                'email' => 'admin@google.com',
                'name' => 'Admin',
                'password' => bcrypt('admin'), // Hasheado con bcrypt

            ]);

            $docente = User::factory()->create([
                'email' => 'docente@google.com',
                'name' => 'Docente',
                'password' => bcrypt('Docente'), // Hasheado con bcrypt
            ]);

            // Asignar el rol predeterminado al usuario
            $user->assignRole('admin');
            $docente->assignRole('docente');
        } else {
            // Si el usuario ya existe, se puede actualizar o simplemente ignorar
            echo "El usuario con email juliobonifacio53@gmail.com ya existe.\n";
        }
    }
}
