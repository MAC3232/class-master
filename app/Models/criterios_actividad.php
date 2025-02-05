<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class criterios_actividad extends Model
{
    use HasFactory;
protected $table = 'criterios_actividad';

    protected $fillable = [
        'rubrica_actividad_id',
        'nombre',
        'descripcion',
    ];

    public function rubrica()
    {
        return $this->belongsTo(rubrica_actividad::class );
    }

    public function descripciones()
    {
        return $this->hasMany(descripciones_actividad_critrio_nivel::class, 'criterio_id');
    }
}
