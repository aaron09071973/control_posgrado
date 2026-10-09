<?php

namespace App\Traits;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

trait RegistraBitacora
{
    protected static function bootRegistraBitacora()
    {
        static::created(function ($model) {
            self::registrarMovimiento($model, 'CREAR');
        });

        static::updated(function ($model) {
            self::registrarMovimiento($model, 'ACTUALIZAR');
        });

        static::deleted(function ($model) {
            self::registrarMovimiento($model, 'ELIMINAR');
        });
    }

    protected static function registrarMovimiento($model, $accion)
    {
        // Don't log if running from console/seeder without auth (unless desired)
        if (!Auth::check()) {
            return;
        }

        $cambios = [];
        if ($accion === 'ACTUALIZAR') {
            $cambios = [
                'antes' => $model->getOriginal(),
                'despues' => $model->getChanges(),
            ];
        } elseif ($accion === 'CREAR' || $accion === 'ELIMINAR') {
            $cambios = $model->toArray();
        }

        Bitacora::create([
            'id_usuario' => Auth::id(),
            'accion' => $accion,
            'tabla_afectada' => class_basename($model),
            'registro_id' => $model->getKey(),
            'detalles' => $cambios,
        ]);
    }
}
