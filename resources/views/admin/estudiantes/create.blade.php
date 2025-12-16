<form action="{{ route('admin.estudiantes.store') }}" method="POST" id="createEstudianteForm">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="font-weight-bold">CI <span class="text-danger">*</span></label>
            <input type="text" name="ci" required maxlength="15" class="form-control">
        </div>
        <div class="form-group col-md-8">
            <label class="font-weight-bold">Nombres <span class="text-danger">*</span></label>
            <input type="text" name="nombres" required maxlength="50" class="form-control">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Apellido Paterno <span class="text-danger">*</span></label>
            <input type="text" name="apellido_paterno" required maxlength="50" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Apellido Materno <span class="text-danger">*</span></label>
            <input type="text" name="apellido_materno" required maxlength="50" class="form-control">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
            <input type="date" name="fecha_nacimiento" required class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Género <span class="text-danger">*</span></label>
            <select name="genero" required class="form-control">
                <option value="">Seleccionar</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Teléfono</label>
            <input type="text" name="telefono" maxlength="15" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Email</label>
            <input type="email" name="email" maxlength="100" class="form-control">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-12">
            <label class="font-weight-bold">Contraseña </label>
            <div class="input-group">
                <input type="password" name="password" minlength="6" class="form-control" placeholder="Mínimo 6 caracteres">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-eye" onclick="togglePassword(this)"></i></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="font-weight-bold">Dirección</label>
        <textarea name="direccion" rows="2" maxlength="255" class="form-control"></textarea>
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


</script>
