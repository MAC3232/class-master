<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrAsistencias extends Model
{
    use HasFactory;

    protected $table = 'qr_asistencias';

    protected $fillable = [
        'asignatura_id',
        'token',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Genera automáticamente un token único de 64 caracteres al crear una nueva entrada
   

    /**
     * Relación con la asignatura.
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignaturas::class);
    }

    /**
     * Relación con el modelo Asistencia.
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
