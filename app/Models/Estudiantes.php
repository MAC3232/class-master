<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estudiantes extends Model
{
    use HasFactory,  CrudTrait;

    protected $table = 'estudiantes';

    protected $fillable = [
        'cedula',
          'codigo_estudiantil',
           'carrera_id',
           'user_id'
        ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class, 'estudiante_id');
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


    public function assignments()
    {
        return $this->belongsToMany(Asignaturas::class, 'asignatura_estudiante' , 'estudiante_id', 'asignatura_id');
    }
}

