<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EjeContenido extends Model
{
    protected $table = 'ejes_contenido';
    protected $fillable = [
        'descripcion',
        'ra_id',
    ];

    // Relación con RA
    public function ra()
    {
        return $this->belongsTo(RA::class);
    }

}
 