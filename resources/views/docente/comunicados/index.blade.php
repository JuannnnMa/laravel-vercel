@extends('layouts.admin-layout')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Comunicados y Avisos</h2>
    </div>

    <div style="padding: 20px;">
        <table class="display responsive nowrap table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Título</th>
                    <th>Mensaje</th>
                    <th>Publicado Por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comunicados as $comunicado)
                <tr>
                    <td>{{ $comunicado->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $comunicado->tipo == 'urgente' ? 'danger' : ($comunicado->tipo == 'reunion' ? 'warning' : 'info') }}">
                            {{ ucfirst($comunicado->tipo) }}
                        </span>
                    </td>
                    <td>{{ $comunicado->titulo }}</td>
                    <td>{{ Str::limit($comunicado->mensaje, 50) }}</td>
                    <td>{{ $comunicado->creador->name ?? 'Administración' }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="openModal('{{ route('docente.comunicados.show', $comunicado->id) }}', 'Detalle del Comunicado')">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
