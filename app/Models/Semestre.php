<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Semestres';
    protected $primaryKey = 'id_semestre';
    public $timestamps = false;

    protected $fillable = [
        'id_posgrado',
        'numero_semestre',
        'desc_semestre',
        'fecha_inicio',
        'fecha_termino',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_termino' => 'date',
        ];
    }

    public function posgrado()
    {
        return $this->belongsTo(Posgrado::class, 'id_posgrado', 'id_posgrado');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_semestre', 'id_semestre');
    }
}
