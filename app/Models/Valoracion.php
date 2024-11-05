<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{

    protected $table = 'valoraciones';
    protected $fillable = [
        'actividad_id', 
        'estudiante_id', 
        'nota'
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
    
    public function estudiante()
    {
        return $this->belongsTo(Estudiantes::class, 'estudiante_id');
    }
    
}

