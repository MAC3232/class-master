<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;  // Para roles y permisos

class User extends Authenticatable
{
    use HasFactory, HasRoles,  CrudTrait, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiantes::class, 'user_id');
    }


}

