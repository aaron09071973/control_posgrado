<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioAsignatura extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Horarios_Asignatura';
    protected $primaryKey = 'id_horario_asignatura';
    public $timestamps = false;

    protected $fillable = [
        'id_grupo',
        'id_asignatura',
        'horario',
        'horas_lectivas',
        'sesion_zoom',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }

    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'Asignaciones_Profesores', 'id_horario_asignatura', 'id_profesor')
                    ->withPivot('id_asignacion_profesor', 'fecha_asignacion');
    }
}
