<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{


    public function asignatura()
    {
        return $this->belongsTo(Asignaturas::class);
    }
}

