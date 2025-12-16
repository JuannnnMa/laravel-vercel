@extends('layouts.admin-layout')

@section('title', 'Estudiantes - ' . $paralelo->curso->nombre_curso . ' ' . $paralelo->nombre)
@section('page-title', 'Asesoría: ' . $paralelo->curso->nombre_curso . ' ' . $paralelo->nombre)

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Listado de Estudiantes: {{ $paralelo->curso->nombre_curso }} "{{ $paralelo->nombre }}"</h2>
        <a href="{{ route('docente.asesoria.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver a Mis Asesorías
        </a>
    </div>

    <div style="padding: 20px;">
        <table class="display responsive nowrap table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Apellidos y Nombres</th>
                    <th>Código</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estudiantes as $index => $matricula)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="font-weight-bold text-dark">{{ $matricula->estudiante->apellido_paterno }} {{ $matricula->estudiante->apellido_materno }}</span>
                                <div class="small text-gray-600">{{ $matricula->estudiante->nombres }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $matricula->estudiante->codigo_estudiante }}</td>
                    <td>
                        <span class="badge badge-success px-2 py-1">INSCRITO</span>
                    </td>
                    
                </tr>
                @empty
                
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
