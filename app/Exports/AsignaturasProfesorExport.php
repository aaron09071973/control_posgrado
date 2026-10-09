<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AsignaturasProfesorExport implements FromCollection, WithHeadings, WithMapping
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
            'Grupo',
            'Horario',
            'Horas Lectivas',
            'Enlace Zoom',
            'Total Alumnos',
            'Alumnos Asignados'
        ];
    }

    public function map($horario): array
    {
        $alumnos = collect();
        if ($horario->grupo && $horario->grupo->alumnos) {
            $alumnos = $horario->grupo->alumnos;
        }

        $nombresAlumnos = $alumnos->map(function($a) {
            return $a->nombre . ' ' . $a->apellido;
        })->implode(', ');

        $nombreGrupo = $horario->grupo ? $horario->grupo->nombre_grupo : 'N/A';

        return [
            $horario->asignatura->codigo_asignatura ?? 'N/A',
            $horario->asignatura->nombre_asignatura,
            $nombreGrupo,
            $horario->horario,
            $horario->horas_lectivas,
            $horario->sesion_zoom ?? 'N/A',
            $alumnos->count(),
            $nombresAlumnos ?: 'Sin alumnos inscritos'
        ];
    }
}
