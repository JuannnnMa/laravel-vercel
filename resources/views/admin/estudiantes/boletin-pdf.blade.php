<!DOCTYPE html>
<html>
<head>
    <title>Libreta Escolar Electrónica</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header-text {
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header-text h3 {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .promedio-col {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .firma-section {
            margin-top: 80px;
            display: flex;
            justify-content: space-around;
        }
        .firma-box {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 200px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-text">
            <h2>Libreta Escolar Electrónica</h2>
            <h3>Educación Secundaria Comunitaria Productiva</h3>
        </div>
    </div>

    <!-- Info Estudiante -->
    <table style="margin-bottom: 20px; border: none;">
        <tr style="border: none;">
            <td style="border: none; text-align: left; width: 50%;">
                <b>Unidad Educativa:</b> COLEGIO SAN SIMÓN DE AYACUCHO<br>
                <b>Código RUDE:</b> {{ $estudiante->codigo_estudiante }}<br>
                <b>Apellidos y Nombres:</b> {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }} {{ $estudiante->nombres }}
            </td>
            <td style="border: none; text-align: left; width: 50%;">
                <b>Gestión:</b> {{ date('Y') }}<br>
                <b>Curso:</b> {{ $inscripcion->paralelo->curso->nombre }} "{{ $inscripcion->paralelo->nombre }}"<br>
                <b>Turno:</b> {{ $inscripcion->paralelo->turno ?? 'MAÑANA' }}
            </td>
        </tr>
    </table>

    <!-- Tabla de Notas -->
    <h4 style="text-align: center; background: #e0e0e0; padding: 5px; border: 1px solid #000; margin-bottom: 0;">Evaluación (Ser, Saber, Hacer y Decidir)</h4>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Campo de Saberes y Conocimientos</th>
                <th rowspan="2">Áreas Curriculares</th>
                <th colspan="3">Valoración Cuantitativa</th> 
                <th colspan="2">Promedio Anual</th>
            </tr>
            <tr>
                <th>1er Trim</th>
                <th>2do Trim</th>
                <th>3er Trim</th>
                <th>Numeral</th>
                <th>Literal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boletin as $materiaId => $datos)
                <tr>
                    <!-- Campo (Area) -->
                    <td style="vertical-align: middle; font-weight: bold;">
                        {{ $datos['materia']->area->nombre ?? 'CAMPO' }}
                    </td>
                    <!-- Area (Materia) -->
                    <td class="text-left">
                        {{ strtoupper($datos['materia']->nombre) }}
                    </td>
                    
                    <!-- Trimestres -->
                    <td>{{ isset($datos[1]) ? $datos[1]->nota_final : '' }}</td>
                    <td>{{ isset($datos[2]) ? $datos[2]->nota_final : '' }}</td>
                    <td>{{ isset($datos[3]) ? $datos[3]->nota_final : '' }}</td>
                    
                    <!-- Promedio -->
                    <td class="promedio-col">{{ $datos['promedio_anual'] }}</td>
                    <td style="font-size: 8px;">{{ $datos['literal'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="firma-section">
        <div class="firma-box">
            <br>
            Director(a)
        </div>
        <div class="firma-box">
            <br>
            Tutor(a)
        </div>
    </div>
</body>
</html>
