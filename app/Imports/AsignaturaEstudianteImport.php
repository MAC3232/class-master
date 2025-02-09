<?php

namespace App\Imports;

use App\Models\AsignaturaEstudiante;
use App\Models\Estudiantes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AsignaturaEstudianteImport implements ToModel, WithHeadingRow
{
    protected $assignment;

    public function __construct($assignment)
    {
        $this->assignment = $assignment; // Guardar la asignatura recibida
    }

    public function model(array $row)
    {
        // Convertir claves a mayúsculas para evitar problemas con los encabezados
        $row = array_change_key_case($row, CASE_UPPER);

        // Obtener datos del archivo
        $code = $row['CODIGO'] ?? $row['CODE'] ?? $row['CODIGO_ESTUDIANTIL'] ?? null;
        $code = ltrim($code, '0'); // Eliminar ceros a la izquierda si los tiene



        // Buscar al estudiante
        $student = Estudiantes::where('codigo_estudiantil', 'LIKE', "%{$code}")->first();

        // Si no se encuentra, registrar el error y continuar con la siguiente fila
        if (!$student) {
            $error = "El estudiante con el codigo {$code} no se encuentra en el sistema";
            Session::push('error', $error);
            return null;
        }

        // Verificar si ya existe la relación en la tabla pivote
        $exists = AsignaturaEstudiante::where('asignatura_id', $this->assignment)
            ->where('estudiante_id', $student->id)
            ->exists();

        if ($exists) {

            return null;
        }

        // Insertar nueva relación
        return new AsignaturaEstudiante([
            'asignatura_id' => $this->assignment,
            'estudiante_id' => $student->id
        ]);
    }
}
