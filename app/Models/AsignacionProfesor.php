<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AsignacionProfesor extends Pivot
{
    protected $table = 'Asignaciones_Profesores';
    protected $primaryKey = 'id_asignacion_profesor';
    public $timestamps = false;

    protected $fillable = [
        'id_profesor',
        'id_horario_asignatura',
        'fecha_asignacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_asignacion' => 'datetime',
        ];
    }
}
