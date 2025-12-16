<!DOCTYPE html>
<html>
<head>
    <title>Reporte Centralizador de Notas</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 2px; text-align: center; overflow: hidden; }
        th { background-color: #f2f2f2; height: 30px; }
        .name-col { width: 200px; text-align: left; padding-left: 5px; }
        .vertical { transform: rotate(-90deg); white-space: nowrap; height: 100px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Centralizador de Notas (Promedio Anual)</h2>
        <h3>Unidad Educativa San Simón de Ayacucho</h3>
        <p>Curso: {{ $curso->nombre }} - Paralelo: {{ $paralelo->nombre }}</p>
        <p>Gestión: {{ $anioActual->nombre ?? date('Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th class="name-col">Apellidos y Nombres</th>
                @foreach($materias as $mat)
                    <th>
                        <div style="font-size: 8px;">{{ substr($mat->nombre, 0, 10) }}</div>
                    </th>
                @endforeach
                <th style="width: 40px; font-weight: bold;">PROM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
                @php
                    $sum = 0;
                    $count = 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="name-col">{{ $row['estudiante']->apellido_paterno }} {{ $row['estudiante']->apellido_materno }} {{ $row['estudiante']->nombres }}</td>
                    @foreach($materias as $mat)
                        <td>
                            @php
                                $nota = $row['notas'][$mat->id] ?? 0;
                                $sum += $nota;
                                $count++;
                            @endphp
                            {{ $nota > 0 ? $nota : '-' }}
                        </td>
                    @endforeach
                    <td style="font-weight: bold; background-color: #eee;">
                        {{ $count > 0 ? round($sum / $count) : 0 }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
