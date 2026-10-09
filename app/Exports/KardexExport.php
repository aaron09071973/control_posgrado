<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KardexExport implements FromCollection, WithHeadings, WithMapping
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
            'Código de Asignatura',
            'Nombre de Asignatura',
            'Horario',
            'Horas Lectivas',
            'Calificación'
        ];
    }

    public function map($horario): array
    {
        return [
            $horario->asignatura->codigo_asignatura ?? 'N/A',
            $horario->asignatura->nombre_asignatura,
            $horario->horario,
            $horario->horas_lectivas,
            $horario->calificacion ?? 'Sin Calificación'
        ];
    }
}
