@extends('layouts.admin-layout')

@section('title', 'Registrar Notas')
@section('page-title', 'Registrar Calificaciones')

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>{{ $inscripcion->estudiante->apellido_paterno }} {{ $inscripcion->estudiante->apellido_materno }} {{ $inscripcion->estudiante->nombres }}</h1>
            <p>{{ $asignacion->materia->nombre }} - {{ $asignacion->paralelo->curso->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
        </div>
        <a href="{{ route('docente.notas.show', $asignacion->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="table-container" style="padding: 30px;">
    <!-- Period Selector (PHP Navigation) -->
    <div class="form-group" style="max-width: 300px; margin-bottom: 30px;">
        <label>Trimestre Académico</label>
        <select id="periodoSelect" class="form-control" onchange="window.location.href = this.value">
            @foreach($periodos as $periodo)
                <option value="{{ route('docente.notas.registrar', ['asignacion' => $asignacion->id, 'estudiante' => $inscripcion->id, 'trimestre' => $periodo->id_periodo]) }}" 
                    {{ $trimestreActual == $periodo->id_periodo ? 'selected' : '' }}>
                    {{ $periodo->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <form action="{{ route('docente.notas.store', [$asignacion->id, $inscripcion->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="trimestre" value="{{ $trimestreActual }}">

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0">Registro de Calificaciones</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Dimensión SER (10%)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" class="form-control" name="nota_ser" 
                                    value="{{ $nota->nota_ser }}" min="0" max="100" required placeholder="0 - 100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Dimensión SABER (35%)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" class="form-control" name="nota_saber" 
                                    value="{{ $nota->nota_saber }}" min="0" max="100" required placeholder="0 - 100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Dimensión HACER (35%)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" class="form-control" name="nota_hacer" 
                                    value="{{ $nota->nota_hacer }}" min="0" max="100" required placeholder="0 - 100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Dimensión DECIDIR (10%)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" class="form-control" name="nota_decidir" 
                                    value="{{ $nota->nota_decidir }}" min="0" max="100" required placeholder="0 - 100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Autoevaluación (10%)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" class="form-control" name="autoevaluacion" 
                                    value="{{ $nota->autoevaluacion }}" min="0" max="100" required placeholder="0 - 100">
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-info text-center">
                            Nota Final Calculada: 
                            <strong>
                                {{ $nota->nota_final }} 
                                @if($nota->literal)
                                    ({{ $nota->literal }})
                                @endif
                            </strong>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Guardar Nota
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
