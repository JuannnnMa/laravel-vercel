<form action="{{ route('admin.tutores.store') }}" method="POST" id="createTutorForm">
    @csrf
    <div class="form-row">
        <div class="form-group">
            <label for="ci">CI *</label>
            <input type="text" class="form-control" id="ci" name="ci" required>
        </div>
        <div class="form-group">
            <label for="nombres">Nombres *</label>
            <input type="text" class="form-control" id="nombres" name="nombres" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="apellido_paterno">Apellido Paterno *</label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
        </div>
        <div class="form-group">
            <label for="apellido_materno">Apellido Materno *</label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña </label>
            <div class="input-group">
                <input type="password" name="password" id="password" minlength="6" class="form-control" placeholder="Mínimo 6 caracteres">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-eye" onclick="togglePassword(this)"></i></span>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password">RepetirContraseña </label>
            <div class="input-group">
                <input type="password" name="password" id="password" minlength="6" class="form-control" placeholder="Mínimo 6 caracteres">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-eye" onclick="togglePassword(this)"></i></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="celular">Celular</label>
            <input type="text" class="form-control" id="celular" name="celular">
        </div>
    </div>

    <div class="form-group">
        <label for="ocupacion">Ocupación</label>
        <input type="text" class="form-control" id="ocupacion" name="ocupacion">
    </div>

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
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

    $('#createTutorForm').on('submit', function(e) {
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
