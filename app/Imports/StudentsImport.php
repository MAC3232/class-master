<?php

namespace App\Imports;

use App\Models\Estudiantes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $career;

    public function __construct($career)
    {
        $this->career = $career; // Guardar la carrera recibida
    }

    public function model(array $row)
    {
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
        $existsEmail = Estudiantes::where('correo', $email)->exists();

        if ($existsCode ||$existsDni || $existsEmail ) {

            $error = "⚠️ Estudiante {$name }  ya existe. Fila omitida.";

            Session::push('import_errors', $error);
            return null;
        }

        $success = "✅ Estudiante {$name} con codigo {$code} fue agregado correctamente";

        Session::push('success_imports', $success);

        // Si todo está bien, crear el registro
        return new Estudiantes([
            'nombre' => $name,
            'cedula' => $dni,
            'codigo_estudiantil' => $code,
            'correo' => $email,
            'carrera_id' => $this->career
        ]);
    }
}
