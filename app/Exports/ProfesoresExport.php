<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProfesoresExport implements FromCollection, WithHeadings, WithMapping
{
    protected $profesores;

    public function __construct($profesores)
    {
        $this->profesores = $profesores;
    }

    public function collection()
    {
        return $this->profesores;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre Completo',
            'Correo Electrónico',
            'Teléfono',
            'Fecha Asignación'
        ];
    }

    public function map($profesor): array
    {
        return [
            $profesor->cod_profesor ?? 'N/A',
            $profesor->nombre . ' ' . $profesor->apellido,
            $profesor->correo_electronico,
            $profesor->telefono ?? 'N/A',
            isset($profesor->pivot->fecha_asignacion) ? \Carbon\Carbon::parse($profesor->pivot->fecha_asignacion)->format('d/m/Y') : 'N/A'
        ];
    }
}
