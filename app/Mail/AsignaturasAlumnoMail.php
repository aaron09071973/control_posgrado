<?php

namespace App\Mail;

use App\Models\Alumno;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AsignaturasAlumnoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alumno;
    public $horarios;

    public function __construct(Alumno $alumno, $horarios)
    {
        $this->alumno = $alumno;
        $this->horarios = $horarios;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consulta de Asignaturas - ' . $this->alumno->nombre . ' ' . $this->alumno->apellido,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.asignaturas_alumno',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
