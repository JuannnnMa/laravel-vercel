<form action="{{ route('admin.paralelos.update', $paralelo->id) }}" method="POST" id="editParaleloForm">
    @csrf
    @method('PUT')
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="curso_id" class="font-weight-bold">Curso <span class="text-danger">*</span></label>
            <select class="form-control" id="curso_id" name="curso_id" required>
                <option value="">Seleccionar Curso...</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ $paralelo->curso_id == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="nombre" class="font-weight-bold">Nombre del Paralelo <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $paralelo->nombre }}" required maxlength="50">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="turno" class="font-weight-bold">Turno <span class="text-danger">*</span></label>
            <select class="form-control" id="turno" name="turno" required>
                <option value="mañana" {{ $paralelo->turno == 'mañana' ? 'selected' : '' }}>Mañana</option>
                <option value="tarde" {{ $paralelo->turno == 'tarde' ? 'selected' : '' }}>Tarde</option>
                <option value="noche" {{ $paralelo->turno == 'noche' ? 'selected' : '' }}>Noche</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="cupo_maximo" class="font-weight-bold">Cupo Máximo <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" value="{{ $paralelo->cupo_maximo }}" min="1" required>
        </div>
        <div class="form-group col-md-4">
            <label for="aula" class="font-weight-bold">Aula</label>
            <input type="text" class="form-control" id="aula" name="aula" value="{{ $paralelo->aula }}" maxlength="40">
        </div>
    </div>
    
    <div class="form-group">
        <label for="asesor_id" class="font-weight-bold">Profesor Asesor (Opcional)</label>
        <select class="form-control select2" id="asesor_id" name="asesor_id">
            <option value="">-- Sin Asesor Asignado --</option>
            @foreach($profesores as $profesor)
                <option value="{{ $profesor->id }}" {{ $paralelo->asesor_id == $profesor->id ? 'selected' : '' }}>
                    {{ $profesor->nombres }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">El profesor seleccionado podrá ver estadísticas y controlar la disciplina de este curso.</small>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
    </div>
</form>

<script>
    $('#editParaleloForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let btn = $('#btnActualizar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');
        
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
                btn.prop('disabled', false).text('Actualizar');
                
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
