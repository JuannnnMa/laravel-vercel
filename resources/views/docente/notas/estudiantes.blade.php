@extends('layouts.admin-layout')

@section('title', 'Estudiantes - ' . $asignacion->materia->nombre)
@section('page-title', 'Notas: ' . $asignacion->materia->nombre)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Listado de Estudiantes: {{ $asignacion->paralelo->curso->nombre }} "{{ $asignacion->paralelo->nombre }}"</h6>
                <a href="{{ route('docente.notas.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Cursos
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Apellidos y Nombres</th>
                                <th>Código</th>
                                <th>Estado</th>
                                <th class="text-center" style="width: 250px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($estudiantes as $index => $matricula)
                            <tr>
                                <td class="align-middle text-center font-weight-bold">{{ $index + 1 }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                       
                                        <div>
                                            <span class="font-weight-bold text-dark">{{ $matricula->estudiante->apellido_paterno }} {{ $matricula->estudiante->apellido_materno }}</span>
                                            <div class="small text-gray-600">{{ $matricula->estudiante->nombres }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">{{ $matricula->estudiante->codigo_estudiante }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-success px-2 py-1">INSCRITO</span>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('docente.notas.registrar', [$asignacion->id, $matricula->id]) }}" class="btn btn-primary btn-sm shadow-sm mr-1">
                                        <i class="fas fa-edit fa-sm"></i> Calificar
                                    </a>
                                    <a href="{{ route('docente.notas.descargar', [$asignacion->id, $matricula->id]) }}" class="btn btn-secondary btn-sm shadow-sm" target="_blank">
                                        <i class="fas fa-file-pdf fa-sm"></i> Boletín
                                    </a>
                                    <a href="{{ route('docente.seguimientos.index', $matricula->estudiante->id) }}" class="btn btn-dark btn-sm shadow-sm" title="Kardex">
                                        <i class="fas fa-address-card fa-sm"></i> Kardex
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-graduate fa-3x mb-3 text-gray-300"></i>
                                    <p class="mb-0">No hay estudiantes inscritos en esta sección.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            columnDefs: [
                { orderable: false, targets: 4 }
            ]
        });
    });
</script>
@endpush
@endsection
