<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignaturaEstudiante extends Model
{
    protected $table = 'asignatura_estudiante';
    public function assignment()
    {
        return $this->belongsTo(Asignaturas::class, 'asignatura_id');
    }

    public function student()
    {
        return $this->belongsTo(Estudiantes::class, 'estudiante_id');
    }

}
