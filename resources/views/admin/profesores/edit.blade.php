<form action="{{ route('admin.profesores.update', $profesor->id) }}" method="POST" id="editProfesorForm">
    @csrf
    @method('PUT')
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="ci" class="font-weight-bold">CI <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ci" name="ci" value="{{ $profesor->ci }}" required maxlength="15">
        </div>
        <div class="form-group col-md-3">
            <label for="nombres" class="font-weight-bold">Nombres <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombres" name="nombres" value="{{ $profesor->nombres }}" required maxlength="50">
        </div>
        <div class="form-group col-md-3">
            <label for="apellido_paterno" class="font-weight-bold">Apellido Paterno <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $profesor->apellido_paterno }}" required maxlength="50">
        </div>
        <div class="form-group col-md-3">
            <label for="apellido_materno" class="font-weight-bold">Apellido Materno <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $profesor->apellido_materno }}" required maxlength="50">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $profesor->email }}" required maxlength="100">
        </div>
        <div class="form-group col-md-6">
            <label for="celular" class="font-weight-bold">Celular</label>
            <input type="text" class="form-control" id="celular" name="celular" value="{{ $profesor->celular }}" maxlength="15">
        </div>
    </div>

    <div class="form-group">
        <label for="direccion" class="font-weight-bold">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $profesor->direccion }}" maxlength="255">
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="especialidad" class="font-weight-bold">Especialidad</label>
            <input type="text" class="form-control" id="especialidad" name="especialidad" value="{{ $profesor->especialidad }}" maxlength="100">
        </div>
        <div class="form-group col-md-6">
            <label for="fecha_ingreso" class="font-weight-bold">Fecha de Ingreso <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{ $profesor->fecha_ingreso->format('Y-m-d') }}" required>
        </div>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
    </div>
</form>


