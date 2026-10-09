<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Asignaturas';
    protected $primaryKey = 'id_asignatura';
    public $timestamps = false;

    protected $fillable = [
        'nombre_asignatura',
        'codigo_asignatura',
        'Horario',
        'hrslectivas',
        'tipo_asignatura',
    ];

    public function horariosAsignaturas()
    {
        return $this->hasMany(HorarioAsignatura::class, 'id_asignatura', 'id_asignatura');
    }
}
