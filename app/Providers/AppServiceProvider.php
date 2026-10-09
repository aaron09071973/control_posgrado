<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\Bitacora;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function (Login $event) {
            Bitacora::create([
                'id_usuario' => $event->user->id,
                'accion' => 'INICIO SESION',
                'tabla_afectada' => 'Sistema',
                'registro_id' => $event->user->id,
                'detalles' => ['ip' => request()->ip(), 'agente' => request()->userAgent()],
            ]);
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                Bitacora::create([
                    'id_usuario' => $event->user->id,
                    'accion' => 'CIERRE SESION',
                    'tabla_afectada' => 'Sistema',
                    'registro_id' => $event->user->id,
                    'detalles' => ['ip' => request()->ip(), 'agente' => request()->userAgent()],
                ]);
            }
        });

        // Personalizar el correo electrónico de restablecimiento de contraseña en español
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Restablecer contraseña')
                ->greeting('¡Hola!')
                ->line('Recibiste este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
                ->action('Restablecer contraseña', $url)
                ->line('Este enlace para restablecer la contraseña expirará en ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutos.')
                ->line('Si no realizaste esta solicitud, no es necesario realizar ninguna otra acción.')
                ->salutation('Saludos, ' . config('app.name'));
        });
    }
}
