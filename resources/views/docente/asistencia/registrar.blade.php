@extends('layouts.admin-layout')

@section('title', 'Registrar Asistencia')
@section('page-title', 'Registro de Asistencia')

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>{{ $asignacion->materia->nombre }}</h1>
            <p>{{ $asignacion->paralelo->curso->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
        </div>
        <a href="{{ route('docente.asistencia.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Lista
        </a>
    </div>
</div>

<div class="table-container" style="padding: 30px;">
    <form action="{{ route('docente.asistencia.store', $asignacion->id) }}" method="POST">
        @csrf
        
        <div class="form-group" style="max-width: 300px; margin-bottom: 30px;">
            <label for="fecha" style="font-weight: 600; color: #374151; margin-bottom: 8px; display: block;">Fecha de Asistencia</label>
            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $fecha }}" required 
                   style="padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; width: 100%;"
                   onchange="window.location.href = '{{ route('docente.asistencia.show', $asignacion->id) }}?fecha=' + this.value">
        </div>

        <table class="table table-striped display" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 30%;">Estudiante</th>
                    <th style="width: 40%;">Estado Asistencia</th>
                    <th style="width: 30%;">Observación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiantes as $matricula)
                @php
                    $asistencia = $asistencias[$matricula->id] ?? null;
                    $estado = $asistencia ? $asistencia->estado : 'presente'; // Default to presente for NEW records
                    $observacion = $asistencia ? $asistencia->observacion : '';
                @endphp
                <tr>
                    <td style="vertical-align: middle;">
                        <div style="font-weight: 500; color: #111827;">
                            {{ $matricula->estudiante->apellido_paterno }} {{ $matricula->estudiante->apellido_materno }}
                        </div>
                        <div style="font-size: 13px; color: #6b7280;">
                            {{ $matricula->estudiante->nombres }}
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <div class="btn-group" role="group" style="width: 100%; display: flex; gap: 5px;">
                            <input type="radio" class="btn-check" name="asistencia[{{ $matricula->id }}]" id="presente_{{ $matricula->id }}" value="presente" {{ $estado == 'presente' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success btn-sm {{ $estado == 'presente' ? 'active' : '' }}" for="presente_{{ $matricula->id }}" style="flex: 1;">Presente</label>

                            <input type="radio" class="btn-check" name="asistencia[{{ $matricula->id }}]" id="ausente_{{ $matricula->id }}" value="ausente" {{ $estado == 'ausente' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger btn-sm {{ $estado == 'ausente' ? 'active' : '' }}" for="ausente_{{ $matricula->id }}" style="flex: 1;">Ausente</label>

                            <input type="radio" class="btn-check" name="asistencia[{{ $matricula->id }}]" id="tardanza_{{ $matricula->id }}" value="tardanza" {{ $estado == 'tardanza' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning btn-sm {{ $estado == 'tardanza' ? 'active' : '' }}" for="tardanza_{{ $matricula->id }}" style="flex: 1;">Tardanza</label>
                            
                            <input type="radio" class="btn-check" name="asistencia[{{ $matricula->id }}]" id="licencia_{{ $matricula->id }}" value="licencia" {{ $estado == 'licencia' ? 'checked' : '' }}>
                            <label class="btn btn-outline-info btn-sm {{ $estado == 'licencia' ? 'active' : '' }}" for="licencia_{{ $matricula->id }}" style="flex: 1;">Licencia</label>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <input type="text" class="form-control form-control-sm" name="observacion[{{ $matricula->id }}]" value="{{ $observacion }}" placeholder="Opcional..."
                               style="border: 1px solid #e5e7eb; border-radius: 6px;">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 30px; text-align: right; padding-top: 20px; border-top: 1px solid #eee;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight: 600;">
                <i class="fas fa-save"></i> Guardar Asistencia
            </button>
        </div>
    </form>
</div>
@endsection
