<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Horario - {{ $seccion->grado->nombre }} {{ $seccion->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .materia {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .docente {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Horario de Clases</h1>
    <h2 style="text-align: center;">{{ $seccion->grado->nombre }} - Paralelo {{ $seccion->nombre }}</h2>
    
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
            </tr>
        </thead>
        <tbody>
            @php
                $horas = ['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '14:00-15:00', '15:00-16:00'];
                $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
            @endphp
            
            @foreach($horas as $hora)
            <tr>
                <td><strong>{{ $hora }}</strong></td>
                @foreach($dias as $dia)
                    <td>
                        @php
                            $clase = $horarios->get($dia)?->firstWhere('hora_inicio', explode('-', $hora)[0]);
                        @endphp
                        
                        @if($clase)
                            <div class="materia">{{ $clase->materia->nombre }}</div>
                            <div class="docente">{{ $clase->docente->nombreCompleto() }}</div>
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
