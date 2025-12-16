@extends('layouts.admin-layout')

@section('title', 'Gestión de Notas - Panel Profesor')
@section('page-title', 'Gestión de Notas')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Seleccione una Materia</h2>
    </div>
    <table id="notasIndexTable" class="table responsive nowrap" style="width:100%">
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
                    <a href="{{ route('docente.notas.show', $asignacion->id) }}" class="btn btn-primary">
                        Gestionar Notas
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
    #notasIndexTable tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05);
    }
</style>
<script>
    $(document).ready(function() {
        $('#notasIndexTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
