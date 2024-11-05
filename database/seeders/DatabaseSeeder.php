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
        Role::firstOrCreate(['name' => 'docente']);


        $this->call(FacultadSeeder::class);
        $this->call(CarreraSeeder::class);
        //crear el usuario inicial
       $existingUser = User::where('email', 'admin@google.com')->first();

        if (!$existingUser) {
            // Si el usuario no existe, lo creamos
            $user = User::factory()->create([
                'email' => 'admin@google.com',
                'name' => 'Admin',
                'password' => 'admin', // Hasheado con bcrypt
            ]);
            $user2 = User::factory()->create([
                'email' => 'docente@google.com',
                'name' => 'Docente',
                'password' => 'docente', // Hasheado con bcrypt
            ]);

            // Asignar el rol predeterminado al usuario
            $user->assignRole('admin');
            $user2->assignRole('docente');
        } else {
            // Si el usuario ya existe, se puede actualizar o simplemente ignorar
            echo "El usuario con email juliobonifacio53@gmail.com ya existe.\n";
        }
    }
}
