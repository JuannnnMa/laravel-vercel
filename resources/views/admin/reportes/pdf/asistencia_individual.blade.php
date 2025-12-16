<!DOCTYPE html>
<html>
<head>
    <title>Asistencia Individual</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de Asistencia Individual</h2>
        <h3>Unidad Educativa San Simón de Ayacucho</h3>
        <p>Gestión: {{ $anioActual->nombre ?? date('Y') }}</p>
    </div>

    <div class="info">
        <p><strong>Estudiante:</strong> {{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</p>
        <p><strong>Código:</strong> {{ $estudiante->codigo_estudiante }}</p>
        <p><strong>CI:</strong> {{ $estudiante->ci }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Materia</th>
                <th>Estado</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asistencias as $a)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $a->asignacion->materia->nombre ?? 'N/A' }}</td>
                    <td>{{ ucfirst($a->estado) }}</td>
                    <td>{{ $a->observacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
