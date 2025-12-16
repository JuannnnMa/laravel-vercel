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
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .escudo {
            width: 80px;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
        }
        .header-text {
            text-align: center;
            width: 100%;
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
            display: table;
            width: 100%;
            margin-bottom: 15px;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 2px 10px;
        }
        .label {
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 10px;
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .area-col {
            text-align: left;
            width: 30%;
        }
        .campo-col {
            text-align: left;
            width: 20%;
            writing-mode: vertical-lr;
            transform: rotate(180deg);
        }
        .promedio-col {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .firma-section {
            margin-top: 80px;
            text-align: center;
            display: flex;
            justify-content: center;
        }
        .firma-box {
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 200px;
            margin: 0 auto;
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

    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell"><span class="label">Unidad Educativa:</span> COLEGIO SAN SIMÓN DE AYACUCHO</div>
            <div class="info-cell"><span class="label">Gestión:</span> {{ date('Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><span class="label">Código RUDE:</span> {{ $inscripcion->estudiante->codigo_estudiante }}</div>
            <div class="info-cell"><span class="label">Curso:</span> {{ $asignacion->paralelo->curso->nombre }} "{{ $asignacion->paralelo->nombre }}"</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><span class="label">Apellidos y Nombres:</span> {{ $inscripcion->estudiante->apellido_paterno }} {{ $inscripcion->estudiante->apellido_materno }} {{ $inscripcion->estudiante->nombres }}</div>
            <div class="info-cell"><span class="label">Turno:</span> {{ $asignacion->paralelo->turno ?? 'MAÑANA' }}</div>
        </div>
    </div>

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
                <th>1er Trimestre</th>
                <th>2do Trimestre</th>
                <th>3er Trimestre</th>
                <th>Numeral</th>
                <th>Literal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Area Name from Materia's Area relation if exists, otherwise "VIDA TIERRA TERRITORIO" placeholder -->
                <td style="text-align: center; font-weight: bold;">
                    {{ $asignacion->materia->area->nombre ?? 'CAMPO' }}
                </td>
                <td style="text-align: left;">
                    {{ strtoupper($asignacion->materia->nombre) }}
                </td>
                
                <!-- Notas Trimestres -->
                <td>{{ $notasPorTrimestre[1]->nota_final ?? 0 }}</td>
                <td>{{ $notasPorTrimestre[2]->nota_final ?? 0 }}</td>
                <td>{{ $notasPorTrimestre[3]->nota_final ?? 0 }}</td>
                
                <!-- Promedio Anual -->
                <td class="promedio-col">{{ $promedioAnual }}</td>
                
                <!-- Literal Anual (Calculado simple) -->
                <td style="font-size: 9px;">
                    {{ mostrarLiteral($promedioAnual) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="firma-section">
        <div class="firma-box">
            {{ $profesor->nombres }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}<br>
            DOCENTE
        </div>
    </div>
</body>
</html>

@php
function mostrarLiteral($numero) {
    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return strtoupper($formatter->format($numero));
}
@endphp
