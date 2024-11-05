<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'asignatura_id',
        'codigo_estudiantil',
        'asistencia',
        'fecha',
    ];

    /**
     * Relación con la asignatura.
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignaturas::class);
    }
    public function estudiante()
    {
        return $this->belongsTo(Estudiantes::class, 'estudiante_id'); // Verifica que la columna 'estudiante_id' sea correcta
    }

    /**
     * Relación con el modelo QrAsistencia.
     */
    public function qrAsistencia()
    {
        return $this->belongsTo(QrAsistencias::class, 'asignatura_id', 'asignatura_id');
    }
}

