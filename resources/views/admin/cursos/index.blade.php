@extends('layouts.admin-layout')

@section('title', 'Cursos')
@section('page-title', 'Gestión de Cursos')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Lista de Cursos</h2>
        <button class="btn btn-primary" onclick="openModal('{{ route('admin.cursos.create') }}', 'Nuevo Curso')">
            <i class="fas fa-plus"></i> Nuevo Curso
        </button>
    </div>
    
    <div style="padding: 20px;">
        <table class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Nivel</th>
                    <th>Nombre</th>
                    <th>Paralelos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cursos as $curso)
                <tr>
                    <td>{{ $curso->nivel }}</td>
                    <td>{{ $curso->nombre }}</td>
                    <td>{{ $curso->paralelos->count() }}</td>
                    <td>
                        @if($curso->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($curso->estado)
                                <button class="btn btn-warning" onclick="openModal('{{ route('admin.cursos.edit', $curso->id) }}', 'Editar Curso')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form id="delete-form-{{ $curso->id }}" action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $curso->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.cursos.activate', $curso->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Activar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <!-- DataTables handles empty tables gracefully, but this is fine too -->
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