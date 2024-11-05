<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facultad extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'facultades';

    protected $fillable = ['nombre'];

    public function carreras()
    {
        return $this->hasMany(Carrera::class);
    }
}

