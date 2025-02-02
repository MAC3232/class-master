<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignaturaEstudiante extends Model
{
    use HasFactory, CrudTrait;


    protected $table = 'asignatura_estudiante';
    public $timestamps = false; 
    protected $fillable = [
        'asignatura_id',
        'estudiante_id',
    ];

    public function assignment()
    {
        return $this->belongsTo(Asignaturas::class, 'asignatura_id');
    }

    public function student()
    {
        return $this->belongsTo(Estudiantes::class, 'estudiante_id');
    }

}
