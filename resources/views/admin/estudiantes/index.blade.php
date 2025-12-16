@extends('layouts.admin-layout')

@section('title', 'Estudiantes')
@section('page-title', 'Gestión de Estudiantes')

@section('content')
<!-- Filters -->
<!-- Filters -->
<div class="filters" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('admin.estudiantes') }}" style="display: flex; gap: 20px; width: 100%; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label style="font-weight: 600; margin-bottom: 8px; display: block;">Curso</label>
            <select name="curso_id" class="form-control" onchange="this.form.submit()">
                <option value="">Todos los cursos</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label style="font-weight: 600; margin-bottom: 8px; display: block;">Paralelo</label>
            <select name="paralelo_id" class="form-control" onchange="this.form.submit()">
                <option value="">Todos los paralelos</option>
                @foreach($paralelos as $paralelo)
                    <option value="{{ $paralelo->id }}" {{ request('paralelo_id') == $paralelo->id ? 'selected' : '' }}>
                        {{ $paralelo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label style="display: block; margin-bottom: 8px;">&nbsp;</label>
            <button type="submit" name="sin_inscribir" value="1" class="btn btn-secondary">
                Estudiantes sin inscribir
            </button>
        </div>
    </form>
    
    <!-- Report Buttons -->
    <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
        @if(request('curso_id') && request('paralelo_id'))
            <a href="{{ route('admin.estudiantes.reporte.asistencia-general', ['curso_id' => request('curso_id'), 'paralelo_id' => request('paralelo_id')]) }}" class="btn btn-info" style="color: white;">
                <i class="fas fa-file-pdf"></i> Descargar Asistencia
            </a>
            <a href="{{ route('admin.estudiantes.reporte.notas-general', ['curso_id' => request('curso_id'), 'paralelo_id' => request('paralelo_id')]) }}" class="btn btn-success" style="color: white;">
                <i class="fas fa-file-pdf"></i> Descargar Notas
            </a>
        @else
            <span class="text-muted" style="font-size: 0.9em; align-self: center;">Seleccione un curso y paralelo para descargar reportes.</span>
        @endif
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h2>Lista de Estudiantes</h2>
        <button onclick="openModal('{{ route('admin.estudiantes.create') }}', 'Nuevo Estudiante')" class="btn btn-primary">
            <i class='fas fa-plus'></i> Nuevo Estudiante
        </button>
    </div>
    
    <div style="padding: 20px;">
        <table class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estudiantes as $estudiante)   
                <tr>
                    <td>{{ $estudiante->codigo_estudiante }}</td>
                    <td>{{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</td>
                    <td>{{ $estudiante->ci }}</td>
                    <td>
                        @if($estudiante->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Retirado</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($estudiante->estado)
                                <button class="btn btn-info" onclick="openModal('{{ route('admin.estudiantes.notas', $estudiante->id) }}', 'Notas')" title="Ver Notas">
                                    <i class="fas fa-clipboard-list"></i>
                                </button>
                                
                                <button class="btn btn-success" onclick="openModal('{{ route('admin.estudiantes.asistencia', $estudiante->id) }}', 'Asistencia')" title="Ver Asistencia">
                                    <i class="fas fa-calendar-check"></i>
                                </button>

                                <a href="{{ route('admin.seguimientos.index', $estudiante->id) }}" class="btn btn-dark" title="Kardex (Seguimiento)">
                                    <i class="fas fa-address-card"></i>
                                </a>
                                <a href="{{ route('admin.estudiantes.tutores', $estudiante->id) }}" class="btn btn-warning" title="Tutores">
                                    <i class="fas fa-users"></i>
                                </a>
                                
                                <button class="btn btn-warning" onclick="openModal('{{ route('admin.estudiantes.edit', $estudiante->id) }}', 'Editar Estudiante')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                @if($estudiante->inscripciones->isEmpty())
                                <button class="btn btn-primary" onclick="openModal('{{ route('admin.estudiantes.inscripcion', $estudiante->id) }}', 'Inscribir Estudiante')" title="Inscribir">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                @endif
                                
                                <form id="delete-form-{{ $estudiante->id }}" action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $estudiante->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.estudiantes.activate', $estudiante->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Activar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Empty state handled by DataTables -->
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('table').DataTable();
    });
</script>
@endpush
