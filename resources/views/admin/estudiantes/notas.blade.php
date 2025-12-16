<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4>Estudiante: {{ $estudiante->nombres }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</h4>
            <p><strong>Curso:</strong> {{ $inscripcion->paralelo->curso->nombre }} "{{ $inscripcion->paralelo->nombre }}"</p>
        </div>
        <div>
            <a href="{{ route('admin.estudiantes.boletin', $estudiante->id) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-file-pdf"></i> Descargar Boletín
            </a>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm text-center">
        <thead class="thead-dark">
            <tr>
                <th rowspan="2" class="align-middle">Área Curricular</th>
                <th colspan="3">Trimestres</th>
                <th rowspan="2" class="align-middle">Promedio Anual</th>
            </tr>
            <tr>
                <th>1° Trim</th>
                <th>2° Trim</th>
                <th>3° Trim</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boletin as $materiaId => $datos)
                <tr>
                    <td class="text-left font-weight-bold">
                        {{ $datos['materia']->nombre }}
                        @if($datos['materia']->area && $datos['materia']->area->nombre != $datos['materia']->nombre)
                            <small class="d-block text-muted">{{ $datos['materia']->area->nombre }}</small>
                        @endif
                    </td>
                    
                    <!-- 1er Trimestre -->
                    <td>
                        @if(isset($datos[1]))
                            <span class="badge badge-primary">{{ $datos[1]->nota_final }}</span>
                        @else
                            -
                        @endif
                    </td>

                    <!-- 2do Trimestre -->
                    <td>
                        @if(isset($datos[2]))
                            <span class="badge badge-primary">{{ $datos[2]->nota_final }}</span>
                        @else
                            -
                        @endif
                    </td>

                    <!-- 3er Trimestre -->
                    <td>
                        @if(isset($datos[3]))
                            <span class="badge badge-primary">{{ $datos[3]->nota_final }}</span>
                        @else
                            -
                        @endif
                    </td>

                    <!-- Promedio -->
                    <td class="font-weight-bold" style="background-color: #f0f0f0;">
                       {{ $datos['promedio_anual'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer" style="margin-top:20px;">
    <button type="button" class="btn btn-secondary" onclick="closeGlobalModal()">Cerrar</button>
</div>
