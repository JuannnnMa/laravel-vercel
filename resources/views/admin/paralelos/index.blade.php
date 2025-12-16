@extends('layouts.admin-layout')

@section('title', 'Paralelos')
@section('page-title', 'Gestión de Paralelos')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Lista de Paralelos</h2>
        <button onclick="openModal('{{ route('admin.paralelos.create') }}', 'Nuevo Paralelo')" class="btn btn-primary">
            <i class='fas fa-plus'></i> Nuevo Paralelo
        </button>
    </div>
    
    <div style="padding: 20px;">
        <table class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Paralelo</th>
                    <th>Turno</th>
                    <th>Aula</th>
                    <th>Capacidad</th>
                    <th>Inscritos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paralelos as $paralelo)
                <tr>
                    <td>{{ $paralelo->curso->nombre }}</td>
                    <td>{{ $paralelo->nombre }}</td>
                    <td>{{ ucfirst($paralelo->turno) }}</td>
                    <td>{{ $paralelo->aula ?? '-' }}</td>
                    <td>{{ $paralelo->cupo_maximo }}</td>
                    <td>{{ $paralelo->inscripciones_count ?? 0 }}</td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-warning" onclick="openModal('{{ route('admin.paralelos.edit', $paralelo->id) }}', 'Editar Paralelo')" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form id="delete-form-{{ $paralelo->id }}" action="{{ route('admin.paralelos.destroy', $paralelo->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $paralelo->id }}')" title="Eliminar">
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