<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    protected $table = 'bitacoras';
    protected $primaryKey = 'id_bitacora';

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'registro_id',
        'detalles',
    ];

    protected $casts = [
        'detalles' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public static function logEvent($accion, $tabla_afectada, $registro_id = null, $detalles = [])
    {
        if (Auth::check()) {
            self::create([
                'id_usuario' => Auth::id(),
                'accion' => $accion,
                'tabla_afectada' => $tabla_afectada,
                'registro_id' => $registro_id,
                'detalles' => $detalles,
            ]);
        }
    }
}
