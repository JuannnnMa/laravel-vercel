<form action="{{ route('admin.profesores.store') }}" method="POST" id="createProfesorForm">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="ci" class="font-weight-bold">CI <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ci" name="ci" required maxlength="15">
        </div>
        <div class="form-group col-md-3">
            <label for="nombres" class="font-weight-bold">Nombres <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombres" name="nombres" required maxlength="50">
        </div>
        <div class="form-group col-md-3">
            <label for="apellido_paterno" class="font-weight-bold">Apellido Paterno <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required maxlength="50">
        </div>
        <div class="form-group col-md-3">
            <label for="apellido_materno" class="font-weight-bold">Apellido Materno <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required maxlength="50">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" required maxlength="100" class="form-control" id="email">
        </div>
        <div class="form-group col-md-6">
             <label class="font-weight-bold">Contraseña <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="password" name="password" required minlength="6" class="form-control" placeholder="Mínimo 6 caracteres">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-eye" onclick="togglePassword(this)"></i></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Celular</label>
            <input type="text" class="form-control" id="celular" name="celular" maxlength="15">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Especialidad</label>
            <input type="text" name="especialidad" maxlength="100" class="form-control" id="especialidad">
        </div>
    </div>
    
    <div class="form-row"> 
        <div class="form-group col-md-12">
            <label class="font-weight-bold">Fecha de Ingreso <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required value="{{ date('Y-m-d') }}">
        </div>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
    </div>
</form>

<script>
    function togglePassword(icon) {
        var input = $(icon).closest('.input-group').find('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(icon).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(icon).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }

    $('#createProfesorForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                btn.prop('disabled', false).text('Guardar');
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, messages) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud.'
                    });
                }
            }
        });
    });
</script>
