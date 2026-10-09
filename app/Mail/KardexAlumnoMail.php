<?php

namespace App\Mail;

use App\Models\Alumno;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KardexAlumnoMail extends Mailable
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
            subject: 'Boleta de Calificaciones (Kardex) - ' . $this->alumno->nombre . ' ' . $this->alumno->apellido,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kardex_alumno',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
