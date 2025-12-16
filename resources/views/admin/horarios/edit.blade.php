<form action="{{ route('admin.horarios.update', $horario->id) }}" method="POST" id="editHorarioForm">
    @csrf
    @method('PUT')
    
    <div style="background: #eef; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <strong>Materia:</strong> {{ $horario->asignacion->materia->nombre }} <br>
        <strong>Docente:</strong> {{ $horario->asignacion->profesor->nombres }} {{ $horario->asignacion->profesor->apellido_paterno }}
    </div>

    <div class="form-group">
        <label for="dia_semana">Día de la Semana *</label>
        <select name="dia_semana" id="dia_semana" class="form-control" required>
            <option value="1" {{ $horario->dia_semana == '1' ? 'selected' : '' }}>Lunes</option>
            <option value="2" {{ $horario->dia_semana == '2' ? 'selected' : '' }}>Martes</option>
            <option value="3" {{ $horario->dia_semana == '3' ? 'selected' : '' }}>Miércoles</option>
            <option value="4" {{ $horario->dia_semana == '4' ? 'selected' : '' }}>Jueves</option>
            <option value="5" {{ $horario->dia_semana == '5' ? 'selected' : '' }}>Viernes</option>
            <option value="6" {{ $horario->dia_semana == '6' ? 'selected' : '' }}>Sábado</option>
        </select>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div class="form-group">
            <label for="hora_inicio">Hora Inicio *</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}" required>
        </div>
        
        <div class="form-group">
            <label for="hora_fin">Hora Fin *</label>
            <input type="time" name="hora_fin" id="hora_fin" class="form-control" value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}" required>
        </div>
    </div>

    <div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
        <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar Horario</button>
    </div>
</form>


