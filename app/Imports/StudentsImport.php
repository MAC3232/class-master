<?php

namespace App\Imports;

use App\Models\Estudiantes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $career;

    public function __construct($career)
    {
        $this->career = $career; // Guardar la carrera recibida
    }

    public function model(array $row)
    {
        // Convertir claves a mayúsculas para evitar problemas con encabezados
        $row = array_change_key_case($row, CASE_UPPER);

        // Obtener datos del archivo
        $name = $row['NOMBRE'] ?? $row['NAME'] ?? null;
        $email = $row['CORREO'] ?? $row['EMAIL'] ?? null;
        $code = $row['CODIGO'] ?? $row['CODE'] ?? $row['CODIGO_ESTUDIANTIL'] ?? null;
        $dni = $row['CEDULA'] ?? $row['IDENTIFICACION'] ?? $row['DNI'] ?? null;

        // Verificar si las columnas requeridas existen
        if (!$name || !$email || !$code || !$dni) {
            Log::warning("Fila omitida: datos incompletos (Nombre: {$name}, Email: {$email}, Código: {$code}, DNI: {$dni}).");
            return null; // Omitir la fila
        }

        // Eliminar ceros a la izquierda en el código estudiantil
        $code = ltrim($code, '0');

        // Verificar si el estudiante ya existe
        $exists = Estudiantes::where('codigo_estudiantil', $code)->exists();
        if ($exists) {
            Log::info("El estudiante con código {$code} ya existe. Fila omitida.");
            return null; // Evitar duplicados
        }

        // Crear y devolver un nuevo estudiante
        return new Estudiantes([
            'nombre' => $name,
            'cedula' => $dni,
            'codigo_estudiantil' => $code,
            'correo' => $email,
            'carrera_id' => $this->career
        ]);
    }
}
