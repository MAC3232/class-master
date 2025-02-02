<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubrica extends Model


{

    protected $table = 'rubricas';
    protected $fillable = [
        'nombre',
        'asignatura_id'
    ];



    public function asignatura()
    {
        return $this->belongsTo(Asignaturas::class);
    }

    public function ra()
    {
        return $this->hasMany(RA::class);
    }

}

 