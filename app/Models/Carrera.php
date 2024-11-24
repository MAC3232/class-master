<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrera extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $table = 'carreras';
    protected $fillable = [
        'nombre',
        'facultad_id'
    ];

    public function facultad()
    {
        return $this->belongsTo(Facultad::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiantes::class);
    }
    public function asignaturas()
{
    return $this->hasMany(Asignaturas::class, 'carrera_id');
}

}
