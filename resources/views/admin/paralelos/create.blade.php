<form action="{{ route('admin.paralelos.store') }}" method="POST" id="createParaleloForm">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="curso_id" class="font-weight-bold">Curso <span class="text-danger">*</span></label>
            <select class="form-control" id="curso_id" name="curso_id" required>
                <option value="">Seleccionar Curso...</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="nombre" class="font-weight-bold">Nombre del Paralelo <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" placeholder="Ej: A, B, C" required maxlength="50" value="{{ old('nombre') }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="turno" class="font-weight-bold">Turno <span class="text-danger">*</span></label>
            <select class="form-control" id="turno" name="turno" required>
                <option value="mañana">Mañana</option>
                <option value="tarde">Tarde</option>
                <option value="noche">Noche</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="cupo_maximo" class="font-weight-bold">Cupo Máximo <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" min="1" required>
        </div>
        <div class="form-group col-md-4">
            <label for="aula" class="font-weight-bold">Aula</label>
            <input type="text" class="form-control" id="aula" name="aula" maxlength="40">
        </div>
    </div>

    <div class="form-group">
        <label for="asesor_id" class="font-weight-bold">Profesor Asesor (Opcional)</label>
        <select class="form-control select2" id="asesor_id" name="asesor_id">
            <option value="">-- Sin Asesor Asignado --</option>
            @foreach($profesores as $profesor)
                <option value="{{ $profesor->id }}">
                    {{ $profesor->nombres }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">El profesor seleccionado podrá ver estadísticas y controlar la disciplina de este curso.</small>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
    </div>
</form>

<script>
    $('#createParaleloForm').on('submit', function(e) {
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
                // Success: Reload page, session flash will trigger footer alert
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
