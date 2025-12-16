<form action="{{ route('admin.horarios.store') }}" method="POST" id="createHorarioForm">
    @csrf
    
    <div class="form-group">
        <label for="asignacion_id">Materia y Docente *</label>
        <select name="asignacion_id" id="asignacion_id" class="form-control" required>
            <option value="">Seleccionar...</option>
            @foreach($asignaciones as $asignacion)
                <option value="{{ $asignacion->id }}">
                    {{ $asignacion->materia->nombre }} - {{ $asignacion->profesor->nombres }} {{ $asignacion->profesor->apellido_paterno }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="dia_semana">Día de la Semana *</label>
        <select name="dia_semana" id="dia_semana" class="form-control" required>
            <option value="">Seleccionar día...</option>
            <option value="1">Lunes</option>
            <option value="2">Martes</option>
            <option value="3">Miércoles</option>
            <option value="4">Jueves</option>
            <option value="5">Viernes</option>
            <option value="6">Sábado</option>
        </select>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div class="form-group">
            <label for="hora_inicio">Hora Inicio *</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="hora_fin">Hora Fin *</label>
            <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
        </div>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Horario</button>
    </div>
</form>


