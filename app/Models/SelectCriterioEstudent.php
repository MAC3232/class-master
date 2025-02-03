<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectCriterioEstudent extends Model
{
    use HasFactory;

    protected $table = 'criterio_desempeno_estudiante';
    protected $fillable = ['estudiante_id', 'criterio_id', 'nivel_desempeno_id'];

    // Relación con Usuario
    public function student() {
        return $this->belongsTo(Estudiantes::class);
    }

    // Relación con Criterio
    public function criterio() {
        return $this->belongsTo(criterios_actividad::class);
    }

    // Relación con Nivel de Desempeño
    public function nivelDesempeno() {
        return $this->belongsTo(niveles_desempeno_actividad::class);
    }
}
