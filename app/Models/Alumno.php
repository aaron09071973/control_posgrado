<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Alumnos';
    protected $primaryKey = 'id_alumno';
    public $timestamps = false;

    protected $fillable = [
        'Cta_alumno',
        'nombre',
        'apellido',
        'correo_electronico',
        'generacion',
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'Alumnos_Grupos', 'id_alumno', 'id_grupo')
                    ->withPivot('id_alumno_grupo', 'fecha_inscripcion');
    }
}
