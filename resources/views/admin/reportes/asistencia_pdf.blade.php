<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Asistencia</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h3>Reporte de Asistencia y Estadísticas</h3>
    <p>Curso: {{ $paralelo->curso->nombre }} "{{ $paralelo->nombre }}"</p>
    
    <table>
        <thead>
            <tr>
                <th class="text-left">Estudiante</th>
                <th>Presentes</th>
                <th>Faltas</th>
                <th>Atrasos</th>
                <th>Licencias</th>
                <th>% Asistencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $inscripcion)
            @php $stats = $estadisticas[$inscripcion->id]; @endphp
            <tr>
                <td class="text-left">{{ $inscripcion->estudiante->apellido_paterno }} {{ $inscripcion->estudiante->nombres }}</td>
                <td>{{ $stats['presentes'] }}</td>
                <td>{{ $stats['faltas'] }}</td>
                <td>{{ $stats['atrasos'] }}</td>
                <td>{{ $stats['licencias'] }}</td>
                <td>{{ $stats['porcentaje'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
