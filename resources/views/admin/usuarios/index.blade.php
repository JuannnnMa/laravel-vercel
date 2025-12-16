@extends('layouts.admin-layout')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')

@section('content')
<div class="table-container">
    <div class="table-header d-flex justify-content-between align-items-center">
        <h2>Lista de Usuarios</h2>
        <div class="d-flex align-items-center">
             <div class="input-group mr-3" style="width: 250px;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-filter text-muted"></i></span>
                </div>
                <select id="roleFilter" class="form-control border-left-0" style="border-radius: 0 4px 4px 0;">
                    <option value="">Todos los Roles</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Profesor">Profesor</option>
                    <option value="Tutor">Tutor</option>
                    <option value="Estudiante">Estudiante</option>
                </select>
            </div>
        </div>
    </div>
    
    <div style="padding: 20px;">
        <table id="tableUsuarios" class="table table-hover responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                           
                            <div>
                                <div class="font-weight-bold">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-pill badge-{{ $user->role->nombre == 'admin' ? 'danger' : ($user->role->nombre == 'profesor' ? 'info' : ($user->role->nombre == 'estudiante' ? 'success' : 'secondary')) }} px-3 py-1">
                            {{ ucfirst($user->role->nombre) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openPasswordModal({{ $user->id }}, '{{ $user->email }}')">
                            <i class="fas fa-key"></i> Cambiar Contraseña
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title font-weight-bold">Cambiar Contraseña</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="passwordForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 bg-soft-info text-info mb-4">
                        <i class="fas fa-info-circle mr-1"></i> Cambiando contraseña para: <strong id="modalUserEmail"></strong>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Nueva Contraseña</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-muted"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control border-left-0" required minlength="6" placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary px-4 font-weight-bold" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning px-4 font-weight-bold text-white">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    .bg-soft-info {
        background-color: rgba(54, 185, 204, 0.1);
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable manualmente
        var table = $('#tableUsuarios').DataTable({
            destroy: true, // Asegura que se reinicialice si existe
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            order: [[0, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            }
        });

        // Filtro por Rol personalizado
        $('#roleFilter').on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            table.column(2).search(val ? val : '', true, false).draw();
        });
    });

    function openPasswordModal(id, email) {
        $('#passwordForm').attr('action', '/admin/usuarios/' + id + '/password');
        $('#modalUserEmail').text(email);
        $('#passwordModal').modal('show');
    }

    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#passwordModal').modal('hide');
                form[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cambiar la contraseña. Intente nuevamente.'
                });
            },
            complete: function() {
                btn.prop('disabled', false).text('Guardar Cambios');
            }
        });
    });
</script>
@endpush
