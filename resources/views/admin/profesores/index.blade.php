@extends('layouts.admin-layout')

@section('title', 'Profesores')
@section('page-title', 'Gestión de Profesores')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Lista de Profesores</h2>
        <button onclick="openModal('{{ route('admin.profesores.create') }}', 'Nuevo Profesor')" class="btn btn-primary">
            <i class='fas fa-plus'></i> Nuevo Profesor
        </button>
    </div>
    
    <div style="padding: 20px;">
        <table class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Email</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profesores as $profesor)
                <tr>
                    <td>{{ $profesor->codigo_profesor }}</td>
                    <td>{{ $profesor->nombres }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}</td>
                    <td>{{ $profesor->ci }}</td>
                    <td>{{ $profesor->email }}</td>
                    <td>{{ $profesor->especialidad ?? 'N/A' }}</td>
                    <td>
                        @if($profesor->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($profesor->estado)
                                <button class="btn btn-warning" onclick="openModal('{{ route('admin.profesores.edit', $profesor->id) }}', 'Editar Profesor')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info" onclick="openModal('{{ route('admin.profesores.materias', $profesor->id) }}', 'Asignar Materias')" title="Materias">
                                    <i class="fas fa-book"></i>
                                </button>
                                <form id="delete-form-{{ $profesor->id }}" action="{{ route('admin.profesores.destroy', $profesor->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $profesor->id }}')" title="Desactivar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.profesores.activate', $profesor->id) }}" method="POST" style="display: inline;">
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
