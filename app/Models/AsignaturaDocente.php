<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignaturaDocente extends Model
{
    protected $table = 'asignatura_docente';

    public function asignatura()
    {
        return $this->belongsTo(Asignaturas::class);
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

