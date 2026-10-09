<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posgrado extends Model
{
    use RegistraBitacora;
    use HasFactory;

    protected $table = 'Posgrados';
    protected $primaryKey = 'id_posgrado';
    public $timestamps = false;

    protected $fillable = [
        'nombre_posgrado',
        'codigo_programa',
    ];

    public function semestres()
    {
        return $this->hasMany(Semestre::class, 'id_posgrado', 'id_posgrado');
    }
}
