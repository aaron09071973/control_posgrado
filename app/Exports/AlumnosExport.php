<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlumnosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $alumnos;

    public function __construct($alumnos)
    {
        $this->alumnos = $alumnos;
    }

    public function collection()
    {
        return $this->alumnos;
    }

    public function headings(): array
    {
        return [
            'Cuenta',
            'Nombre Completo',
            'Correo Electrónico',
            'Generación',
            'Fecha Inscripción',
            'Estado'
        ];
    }

    public function map($alumno): array
    {
        return [
            $alumno->Cta_alumno,
            $alumno->nombre . ' ' . $alumno->apellido,
            $alumno->correo_electronico,
            $alumno->generacion,
            isset($alumno->pivot->fecha_inscripcion) ? \Carbon\Carbon::parse($alumno->pivot->fecha_inscripcion)->format('d/m/Y') : 'N/A',
            $alumno->is_finalizado ? 'Finalizado' : 'En Curso'
        ];
    }
}
