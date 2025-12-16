@extends('layouts.admin-layout')

@section('title', 'Dashboard - Administración')
@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="page-header" style="margin-bottom: 30px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #1a1a1a;">Bienvenido al Sistema Administrativo</h1>
    <p style="color: #666;">Colegio San Simón de Ayacucho - Gestión Académica</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e3f2fd; color: #0d47a1;">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-info">
            <h3>Profesores</h3>
            <div class="number">{{ $totalProfesores }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f5e9; color: #1b5e20;">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3>Estudiantes</h3>
            <div class="number">{{ $totalEstudiantes }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fff3e0; color: #e65100;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>Tutores</h3>
            <div class="number">{{ $totalTutores }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f3e5f5; color: #4a148c;">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <h3>Usuarios</h3>
            <div class="number">{{ $totalUsuarios }}</div>
        </div>
    </div>
</div>

<div class="table-container" style="margin-top: 30px;">
    <div class="table-header" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="font-size: 18px;">Acciones Rápidas</h2>
    </div>
    <div style="padding: 10px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
            <a href="{{ route('admin.profesores') }}" class="quick-action-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Gestionar Profesores</span>
            </a>
            <a href="{{ route('admin.estudiantes') }}" class="quick-action-card">
                <i class="fas fa-user-graduate"></i>
                <span>Gestionar Estudiantes</span>
            </a>
            <a href="{{ route('admin.tutores') }}" class="quick-action-card">
                <i class="fas fa-users"></i>
                <span>Gestionar Tutores</span>
            </a>
            <a href="{{ route('admin.materias') }}" class="quick-action-card">
                <i class="fas fa-book"></i>
                <span>Gestionar Materias</span>
            </a>
            <a href="{{ route('admin.horarios') }}" class="quick-action-card">
                <i class="fas fa-clock"></i>
                <span>Ver Horarios</span>
            </a>
        </div>
    </div>
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
    
    .quick-action-card {
        background: #f8f9fa;
        border: 1px solid #eee;
        padding: 20px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s;
        text-align: center;
    }
    
    .quick-action-card i {
        font-size: 32px;
        color: #000;
    }
    
    .quick-action-card span {
        font-weight: 600;
        font-size: 14px;
    }
    
    .quick-action-card:hover {
        background: #fff;
        border-color: #000;
        color: #000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
</style>
@endsection
