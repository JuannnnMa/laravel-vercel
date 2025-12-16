@extends('layouts.admin-layout')

@section('title', 'Dashboard - Panel Profesor')
@section('page-title', 'Panel del Profesor')

@section('content')
<div class="page-header" style="margin-bottom: 30px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #1a1a1a;">Bienvenido, {{ $profesor->nombres }}</h1>
    <p style="color: #666;">Gestión Académica - {{ $anioActual->nombre ?? 'Gestión Actual' }}</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e3f2fd; color: #0d47a1;">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3>Materias Asignadas</h3>
            <div class="number">{{ $asignaciones->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f5e9; color: #1b5e20;">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3>Total Estudiantes</h3>
            <div class="number">
                {{ $asignaciones->sum(function($asig) { return $asig->paralelo->inscripciones->where('estado', 1)->count(); }) }}
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fff3e0; color: #e65100;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3>Periodo Actual</h3>
            <div class="number">1º Bimestre</div>
        </div>
    </div>
</div>

<div class="table-container" style="margin-top: 30px;">
    <div class="table-header" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="font-size: 18px;">Mis Materias</h2>
    </div>
    
    <table id="materiasTable" class="table responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Curso y Paralelo</th>
                <th>Estudiantes</th>
                <th>Acciones Rápidas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asignaciones as $asignacion)
            <tr>
                <td style="font-weight: 500;">{{ $asignacion->materia->nombre }}</td>
                <td>
                    <span class="badge badge-light" style="font-size: 13px;">
                        {{ $asignacion->paralelo->curso->nombre }} "{{ $asignacion->paralelo->nombre }}"
                    </span>
                </td>
                <td>
                    <i class="fas fa-users" style="color: #666; margin-right: 5px;"></i>
                    {{ $asignacion->paralelo->inscripciones->where('estado', 1)->count() }} inscritos
                </td>
                <td>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('docente.notas.show', $asignacion->id) }}" class="btn btn-sm btn-primary" title="Registrar Notas">
                            <i class="fas fa-star"></i> Notas
                        </a>
                        <a href="{{ route('docente.asistencia.show', $asignacion->id) }}" class="btn btn-sm btn-secondary" title="Tomar Asistencia">
                            <i class="fas fa-calendar-check"></i> Asistencia
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }
    
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stat-info h3 {
        margin: 0;
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }
    
    .stat-info .number {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-top: 4px;
        line-height: 1;
    }
    
    /* Manual striping to avoid JS selector conflict */
    #materiasTable tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05);
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#materiasTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
