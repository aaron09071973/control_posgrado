<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Grupos';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'id_semestre',
        'nombre_grupo',
        'periodo_intersemestral',
    ];

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre', 'id_semestre');
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'Alumnos_Grupos', 'id_grupo', 'id_alumno')
                    ->withPivot('id_alumno_grupo', 'fecha_inscripcion');
    }

    public function horariosAsignaturas()
    {
        return $this->hasMany(HorarioAsignatura::class, 'id_grupo', 'id_grupo');
    }
}
