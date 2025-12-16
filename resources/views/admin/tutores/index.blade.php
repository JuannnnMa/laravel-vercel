@extends('layouts.admin-layout')

@section('title', 'Tutores')
@section('page-title', 'Gestión de Tutores')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Lista de Tutores</h2>
        <button onclick="openModal('{{ route('admin.tutores.create') }}', 'Nuevo Tutor')" class="btn btn-primary">
            <i class='fas fa-plus'></i> Nuevo Tutor
        </button>
    </div>
    
    <div style="padding: 20px;">
        <table class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Parentesco</th>
                    <th>Estudiantes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tutores as $tutor)
                <tr>
                    <td>{{ $tutor->nombres }} {{ $tutor->apellido_paterno }} {{ $tutor->apellido_materno }}</td>
                    <td>{{ $tutor->ci }}</td>
                    <td>{{ $tutor->celular ?? $tutor->telefono }}</td>
                    <td>{{ $tutor->email ?? 'N/A' }}</td>
                    <td>{{ ucfirst($tutor->parentesco ?? 'N/A') }}</td>
                    <td>
                        <span class="badge badge-info">{{ $tutor->estudiantes_count ?? 0 }}</span>
                    </td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-secondary" onclick="openModal('{{ route('admin.tutores.estudiantes', $tutor->id) }}', 'Estudiantes de {{ $tutor->nombres }}')" title="Ver Estudiantes">
                                <i class="fas fa-users"></i>
                            </button>
                            <button class="btn btn-warning" onclick="openModal('{{ route('admin.tutores.edit', $tutor->id) }}', 'Editar Tutor')" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form id="delete-form-{{ $tutor->id }}" action="{{ route('admin.tutores.destroy', $tutor->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $tutor->id }}')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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