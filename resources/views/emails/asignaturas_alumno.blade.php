<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Asignaturas</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Hola {{ $alumno->nombre }} {{ $alumno->apellido }},</h2>
    <p>A continuación se detallan las asignaturas en las que estás inscrito actualmente:</p>

    @if($horarios->isEmpty())
        <p>No tienes asignaturas programadas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Asignatura</th>
                    <th>Horario</th>
                    <th>Horas Lectivas</th>
                    <th>Profesor(es) Asignado(s)</th>
                    <th>Enlace Zoom</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horarios as $horario)
                    <tr>
                        <td>{{ $horario->asignatura->codigo_asignatura ?? 'N/A' }}</td>
                        <td>{{ $horario->asignatura->nombre_asignatura }}</td>
                        <td>{{ $horario->horario }}</td>
                        <td>{{ $horario->horas_lectivas }}</td>
                        <td>
                            @if($horario->profesores->isNotEmpty())
                                {{ $horario->profesores->map(function($p) { return $p->nombre . ' ' . $p->apellido; })->implode(', ') }}
                            @else
                                Sin profesor asignado
                            @endif
                        </td>
                        <td>
                            @if($horario->sesion_zoom)
                                <a href="{{ $horario->sesion_zoom }}">Unirse</a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <br>
    <p>Saludos cordiales,</p>
    <p>Control de Posgrado</p>
</body>
</html>
