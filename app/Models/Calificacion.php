<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Calificaciones';
    protected $primaryKey = 'id_calificacion';
    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
        'id_asignatura',
        'calificacion',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }
}
