<form action="{{ route('admin.tutores.update', $tutor->id) }}" method="POST" id="editTutorForm">
    @csrf
    @method('PUT')
    <div class="form-row">
        <div class="form-group">
            <label for="ci">CI *</label>
            <input type="text" class="form-control" id="ci" name="ci" value="{{ $tutor->ci }}" required>
        </div>
        <div class="form-group">
            <label for="nombres">Nombres *</label>
            <input type="text" class="form-control" id="nombres" name="nombres" value="{{ $tutor->nombres }}" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="apellido_paterno">Apellido Paterno *</label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $tutor->apellido_paterno }}" required>
        </div>
        <div class="form-group">
            <label for="apellido_materno">Apellido Materno *</label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $tutor->apellido_materno }}" required>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $tutor->email }}">
        </div>
        <div class="form-group">
            <label for="celular">Celular</label>
            <input type="text" class="form-control" id="celular" name="celular" value="{{ $tutor->celular }}">
        </div>
    </div>

    <div class="form-group">
        <label for="ocupacion">Ocupación</label>
        <input type="text" class="form-control" id="ocupacion" name="ocupacion" value="{{ $tutor->ocupacion }}">
    </div>

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <textarea class="form-control" id="direccion" name="direccion" rows="2">{{ $tutor->direccion }}</textarea>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
    </div>
</form>


