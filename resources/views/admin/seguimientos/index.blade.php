@extends('layouts.admin-layout')

@section('title', 'Kardex - ' . $estudiante->nombres . ' ' . $estudiante->apellido_paterno)

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Kardex: {{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</h2>
        <div>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Nueva Incidencia
            </button>
            <a href="{{ route('admin.estudiantes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-container">
        <div style="padding: 20px;">
            <table class="display responsive nowrap" id="kardexTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Gravedad</th>
                        <th>Estado</th>
                        <th>Curso</th>
                        <th>Registrado Por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seguimientos as $seg)
                    <tr>
                        <td>{{ $seg->fecha_registro ? $seg->fecha_registro->format('d/m/Y') : date('d/m/Y') }}</td>
                        <td>{{ $seg->titulo ?? 'Sin título' }}</td>
                        <td>
                            <span class="badge badge-{{ $seg->tipo_incidencia == 'CONDUCTA' ? 'danger' : ($seg->tipo_incidencia == 'ACADEMICO' ? 'warning' : 'info') }}">
                                {{ $seg->tipo_incidencia }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $seg->nivel_gravedad == 'ALTO' ? 'danger' : ($seg->nivel_gravedad == 'MEDIO' ? 'warning' : 'success') }}">
                                {{ $seg->nivel_gravedad }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $seg->estado == 'PENDIENTE' ? 'danger' : ($seg->estado == 'RESUELTO' ? 'success' : 'primary') }}">
                                {{ $seg->estado }}
                            </span>
                        </td>
                        <td>{{ $seg->inscripcion->paralelo->curso->nombre ?? '-' }} {{ $seg->inscripcion->paralelo->nombre ?? '-' }}</td>
                        <td>{{ $seg->profesor->name ?? 'Usuario Sistema' }}</td>
                        <td>
                            <div class="actions">
                                <button class="btn btn-info" onclick="editSeguimiento({{ $seg }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.seguimientos.destroy', $seg->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Está seguro de eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> <!-- Content loaded normally, but since we are Standard, we code form here -->
            <form action="{{ route('admin.seguimientos.store', $estudiante->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Incidencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Inscripcion ID check (assume active) -->
                    @php
                        $activeInscripcion = $estudiante->inscripciones->where('estado', 'activo')->first(); // Or latest
                    @endphp
                    @if($activeInscripcion)
                        <input type="hidden" name="inscripcion_id" value="{{ $activeInscripcion->id }}">
                    @else
                        <div class="alert alert-warning">El estudiante no está inscrito en la gestión actual. Se usará la última inscripción.</div>
                        <input type="hidden" name="inscripcion_id" value="{{ $estudiante->inscripciones->last()->id ?? '' }}">
                    @endif

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Título Breve *</label>
                            <input type="text" name="titulo" class="form-control" required placeholder="Ej. LLegada tarde recurrente">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Fecha</label>
                            <input type="text" class="form-control" value="{{ date('d/m/Y') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Tipo *</label>
                            <select name="tipo_incidencia" class="form-control">
                                <option value="CONDUCTA">CONDUCTA</option>
                                <option value="ACADEMICO">ACADÉMICO</option>
                                <option value="ASISTENCIA">ASISTENCIA</option>
                                <option value="SALUD">SALUD</option>
                                <option value="FAMILIAR">FAMILIAR</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Gravedad *</label>
                            <select name="nivel_gravedad" class="form-control">
                                <option value="BAJO">BAJO</option>
                                <option value="MEDIO">MEDIO</option>
                                <option value="ALTO">ALTO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Estado *</label>
                            <select name="estado" class="form-control">
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="REVISADO">REVISADO</option>
                                <option value="RESUELTO">RESUELTO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observación Detallada *</label>
                        <textarea name="observacion" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Incidencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Título Breve *</label>
                            <input type="text" name="titulo" id="edit_titulo" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Tipo *</label>
                            <select name="tipo_incidencia" id="edit_tipo" class="form-control">
                                <option value="CONDUCTA">CONDUCTA</option>
                                <option value="ACADEMICO">ACADÉMICO</option>
                                <option value="ASISTENCIA">ASISTENCIA</option>
                                <option value="SALUD">SALUD</option>
                                <option value="FAMILIAR">FAMILIAR</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Gravedad *</label>
                            <select name="nivel_gravedad" id="edit_gravedad" class="form-control">
                                <option value="BAJO">BAJO</option>
                                <option value="MEDIO">MEDIO</option>
                                <option value="ALTO">ALTO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Estado *</label>
                            <select name="estado" id="edit_estado" class="form-control">
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="REVISADO">REVISADO</option>
                                <option value="RESUELTO">RESUELTO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observación Detallada *</label>
                        <textarea name="observacion" id="edit_observacion" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kardexTable').DataTable({
            destroy: true
        });
    });

    function openCreateModal() {
        $('#createModal').modal('show');
    }

    function editSeguimiento(data) {
        let url = "{{ route('admin.seguimientos.update', ':id') }}";
        url = url.replace(':id', data.id);
        $('#editForm').attr('action', url);

        $('#edit_titulo').val(data.titulo);
        $('#edit_tipo').val(data.tipo_incidencia);
        $('#edit_gravedad').val(data.nivel_gravedad);
        $('#edit_estado').val(data.estado);
        $('#edit_observacion').val(data.observacion);

        $('#editModal').modal('show');
    }
</script>
@endpush
