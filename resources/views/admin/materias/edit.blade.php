<form action="{{ route('admin.materias.update', $materia->id) }}" method="POST" id="editMateriaForm">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="codigo">Código *</label>
        <input type="text" class="form-control" id="codigo" name="codigo" value="{{ $materia->codigo }}" required>
    </div>
    
    <div class="form-group">
        <label for="nombre">Nombre de la Materia *</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $materia->nombre }}" required>
    </div>
    
    <div class="form-group">
        <label for="area_id">Area *</label>
        <select class="form-control" id="area_id" name="area_id" required>
            <option value="">Seleccionar Area...</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ $materia->area_id == $area->id ? 'selected' : '' }}>{{ $area->nombre }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ $materia->descripcion }}</textarea>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
    </div>
</form>

<script>
    $('#editMateriaForm').on('submit', function(e) {
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
