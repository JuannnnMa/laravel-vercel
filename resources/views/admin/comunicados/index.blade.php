@extends('layouts.admin-layout')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Gestión de Comunicados</h2>
        <button type="button" class="btn btn-primary" onclick="openModal('{{ route('admin.comunicados.create') }}', 'Nuevo Comunicado')">
            <i class="fas fa-plus-circle"></i> Nuevo Comunicado
        </button>
    </div>

    <div style="padding: 20px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table class="display responsive nowrap" id="tablaComunicados" style="width:100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Destinatarios</th>
                    <th>Evento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comunicados as $comunicado)
                <tr>
                    <td>{{ $comunicado->created_at->format('d/m/Y') }}</td>
                    <td>{{ $comunicado->titulo }}</td>
                    <td>
                        @php
                            $badgeClass = 'badge-info';
                            if(strtolower($comunicado->tipo) == 'urgente') $badgeClass = 'badge-danger';
                            elseif(strtolower($comunicado->tipo) == 'reunion') $badgeClass = 'badge-warning';
                            elseif(strtolower($comunicado->tipo) == 'evento') $badgeClass = 'badge-success';
                            elseif(strtolower($comunicado->tipo) == 'recordatorio') $badgeClass = 'badge-primary';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($comunicado->tipo) }}</span>
                    </td>
                    <td>
                        @if($comunicado->destinatarios == 'todos')
                            <span class="badge badge-secondary">Todos</span>
                        @else
                            {{ ucfirst($comunicado->destinatarios) }}
                        @endif
                    </td>
                    <td>
                        @if($comunicado->fecha_evento)
                            {{ $comunicado->fecha_evento->format('d/m/Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-warning" onclick="openModal('{{ route('admin.comunicados.edit', $comunicado->id) }}', 'Editar Comunicado')" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.comunicados.destroy', $comunicado->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este comunicado?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#tablaComunicados')) {
            $('#tablaComunicados').DataTable().destroy();
        }
        
        $('#tablaComunicados').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            order: [[ 0, "desc" ]],
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });
        
        $('[data-toggle="tooltip"]').tooltip();

        @if($errors->any())
            openModal('{{ route('admin.comunicados.create') }}', 'Nuevo Comunicado');
        @endif
    });
</script>
@endpush

@endsection
