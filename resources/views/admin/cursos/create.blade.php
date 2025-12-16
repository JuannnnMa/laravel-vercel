<form action="{{ route('admin.cursos.store') }}" method="POST" id="createCursoForm">
    @csrf
    <div class="form-group">
        <label for="nombre">Nombre del Curso *</label>
        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Primero de Primaria" required>
    </div>
    <div class="form-group">
        <label for="nivel">Nivel *</label>
        <select class="form-control" id="nivel" name="nivel" required>
            <option value="">Seleccionar...</option>
            <option value="Inicial">Inicial</option>
            <option value="Primaria">Primaria</option>
            <option value="Secundaria">Secundaria</option>
        </select>
    </div>
    <div class="form-group">
        <label for="grado">Grado *</label>
        <select class="form-control" id="grado" name="grado" required>
            <option value="">Seleccionar...</option>
            @for($i = 1; $i <= 6; $i++)
                <option value="{{ $i }}">{{ $i }}°</option>
            @endfor
        </select>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
    </div>
    
    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Curso</button>
    </div>
</form>

<script>
    $('#createCursoForm').on('submit', function(e) {
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
                btn.prop('disabled', false).text('Guardar Curso');
                
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
