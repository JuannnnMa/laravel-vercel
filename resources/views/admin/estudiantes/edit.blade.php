<form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST" id="editEstudianteForm">
    @csrf
    @method('PUT')
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="font-weight-bold">CI <span class="text-danger">*</span></label>
            <input type="text" name="ci" value="{{ $estudiante->ci }}" required maxlength="15" class="form-control">
        </div>
        <div class="form-group col-md-8">
            <label class="font-weight-bold">Nombres <span class="text-danger">*</span></label>
            <input type="text" name="nombres" value="{{ $estudiante->nombres }}" required maxlength="50" class="form-control">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Apellido Paterno <span class="text-danger">*</span></label>
            <input type="text" name="apellido_paterno" value="{{ $estudiante->apellido_paterno }}" required maxlength="50" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Apellido Materno <span class="text-danger">*</span></label>
            <input type="text" name="apellido_materno" value="{{ $estudiante->apellido_materno }}" required maxlength="50" class="form-control">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
            <input type="date" name="fecha_nacimiento" value="{{ $estudiante->fecha_nacimiento }}" required class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Género <span class="text-danger">*</span></label>
            <select name="genero" required class="form-control">
                <option value="M" {{ $estudiante->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ $estudiante->genero == 'F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Teléfono</label>
            <input type="text" name="telefono" value="{{ $estudiante->telefono }}" maxlength="15" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Email</label>
            <input type="email" name="email" value="{{ $estudiante->email }}" maxlength="100" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="font-weight-bold">Dirección</label>
        <textarea name="direccion" rows="2" maxlength="255" class="form-control">{{ $estudiante->direccion }}</textarea>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
    </div>
</form>

<script>


</script>
