@extends('layouts.admin-layout')

@section('title', 'Materias')
@section('page-title', 'Gestión de Materias')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Lista de Materias</h2>
        <button onclick="openModal('{{ route('admin.materias.create') }}', 'Nueva Materia')" class="btn btn-primary">
            <i class='fas fa-plus'></i> Nueva Materia
        </button>
    </div>
    
    <div style="padding: 20px;">
        <table class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Área Curricular</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materias as $materia)
                <tr>
                    <td>{{ $materia->codigo }}</td>
                    <td>{{ $materia->nombre }}</td>
                    <td>{{ $materia->area->nombre ?? 'N/A' }}</td>
                    <td>
                        @if($materia->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($materia->estado)
                                <button class="btn btn-warning" onclick="openModal('{{ route('admin.materias.edit', $materia->id) }}', 'Editar Materia')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form id="delete-form-{{ $materia->id }}" action="{{ route('admin.materias.destroy', $materia->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $materia->id }}')" title="Desactivar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.materias.activate', $materia->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" title="Activar">
                                        <i class="fas fa-check"></i> Activar
                                    </button>
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