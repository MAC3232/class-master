<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asignaturas extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
        'nombre',
        'codigo',
        'competencia',
        'descripcion_competencia',
        'justificacion',
        'facultad_id',
        'carrera_id',
        'prerequisitos',
        'correquisitos',
        'user_id',
        'area_formacion',
        'tipo_asignatura',
        'nivel_formacion',
        'modalidad',
        'creditos_academicos',
        'horas_presenciales',
        'horas_independientes',
        'horas_totales',
        'catalogo'
    ];

    public function facultad()
    {
        return $this->belongsTo(Facultad::class);
    }
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'asignatura_id');
    }
    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function rubrica()
    {
        return $this->hasOne(Rubrica::class, 'asignatura_id');
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class );
    }


    public function students()
    {
        return $this->belongsToMany(Estudiantes::class, 'asignatura_estudiante',  'asignatura_id', 'estudiante_id');
    }
    public function qrAsistencias()
    {
        return $this->hasMany(QrAsistencias::class);
    }

    /**
     * Relación con el modelo Asistencia.
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'asignatura_id');
    }

    protected static function booted()
{
    static::created(function ($asignatura) {
        if (!$asignatura->rubrica) {
            $asignatura->rubrica()->create([
                'asignatura_id'=>$asignatura->id,
                'nombre' => 'Rúbrica de ' . $asignatura->nombre,
                'descripcion' => 'Descripción predeterminada para la rúbrica de esta asignatura.',
            ]);
        }
    });
}

}
