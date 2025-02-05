<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rubrica_actividad extends Model
{
    protected $table = 'rubrica_actividad';
    protected $fillable = [
        'nombre',
        'descripcion',
        'actividad_id',
    ];

    public function criterios()
    {
        return $this->hasMany(criterios_actividad::class);
    }

    public function selectstudent()
    {
        return $this->hasMany(SelectCriterioEstudent::class);
    }
      public function nivelesDesempeno()
    {
        return $this->hasMany(niveles_desempeno_actividad::class, 'rubrica_actividad_id');
    }
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}
