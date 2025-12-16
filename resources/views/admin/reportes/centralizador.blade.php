@extends('layouts.admin-layout')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Centralizador de Notas: {{ $paralelo->curso->nombre }} "{{ $paralelo->nombre }}"</h4>
        <a href="{{ request()->fullUrlWithQuery(['descargar' => 'true']) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        @foreach($materias as $materia)
                        <th class="text-center">{{ substr($materia->nombre, 0, 3) }}</th>
                        @endforeach
                        <th class="text-center bg-light">Prom</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscripciones as $inscripcion)
                    <tr>
                        <td>{{ $inscripcion->estudiante->apellido_paterno }} {{ $inscripcion->estudiante->nombres }}</td>
                        @foreach($materias as $materia)
                        <td class="text-center">
                            {{ $matriz[$inscripcion->id][$materia->id] ?? '-' }}
                        </td>
                        @endforeach
                        <td class="text-center font-weight-bold bg-light">
                            {{ $promedios[$inscripcion->id] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
