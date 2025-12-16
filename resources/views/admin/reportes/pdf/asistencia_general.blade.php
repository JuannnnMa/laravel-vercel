<!DOCTYPE html>
<html>
<head>
    <title>Reporte Asistencia General</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .left { text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte General de Asistencia</h2>
        <h3>Unidad Educativa San Simón de Ayacucho</h3>
        <p>Curso: {{ $curso->nombre }} - Paralelo: {{ $paralelo->nombre }}</p>
        <p>Gestión: {{ $anioActual->nombre ?? date('Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Apellidos y Nombres</th>
                <th>Presentes</th>
                <th>Ausentes</th>
                <th>Licencias</th>
                <th>Atrasos</th>
                <th>Total Días Registrados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estudiantes as $index => $est)
                @php
                    // Count only for the current inscription/year
                     $inscripcion = $est->inscripciones->first();
                     // Calculate summaries from the loaded relation
                     $asistencias = $inscripcion->asistencias; 
                     $presentes = $asistencias->where('estado', 'presente')->count();
                     $ausentes = $asistencias->where('estado', 'ausente')->count();
                     $licencias = $asistencias->where('estado', 'licencia')->count();
                     $atrasos = $asistencias->where('estado', 'atraso')->count();
                     $total = $asistencias->count();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left">{{ $est->apellido_paterno }} {{ $est->apellido_materno }} {{ $est->nombres }}</td>
                    <td>{{ $presentes }}</td>
                    <td>{{ $ausentes }}</td>
                    <td>{{ $licencias }}</td>
                    <td>{{ $atrasos }}</td>
                    <td>{{ $total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
