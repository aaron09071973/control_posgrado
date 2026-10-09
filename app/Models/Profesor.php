<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Profesores';
    protected $primaryKey = 'id_profesor';
    public $timestamps = false;

    protected $fillable = [
        'cod_profesor',
        'nombre',
        'apellido',
        'correo_electronico',
        'telefono',
        'adscripcion',
    ];

    public function horariosAsignaturas()
    {
        return $this->belongsToMany(HorarioAsignatura::class, 'Asignaciones_Profesores', 'id_profesor', 'id_horario_asignatura')
                    ->withPivot('id_asignacion_profesor', 'fecha_asignacion');
    }
}
