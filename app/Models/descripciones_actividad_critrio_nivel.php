<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class descripciones_actividad_critrio_nivel extends Model
{
    use HasFactory;

    protected $table = 'descripciones_actividad_critrio_nivel';
    protected $fillable = [
        'criterio_id',
        'nivel_desempeno_id',
        'descripcion',
    ];

    public function criterio()
    {
        return $this->belongsTo(criterios_actividad::class);
    }

    public function nivelDesempeno()
    {
        return $this->belongsTo(niveles_desempeno_actividad::class);
    }
}
 