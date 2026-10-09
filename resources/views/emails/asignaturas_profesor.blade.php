<!DOCTYPE html>
<html>
<head>
    <title>Carga de Asignaturas</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Estimado(a) {{ $profesor->nombre }} {{ $profesor->apellido }},</h2>
    <p>A continuación se detalla su carga de asignaturas actual junto con los alumnos inscritos:</p>

    @if($horarios->isEmpty())
        <p>No tiene asignaturas programadas actualmente.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Asignatura</th>
                    <th>Grupo</th>
                    <th>Horario</th>
                    <th>Alumnos Inscritos</th>
                    <th>Enlace Zoom</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horarios as $horario)
                    @php
                        $alumnos = collect();
                        if ($horario->grupo && $horario->grupo->alumnos) {
                            $alumnos = $horario->grupo->alumnos;
                        }
                    @endphp
                    <tr>
                        <td>{{ $horario->asignatura->codigo_asignatura ?? 'N/A' }}</td>
                        <td>{{ $horario->asignatura->nombre_asignatura }}</td>
                        <td>{{ $horario->grupo ? $horario->grupo->nombre_grupo : 'N/A' }}</td>
                        <td>{{ $horario->horario }} ({{ $horario->horas_lectivas }}h)</td>
                        <td>
                            <strong>Total: {{ $alumnos->count() }}</strong><br>
                            @if($alumnos->isNotEmpty())
                                <small>{{ $alumnos->map(function($a) { return $a->nombre . ' ' . $a->apellido; })->implode(', ') }}</small>
                            @else
                                <span style="color:red;">Sin alumnos</span>
                            @endif
                        </td>
                        <td>
                            @if($horario->sesion_zoom)
                                <a href="{{ $horario->sesion_zoom }}">Enlace</a>
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
