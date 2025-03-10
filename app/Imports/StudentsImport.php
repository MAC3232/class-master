<?php

namespace App\Imports;

use App\Models\Estudiantes;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $career;

    public function __construct($career)
    {
        $this->career = $career; // Guardar la carrera recibida
    }

    public function model(array $row)
    {
        set_time_limit(300); // 5 minutos

        $row = array_change_key_case($row, CASE_UPPER);
        $name = $row['NOMBRE'] ?? $row['NAME'] ?? null;
        $email = $row['CORREO'] ?? $row['EMAIL'] ?? null;
        $code = $row['CODIGO'] ?? $row['CODE'] ?? $row['CODIGO_ESTUDIANTIL'] ?? null;
        $dni = $row['CEDULA'] ?? $row['IDENTIFICACION'] ?? $row['DNI'] ?? null;


        if (empty(trim($name)) && empty(trim($email)) && empty(trim($code)) && empty(trim($dni))) {
            return null; // Ignorar esta fila completamente
        }

        // Verificar si faltan datos
        if (!$name || !$email || !$code || !$dni) {
            $error = "❌ Fila omitida: Datos incompletos (Nombre: {$name}, Email: {$email}, Código: {$code}, DNI: {$dni}).";
            Session::push('import_errors', $error);
            return null;
        }

        // Verificar si el estudiante ya existe
        $existsCode = Estudiantes::where('codigo_estudiantil', $code)->exists();
        $existsDni = Estudiantes::where('cedula', $dni)->exists();
        $existsEmail = User::where('email', $email)->exists();

        if ($existsCode || $existsDni || $existsEmail) {
            $error = "⚠️ Estudiante {$name} ya existe. Fila omitida.";
            Session::push('import_errors', $error);
            return null;
        }

        $success = "✅ Estudiante {$name} con codigo {$code} fue agregado correctamente";
        Session::push('success_imports', $success);

        // Crear u  suario asociado
        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => bcrypt($dni), // Hash usando bcrypt
        ]);
        $user->assignRole('estudiante');


        // Crear estudiante asociado al usuario
        return new Estudiantes([

            'cedula'             => $dni,
            'codigo_estudiantil' => $code,
            'carrera_id'         => $this->career,
            'user_id'            => $user->id,
        ]);

    }
}
