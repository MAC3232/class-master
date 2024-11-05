<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{

    protected $table = 'criterios';
    protected $fillable = [
        'descripcion',  
        'ra_id'
    ];

    public function ra()
    {
        return $this->belongsTo(RA::class);
    }
}

