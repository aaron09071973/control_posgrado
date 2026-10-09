<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AlumnoGrupo extends Pivot
{
    protected $table = 'Alumnos_Grupos';
    protected $primaryKey = 'id_alumno_grupo';
    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
        'id_grupo',
        'fecha_inscripcion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inscripcion' => 'datetime',
        ];
    }
}
