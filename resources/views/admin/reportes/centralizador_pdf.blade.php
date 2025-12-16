<!DOCTYPE html>
<html>
<head>
    <title>Centralizador de Notas</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h3>Centralizador de Notas</h3>
    <p>Curso: {{ $paralelo->curso->nombre }} "{{ $paralelo->nombre }}"</p>
    
    <table>
        <thead>
            <tr>
                <th class="text-left">Estudiante</th>
                @foreach($materias as $materia)
                <th>{{ substr($materia->nombre, 0, 3) }}</th>
                @endforeach
                <th>Prom</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $inscripcion)
            <tr>
                <td class="text-left">{{ $inscripcion->estudiante->apellido_paterno }} {{ $inscripcion->estudiante->nombres }}</td>
                @foreach($materias as $materia)
                <td>{{ $matriz[$inscripcion->id][$materia->id] ?? '-' }}</td>
                @endforeach
                <td>{{ $promedios[$inscripcion->id] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
