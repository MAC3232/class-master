<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use CrudTrait;

    protected $table = 'actividades';
    protected $fillable = [
        'nombre', 
        'fecha', 
        'ponderacion', 
        'ra_id', 
        'asignatura_id'
    ];

    public function ra()
    {
        return $this->belongsTo(RA::class);
    }
    public function rubrica()
    {
        return $this->hasOne(rubrica_actividad::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignaturas::class);
    }
    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class, 'actividad_id');
    }
    
    public function estudiante()
{
    return $this->belongsTo(Estudiantes::class);
}


    
}

