@extends('layouts.admin-layout')

@section('title', 'Mis Asesorías')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Cursos Asesorados</h2>
    </div>
    <div style="padding: 20px;">
        <table class="display responsive nowrap table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Anio Academico</th>
                    <th>Curso</th>
                    <th>Inscritos</th>
                    <th>Turno</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paralelosAsesorados as $paralelo)
                <tr>
                    <td>{{ $paralelo->anioAcademico->nombre }}</td>
                    <td>{{ $paralelo->curso->nombre_curso }} "{{ $paralelo->nombre }}"</td>
                    <td><span class="badge badge-info">{{ $paralelo->inscritos }} Estudiantes</span></td>
                    <td>{{ ucfirst($paralelo->turno) }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('docente.asesoria.show', $paralelo->id) }}'">
                           <i class="fas fa-eye"></i> Ver Detalle
                        </button>
                    </td>
                </tr>
                @empty
                <!-- Empty state handled by DataTables generally, but if collection empty, table empty -->
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
