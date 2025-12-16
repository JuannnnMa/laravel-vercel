@extends('layouts.admin-layout')

@section('title', 'Tutores - ' . $estudiante->nombres . ' ' . $estudiante->apellido_paterno)

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Tutores de: {{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</h2>
        <div>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Agregar Tutor
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div style="padding: 20px;">
        <table id="tutoresTable" style="width:100%">
            <thead>
                <tr>
                    <th>Nombres y Apellidos</th>
                    <th>CI</th>
                    <th>Celular</th>
                    <th>Parentesco</th>
                    <th>Principal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiante->tutores as $tutor)
                <tr>
                    <td>{{ $tutor->nombres }} {{ $tutor->apellido_paterno }} {{ $tutor->apellido_materno }}</td>
                    <td>{{ $tutor->ci }}</td>
                    <td>{{ $tutor->celular }}</td>
                    <td>
                        <span class="badge badge-info">{{ $tutor->pivot->tipo_parentesco }}</span>
                    </td>
                    <td>
                        @if($tutor->pivot->es_principal)
                            <span class="badge badge-success">SÍ</span>
                        @else
                            <span class="badge badge-secondary">NO</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.parentescos.destroy', $tutor->pivot->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Está seguro de desvincular este tutor?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Desvincular
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Agregar Tutor -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.estudiantes.attach-tutor', $estudiante->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Tutor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Buscar Tutor *</label>
                        <select name="tutor_id" id="tutor_select" class="form-control" required style="width: 100%;">
                            <option value="">Seleccione un tutor...</option>
                            @foreach($tutoresDisponibles as $tutor)
                                <option value="{{ $tutor->id }}">
                                    {{ $tutor->apellido_paterno }} {{ $tutor->apellido_materno }} {{ $tutor->nombres }} - CI: {{ $tutor->ci }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Tipo de Parentesco *</label>
                            <select name="tipo_parentesco" class="form-control" required>
                                <option value="PADRE">Padre</option>
                                <option value="MADRE">Madre</option>
                                <option value="ABUELO">Abuelo</option>
                                <option value="ABUELA">Abuela</option>
                                <option value="TIO">Tío</option>
                                <option value="TIA">Tía</option>
                                <option value="HERMANO">Hermano</option>
                                <option value="HERMANA">Hermana</option>
                                <option value="TUTOR_LEGAL">Tutor Legal</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>&nbsp;</label>
                            <div class="custom-control custom-checkbox" style="padding-top: 10px;">
                                <input type="checkbox" class="custom-control-input" id="es_principal" name="es_principal" value="1">
                                <label class="custom-control-label" for="es_principal">Es tutor principal</label>
                            </div>
                        </div>
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

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // DataTable
        $('#tutoresTable').DataTable({
            destroy: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });

        // Select2 con filtro automático por apellido del estudiante
        $('#tutor_select').select2({
            placeholder: 'Buscar tutor...',
            allowClear: true,
            dropdownParent: $('#createModal'),
            matcher: function(params, data) {
                // Si no hay término de búsqueda, mostrar todos
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Búsqueda case-insensitive
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }

                return null;
            }
        });

        // Auto-filtrar por apellido del estudiante al abrir el modal
        $('#createModal').on('shown.bs.modal', function () {
            var apellidoEstudiante = '{{ $estudiante->apellido_paterno }}';
            $('#tutor_select').select2('open');
            // Pre-filtrar por apellido
            setTimeout(function() {
                $('.select2-search__field').val(apellidoEstudiante).trigger('input');
            }, 100);
        });
    });

    function openCreateModal() {
        $('#createModal').modal('show');
    }
</script>
@endsection
