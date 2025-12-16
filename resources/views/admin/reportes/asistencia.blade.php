@extends('layouts.admin-layout')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Estadísticas de Asistencia: {{ $paralelo->curso->nombre }} "{{ $paralelo->nombre }}"</h4>
        <a href="{{ request()->fullUrlWithQuery(['descargar' => 'true']) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th class="text-center text-success">Presentes</th>
                        <th class="text-center text-danger">Faltas</th>
                        <th class="text-center text-warning">Atrasos</th>
                        <th class="text-center text-info">Licencias</th>
                        <th class="text-center font-weight-bold">% Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscripciones as $inscripcion)
                    @php $stats = $estadisticas[$inscripcion->id]; @endphp
                    <tr>
                        <td>{{ $inscripcion->estudiante->apellido_paterno }} {{ $inscripcion->estudiante->nombres }}</td>
                        <td class="text-center">{{ $stats['presentes'] }}</td>
                        <td class="text-center">{{ $stats['faltas'] }}</td>
                        <td class="text-center">{{ $stats['atrasos'] }}</td>
                        <td class="text-center">{{ $stats['licencias'] }}</td>
                        <td class="text-center">
                            @if($stats['porcentaje'] < 70)
                                <span class="badge badge-danger">{{ $stats['porcentaje'] }}%</span>
                            @elseif($stats['porcentaje'] < 85)
                                <span class="badge badge-warning">{{ $stats['porcentaje'] }}%</span>
                            @else
                                <span class="badge badge-success">{{ $stats['porcentaje'] }}%</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
