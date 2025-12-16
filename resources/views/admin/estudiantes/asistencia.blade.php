<div style="background:#f9f9f9; padding:20px; border-radius:8px; margin-bottom:20px;">
    <h4>Estudiante: {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }}</h4>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Materia</th>
            <th>Estado</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @if(count($asistencias) > 0)
            @foreach($asistencias as $a)
                @php
                    $badge = $a->estado == 'presente' ? 'badge-success' : ($a->estado == 'ausente' ? 'badge-danger' : 'badge-warning');
                @endphp
                    <td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $a->asignacion?->materia?->nombre ?? 'N/A' }}</td>
                    <td><span class="badge {{ $badge }}">{{ ucfirst($a->estado) }}</span></td>
                    <td>{{ $a->observacion ?? '-' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align:center; padding:40px; color:#666;">No hay registros de asistencia</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="modal-footer" style="margin-top:20px; display: flex; justify-content: space-between;">
    <a href="{{ route('admin.estudiantes.reporte.asistencia-individual', $estudiante->id) }}" class="btn btn-danger" style="display: inline-flex; align-items: center; gap: 5px;">
        <i class="fas fa-file-pdf"></i> Descargar Asistencia
    </a>
    <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cerrar</button>
</div>
