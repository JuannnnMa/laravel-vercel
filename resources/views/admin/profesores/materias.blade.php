<div style="margin-bottom: 20px;">
    <strong>Docente:</strong> {{ $profesor->nombres }} {{ $profesor->apellido_paterno }} {{ $profesor->apellido_materno }}
</div>

<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef;">
    <h5 style="margin-top: 0; margin-bottom: 15px; font-size: 14px; text-transform: uppercase; color: #666;">Asignar Nueva Materia</h5>
    <form action="{{ route('admin.profesores.materias.add') }}" method="POST" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
        @csrf
        <input type="hidden" name="profesor_id" value="{{ $profesor->id }}">
        
        <div class="form-group" style="flex: 1; margin: 0; min-width: 200px;">
            <label style="font-size: 12px; margin-bottom: 4px; display: block;">Materia</label>
            <select name="materia_id" class="form-control" required style="width: 100%;">
                <option value="">Seleccionar Materia...</option>
                @foreach($todasMaterias as $materia)
                    <option value="{{ $materia->id }}">{{ $materia->nombre }} ({{ $materia->codigo }})</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" style="flex: 1; margin: 0; min-width: 200px;">
            <label style="font-size: 12px; margin-bottom: 4px; display: block;">Paralelo</label>
            <select name="paralelo_id" class="form-control" required style="width: 100%;">
                <option value="">Seleccionar Paralelo...</option>
                @foreach($paralelos as $paralelo)
                    <option value="{{ $paralelo->id }}">
                        {{ $paralelo->curso->nombre }} - {{ $paralelo->nombre }} ({{ ucfirst($paralelo->turno) }})
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary" style="height: 38px;">
            <i class="fas fa-plus"></i> Asignar
        </button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped" style="width: 100%; font-size: 14px;">
        <thead style="background: #e9ecef;">
            <tr>
                <th style="padding: 8px;">Materia</th>
                <th style="padding: 8px;">Curso/Paralelo</th>
                <th style="padding: 8px; width: 50px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asignaciones as $asignacion)
            <tr>
                <td style="padding: 8px;">{{ $asignacion->materia->nombre }}</td>
                <td style="padding: 8px;">
                    {{ $asignacion->paralelo->curso->nombre }} - {{ $asignacion->paralelo->nombre }}
                    <small style="color: #666; display: block;">{{ ucfirst($asignacion->paralelo->turno) }}</small>
                </td>
                <td style="padding: 8px; text-align: center;">
                    <form action="{{ route('admin.profesores.materias.remove') }}" method="POST" onsubmit="return confirm('¿Quitar esta materia del docente?');">
                        @csrf
                        <input type="hidden" name="id_asignacion" value="{{ $asignacion->id }}">
                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; padding: 20px; color: #666;">
                    No hay materias asignadas a este docente.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    // AJAX removed. Standard submission used.
</script>

<div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
    <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cerrar</button>
</div>
