<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estrategia extends Model
{
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
