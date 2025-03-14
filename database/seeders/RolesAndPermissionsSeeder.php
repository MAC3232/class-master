<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Permisos que se utilizan en la aplicación
        $permissions = [
            'manage-users',
            'create-users',
            'manage-assignments',
            'manage-students',
            'manage-asignaturas',
            'create-asignaturas',
            // Agrega otros permisos según tus necesidades
        ];


        // Crea cada permiso (si no existe)
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // roles que se usan
        $roles = [
            'super-admin',
            'admin',
            'docente',
            'estudiante',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Asigna permisos al rol super-admin (tiene todos los permisos)
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $superAdminRole->syncPermissions(Permission::all());

        // Puedes asignar permisos específicos a otros roles, por ejemplo:
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->syncPermissions(['manage-users', 'manage-assignments']);

        // Para 'docente' y 'estudiante' asigna solo lo que necesites
    }
}
