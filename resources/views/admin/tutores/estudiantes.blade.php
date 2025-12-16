<div style="margin-bottom: 20px;">
    <strong>Tutor:</strong> {{ $tutor->nombres }} {{ $tutor->apellido_paterno }} {{ $tutor->apellido_materno }}
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped" style="width: 100%; font-size: 14px;">
        <thead style="background: #e9ecef;">
            <tr>
                <th style="padding: 8px;">Estudiante</th>
                <th style="padding: 8px;">Curso Actual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tutor->estudiantes as $estudiante)
                @php
                    $inscripcion = $estudiante->inscripciones->first();
                @endphp
            <tr>
                <td style="padding: 8px;">
                    {{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}
                    <small style="color: #666; display: block;">CI: {{ $estudiante->ci }}</small>
                </td>
                <td style="padding: 8px;">
                    @if($inscripcion && $inscripcion->paralelo)
                        {{ $inscripcion->paralelo->curso->nombre }} - {{ $inscripcion->paralelo->nombre }}
                    @else
                        <span style="color: #999;">No inscrito</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="text-align: center; padding: 20px; color: #666;">
                    No tiene estudiantes asociados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-footer" style="padding-top: 20px; border-top: 1px solid #eee;">
    <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cerrar</button>
</div>
