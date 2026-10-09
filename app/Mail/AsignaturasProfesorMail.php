<?php

namespace App\Mail;

use App\Models\Profesor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AsignaturasProfesorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $profesor;
    public $horarios;

    public function __construct(Profesor $profesor, $horarios)
    {
        $this->profesor = $profesor;
        $this->horarios = $horarios;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Carga de Asignaturas - ' . $this->profesor->nombre . ' ' . $this->profesor->apellido,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.asignaturas_profesor',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
