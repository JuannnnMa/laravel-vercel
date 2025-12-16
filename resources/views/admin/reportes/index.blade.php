@extends('layouts.admin-layout')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="card-title mb-0">Generador de Reportes Grupales</h4>
        <small class="text-muted">Seleccione un paralelo para generar sus reportes</small>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($paralelos as $paralelo)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-left-primary hover-card">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-3">
                            <span class="badge badge-primary p-2 badge-pill">{{ $paralelo->curso->nombre }}</span>
                        </div>
                        <h3 class="font-weight-bold">Paralelo "{{ $paralelo->nombre }}"</h3>
                        <div class="mt-3 d-flex flex-column gap-2">
                            <a href="{{ route('admin.reportes.centralizador', ['paralelo_id' => $paralelo->id]) }}" class="btn btn-outline-primary btn-sm mb-2">
                                <i class="fas fa-table mr-1"></i> Centralizador
                            </a>
                            <a href="{{ route('admin.reportes.asistencia', ['paralelo_id' => $paralelo->id]) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-check-square mr-1"></i> Asistencia
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No hay paralelos activos.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .hover-card { transition: transform 0.2s; border-left: 4px solid #4e73df !important; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endsection

