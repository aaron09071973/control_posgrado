<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AsignaturasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $horarios;

    public function __construct($horarios)
    {
        $this->horarios = $horarios;
    }

    public function collection()
    {
        return $this->horarios;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Asignatura',
            'Horario',
            'Horas Lectivas',
            'Enlace Zoom',
            'Profesor(es) Asignado(s)'
        ];
    }

    public function map($horario): array
    {
        $profesores = $horario->profesores->map(function($p) {
            return $p->nombre . ' ' . $p->apellido;
        })->implode(', ');

        return [
            $horario->asignatura->codigo_asignatura ?? 'N/A',
            $horario->asignatura->nombre_asignatura,
            $horario->horario,
            $horario->horas_lectivas,
            $horario->sesion_zoom ?? 'N/A',
            $profesores ?: 'Sin profesor asignado'
        ];
    }
}
