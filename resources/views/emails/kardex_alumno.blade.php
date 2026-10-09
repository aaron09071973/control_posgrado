<!DOCTYPE html>
<html>
<head>
    <title>Boleta de Calificaciones (Kardex)</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge-green { color: #15803d; font-weight: bold; }
        .badge-yellow { color: #a16207; font-weight: bold; }
        .badge-red { color: #b91c1c; font-weight: bold; }
        .badge-gray { color: #4b5563; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Hola {{ $alumno->nombre }} {{ $alumno->apellido }},</h2>
    <p>A continuación se detalla tu Boleta de Calificaciones (Kardex) oficial del sistema de posgrados:</p>

    @if($horarios->isEmpty())
        <p>No tienes asignaturas programadas ni calificaciones registradas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Asignatura</th>
                    <th>Horario</th>
                    <th>Horas Lectivas</th>
                    <th>Calificación</th>
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
                            @php
                                $val = floatval($horario->calificacion);
                                $class = 'badge-gray';
                                if (is_numeric($horario->calificacion)) {
                                    if ($val >= 8.0) {
                                        $class = 'badge-green';
                                    } elseif ($val >= 6.0) {
                                        $class = 'badge-yellow';
                                    } else {
                                        $class = 'badge-red';
                                    }
                                }
                            @endphp
                            <span class="{{ $class }}">
                                {{ $horario->calificacion ?? 'Sin Calificación' }}
                            </span>
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
