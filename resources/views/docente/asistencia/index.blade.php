@extends('layouts.admin-layout')

@section('title', 'Asistencia - Panel Profesor')
@section('page-title', 'Registro de Asistencia')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Registro de Asistencia</h2>
    </div>
    <table id="asistenciaIndexTable" class="table responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Curso</th>
                <th>Paralelo</th>
                <th>Estudiantes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asignaciones as $asignacion)
            <tr>
                <td>{{ $asignacion->materia->nombre }}</td>
                <td>{{ $asignacion->paralelo->curso->nombre }}</td>
                <td>{{ $asignacion->paralelo->nombre }}</td>
                <td>{{ $asignacion->paralelo->inscripciones->where('estado', 1)->count() }}</td>
                <td>
                    <a href="{{ route('docente.asistencia.show', $asignacion->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list"></i> Ver Registro Asistencia
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<style>
    /* Manual striping to avoid JS selector conflict */
    #asistenciaIndexTable tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05);
    }
</style>
<script>
    $(document).ready(function() {
        $('#asistenciaIndexTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
