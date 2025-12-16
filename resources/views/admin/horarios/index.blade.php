@extends('layouts.admin-layout')

@section('title', 'Horarios')
@section('page-title', 'Gestión de Horarios')

@section('content')
<!-- Filters -->
<!-- Filters -->
<div class="filters" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('admin.horarios') }}" id="filterForm" style="display: flex; gap: 20px; width: 100%; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label style="font-weight: 600; margin-bottom: 8px; display: block;">Curso</label>
            <select name="curso_id" id="cursoSelect" class="form-control" onchange="this.form.submit()" required>
                <option value="">Seleccionar curso</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        @if(request('curso_id'))
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label style="font-weight: 600; margin-bottom: 8px; display: block;">Paralelo</label>
            <select name="paralelo_id" id="paraleloSelect" class="form-control" onchange="this.form.submit()" required>
                <option value="">Seleccionar paralelo</option>
                @foreach($paralelos as $paralelo)
                    <option value="{{ $paralelo->id }}" {{ request('paralelo_id') == $paralelo->id ? 'selected' : '' }}>
                        {{ $paralelo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        
        @if(request('paralelo_id'))
        <div class="form-group" style="margin-bottom: 0;">
            <button type="button" class="btn btn-primary" onclick="openModal('{{ route('admin.horarios.create', ['paralelo_id' => request('paralelo_id')]) }}', 'Nuevo Horario')">
                <i class="fas fa-plus"></i> Nuevo Horario
            </button>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <button type="submit" formaction="{{ route('admin.horarios.descargar') }}" class="btn btn-secondary">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </button>
        </div>
        @endif
    </form>
</div>

@if(request('paralelo_id'))
<div class="table-container">
    <div class="table-header">
        <h2>Horario de Clases</h2>
    </div>
    
    @if($horarios->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 120px;">Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $horas = [
                    ['inicio' => '08:00', 'fin' => '09:00'],
                    ['inicio' => '09:00', 'fin' => '10:00'],
                    ['inicio' => '10:00', 'fin' => '11:00'],
                    ['inicio' => '11:00', 'fin' => '12:00'],
                    ['inicio' => '12:00', 'fin' => '13:00'],
                    ['inicio' => '14:00', 'fin' => '15:00'],
                    ['inicio' => '15:00', 'fin' => '16:00'],
                    ['inicio' => '16:00', 'fin' => '17:00'],
                ];
                $dias = [
                    1 => 'Lunes', 
                    2 => 'Martes', 
                    3 => 'Miércoles', 
                    4 => 'Jueves', 
                    5 => 'Viernes', 
                    6 => 'Sábado'
                ];
            @endphp
            
            @foreach($horas as $hora)
            <tr>
                <td><strong>{{ $hora['inicio'] }}-{{ $hora['fin'] }}</strong></td>
                @foreach($dias as $numDia => $nombreDia)
                    <td>
                        @php
                            $clase = $horarios->get($numDia)?->first(function($h) use ($hora) {
                                return \Carbon\Carbon::parse($h->hora_inicio)->format('H:i') == $hora['inicio'];
                            });
                        @endphp
                        
                        @if($clase)
                            <div style="padding: 8px 4px; background: #f8f9fa; border-radius: 4px; border-left: 3px solid #1a1a1a; position: relative; font-size: 12px;">
                                <div style="display: flex; align-items: start; gap: 4px;">
                                    <div style="flex: 1; min-width: 0;">
                                        <strong style="display: block; margin-bottom: 2px; line-height: 1.2;">{{ $clase->materia->nombre }}</strong>
                                        <small style="color: #666; display: block; font-size: 10px; line-height: 1.2;">{{ $clase->profesor->nombres }} {{ $clase->profesor->apellido_paterno }}</small>
                                    </div>
                                    <div style="display: flex; gap: 2px; flex-shrink: 0;">
                                        <button type="button" class="btn-icon" onclick="openModal('{{ route('admin.horarios.edit', $clase->id) }}', 'Editar Horario')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form id="delete-horario-{{ $clase->id }}" action="{{ route('admin.horarios.destroy', $clase->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-icon btn-icon-danger" onclick="confirmDelete('delete-horario-{{ $clase->id }}')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span style="color: #ccc; font-size: 12px;">-</span>
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 60px; color: #666;">
        <i class="fas fa-calendar-times" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px;"></i>
        <p>No hay horarios configurados para este paralelo</p>
        <p style="font-size: 14px; color: #999;">Haz clic en "Nuevo Horario" para comenzar</p>
    </div>
    @endif
</div>
@else
<div class="table-container">
    <div style="text-align: center; padding: 60px; color: #666;">
        <i class="fas fa-calendar-day" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px;"></i>
        <p>Seleccione un curso y paralelo para ver el horario</p>
    </div>
</div>
@endif

<style>
.btn-icon {
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #666;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: #e9ecef;
    color: #1a1a1a;
}

.btn-icon-danger:hover {
    background: #fee;
    color: #dc3545;
}
</style>
@endsection
