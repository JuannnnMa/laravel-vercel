<form action="{{ route('admin.comunicados.update', $comunicado->id) }}" method="POST" id="editComunicadoForm">
    @csrf
    @method('PUT')
    <div class="form-group mb-4">
        <label class="font-weight-bold text-dark">Título del Comunicado</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-heading"></i></span>
            </div>
            <input type="text" name="titulo" class="form-control form-control-lg" required maxlength="191" value="{{ $comunicado->titulo }}">
        </div>
    </div>

    <div class="form-group mb-4">
        <label class="font-weight-bold text-dark">Mensaje</label>
        <textarea name="mensaje" class="form-control" rows="6" required>{{ $comunicado->mensaje }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">Tipo de Comunicado</label>
                <input type="text" name="tipo" list="tipos-list" class="form-control" required value="{{ $comunicado->tipo }}">
                <datalist id="tipos-list">
                    <option value="Aviso General">
                    <option value="Reunión">
                    <option value="Evento">
                    <option value="Urgente">
                    <option value="Recordatorio">
                </datalist>
                <small class="form-text text-muted">Puede escribir su propio tipo.</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">Destinatarios</label>
                <select name="destinatarios" class="form-control custom-select">
                    <option value="todos" {{ $comunicado->destinatarios == 'todos' ? 'selected' : '' }}>Todos (Comunidad Educativa)</option>
                    <option value="docentes" {{ $comunicado->destinatarios == 'docentes' ? 'selected' : '' }}>Solo Docentes</option>
                    <option value="padres" {{ $comunicado->destinatarios == 'padres' ? 'selected' : '' }}>Solo Padres de Familia</option>
                    <option value="estudiantes" {{ $comunicado->destinatarios == 'estudiantes' ? 'selected' : '' }}>Solo Estudiantes</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="font-weight-bold">Fecha del Evento (Opcional)</label>
                <input type="datetime-local" name="fecha_evento" class="form-control" value="{{ $comunicado->fecha_evento ? $comunicado->fecha_evento->format('Y-m-d\TH:i') : '' }}">
            </div>
        </div>
    </div>

    <div class="modal-footer border-top pt-3">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Guardar Cambios</button>
    </div>
</form>

<script>
    $('#editComunicadoForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let btn = $('#btnActualizar');
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
                btn.prop('disabled', false).text('Guardar Cambios');
                
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
