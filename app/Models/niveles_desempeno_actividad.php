<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class niveles_desempeno_actividad extends Model
{
    use HasFactory;

    protected $table = 'niveles_desempeno_actividad';
    protected $fillable = [
        'nombre',
        'puntaje_inicial',
        'puntaje_final',
        'rubrica_actividad_id'
    ];

    public function rubricaActividad()
    {
        return $this->belongsTo(rubrica_actividad::class, 'rubrica_actividad_id');
    }

    public function descripciones()
    {
        return $this->hasMany(descripciones_actividad_critrio_nivel::class, 'nivel_desempeno_id');
    }
}
