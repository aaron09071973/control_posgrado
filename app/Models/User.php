<?php

namespace App\Models;

use App\Traits\RegistraBitacora;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'Tipo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use RegistraBitacora;
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's role based on their Tipo.
     */
    protected function role(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                (isset($attributes['Tipo']) && $attributes['Tipo'] == 1) 
                    ? 'administrador' 
                    : 'consulta',
        );
    }
}
