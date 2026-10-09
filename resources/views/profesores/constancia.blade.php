<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia - {{ $profesor->nombre }} {{ $profesor->apellido }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 20px 40px;
            color: #333;
        }
        .header-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .logo-cell-left {
            width: 15%;
            text-align: left;
            vertical-align: middle;
        }
        .logo-cell-right {
            width: 15%;
            text-align: right;
            vertical-align: middle;
        }
        .title-cell {
            width: 70%;
            text-align: center;
            vertical-align: middle;
        }
        .logo-cell-left img, .logo-cell-right img {
            width: 80px;
        }
        .title-cell h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }
        .title-cell h2 {
            font-size: 13px;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .document-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        .content {
            font-size: 15px;
            line-height: 1.5;
            text-align: justify;
            margin-bottom: 25px;
        }
        .data-table {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .data-table td strong {
            display: inline-block;
            width: 180px;
        }
        .signature {
            position: absolute;
            bottom: 40px;
            left: 0;
            width: 100%;
            text-align: center;
        }
        .signature .line {
            width: 300px;
            border-top: 1px solid #333;
            margin: 0 auto 10px auto;
        }
        .signature p {
            margin: 0;
            font-weight: bold;
        }
        .signature span {
            font-size: 12px;
            color: #555;
        }
        .date {
            text-align: right;
            margin-bottom: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-cell-left">
                <?php
                    $path = public_path('unam.png');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    if (file_exists($path)) {
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        echo '<img src="'.$base64.'" alt="Logo UNAM">';
                    }
                ?>
            </td>
            <td class="title-cell">
                <h1 style="margin-bottom: 5px;">Universidad Nacional Autónoma de México</h1>
                <h2>Centro Peninsular en Humanidades y Ciencias Sociales</h2>
            </td>
            <td class="logo-cell-right">
                <?php
                    $path = public_path('escudo.jpg');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    if (file_exists($path)) {
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        echo '<img src="'.$base64.'" alt="Logo Escudo">';
                    }
                ?>
            </td>
        </tr>
    </table>

    <div class="document-title">
        Constancia de Asignación Docente
    </div>

    <div class="date">
        Mérida, Yucatán a {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
    </div>

    <div class="content">
        <p>A quien corresponda:</p>
        
        <p>Por medio de la presente se hace constar que el/la profesor(a) <strong>{{ mb_strtoupper($profesor->nombre . ' ' . $profesor->apellido) }}</strong>, 
        ha sido asignado(a) como docente en esta sede foránea de la UNAM en la Ciudad de Mérida, Yucatán impartiendo la siguiente asignatura y cumpliendo con la carga horaria correspondiente.</p>
        
        <p>A continuación se detallan los datos de la asignación registrados en nuestro sistema:</p>

        <table class="data-table">
            <tr>
                <td><strong>Asignatura:</strong></td>
                <td>{{ mb_strtoupper($horario->asignatura->nombre_asignatura) }}</td>
            </tr>
            <tr>
                <td><strong>Tipo de Asignatura:</strong></td>
                <td>{{ $horario->asignatura->tipo_asignatura }}</td>
            </tr>
            <tr>
                <td><strong>Posgrado:</strong></td>
                <td>{{ $horario->grupo->semestre->posgrado->nombre_posgrado }}</td>
            </tr>
            <tr>
                <td><strong>Semestre:</strong></td>
                <td>{{ $horario->grupo->semestre->desc_semestre }} ({{ $horario->grupo->semestre->numero_semestre }}º)</td>
            </tr>
            <tr>
                <td><strong>Grupo:</strong></td>
                <td>{{ $horario->grupo->nombre_grupo }}</td>
            </tr>
            <tr>
                <td><strong>Periodo Lectivo:</strong></td>
                <td>{{ ucfirst(\Carbon\Carbon::parse($horario->grupo->semestre->fecha_inicio)->locale('es')->translatedFormat('F Y')) }} - {{ ucfirst(\Carbon\Carbon::parse($horario->grupo->semestre->fecha_termino)->locale('es')->translatedFormat('F Y')) }}</td>
            </tr>
            <tr>
                <td><strong>Total de horas lectivas:</strong></td>
                <td>{{ $horario->asignatura->hrslectivas }} horas</td>
            </tr>
        </table>

        <p>Con un periodo de exámenes, evaluaciones y de Intersemestral del {{ $horario->grupo?->periodo_intersemestral }}</p>

        <p>Se expide la presente para los fines y usos que a la interesada(o) convengan.</p>
    </div>

    <div class="signature">
        <div class="line"></div>
        <p>Dra. María Carolina Depetris</p>
        <p>Directora del CEPHCIS</p>
        <span>Centro Peninsular en Humanidades y Ciencias Sociales</span>
    </div>

</body>
</html>
