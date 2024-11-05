<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RA extends Model
{

    protected $table = 'ra';
    protected $fillable = [
        'nombre', 
        'ponderacion', 
        'descripcion', 
        'corte',
        'rubrica_id'
    ];

    public function rubrica()
    {
        return $this->belongsTo(Rubrica::class);
    }

    public function criterios()
    {
        return $this->hasMany(Criterio::class, 'ra_id');
    }
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'ra_id');
    }
    public function estrategias()
    {
        return $this->hasMany(Estrategia::class, 'ra_id');
    }

    // Relación con Eje de Contenido
    public function ejesContenido()
    {
        return $this->hasMany(EjeContenido::class, 'ra_id');
    }
}

