<aside class="sidebar">
    <div class="sidebar-header" style="padding: 20px; border-bottom: 1px solid #333; text-align: center;">
        <div class="logo-container" style="width: 80px; height: 80px; margin: 0 auto 15px; border-radius: 50%; background: white; padding: 4px; overflow: hidden;">
            <img src="{{ asset('img/descarga.jpeg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: white; margin-bottom: 5px;">Colegio San Simón</h3>
        <p style="font-size: 12px; color: #999;">
            @if(auth()->user()->hasRole('profesor')) 
                Panel Docente 
            @else 
                Sistema Administración 
            @endif
        </p>
    </div>
    <ul class="sidebar-menu" style="list-style: none; padding: 20px 0;">
        @if(auth()->user()->hasRole('administrador'))
            <li>
                <a href="{{ route('admin.dashboard') }}" class="@if(Route::currentRouteName() == 'admin.dashboard') active @endif">
                    <i class="fas fa-home" style="width: 20px; margin-right: 12px;"></i>
                    Inicio
                </a>
            </li>
            <li>
                <a href="{{ route('admin.usuarios') }}" class="@if(Str::contains(Route::currentRouteName(), 'usuarios')) active @endif">
                    <i class="fas fa-users-cog" style="width: 20px; margin-right: 12px;"></i>
                    Usuarios y Roles
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profesores') }}" class="@if(Str::contains(Route::currentRouteName(), 'profesores')) active @endif">
                    <i class="fas fa-chalkboard-teacher" style="width: 20px; margin-right: 12px;"></i>
                    Profesores
                </a>
            </li>
            <li>
                <a href="{{ route('admin.estudiantes') }}" class="@if(Str::contains(Route::currentRouteName(), 'estudiantes')) active @endif">
                     <i class="fas fa-user-graduate" style="width: 20px; margin-right: 12px;"></i>
                    Estudiantes
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tutores') }}" class="@if(Str::contains(Route::currentRouteName(), 'tutores')) active @endif">
                    <i class="fas fa-users" style="width: 20px; margin-right: 12px;"></i>
                    Tutores
                </a>
            </li>
            <li>
                <a href="{{ route('admin.materias') }}" class="@if(Str::contains(Route::currentRouteName(), 'materias')) active @endif">
                    <i class="fas fa-book" style="width: 20px; margin-right: 12px;"></i>
                    Materias
                </a>
            </li>
            <li>
                <a href="{{ route('admin.cursos') }}" class="@if(Str::contains(Route::currentRouteName(), 'cursos')) active @endif">
                     <i class="fas fa-layer-group" style="width: 20px; margin-right: 12px;"></i>
                    Cursos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.paralelos') }}" class="@if(Str::contains(Route::currentRouteName(), 'paralelos')) active @endif">
                    <i class="fas fa-th-large" style="width: 20px; margin-right: 12px;"></i>
                    Paralelos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.horarios') }}" class="@if(Str::contains(Route::currentRouteName(), 'horarios')) active @endif">
                    <i class="fas fa-clock" style="width: 20px; margin-right: 12px;"></i>
                    Horarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.comunicados.index') }}" class="@if(Str::contains(Route::currentRouteName(), 'admin.comunicados')) active @endif">
                    <i class="fas fa-bullhorn" style="width: 20px; margin-right: 12px;"></i>
                    Comunicados
                </a>
            </li>

        @elseif(auth()->user()->hasRole('profesor'))
            <li>
                <a href="{{ route('docente.dashboard') }}" class="@if(Route::currentRouteName() == 'docente.dashboard') active @endif">
                    <i class="fas fa-home" style="width: 20px; margin-right: 12px;"></i>
                    Inicio
                </a>
            </li>
            <li>
                <a href="{{ route('docente.notas.index') }}" class="@if(Str::contains(Route::currentRouteName(), 'docente.notas')) active @endif">
                    <i class="fas fa-edit" style="width: 20px; margin-right: 12px;"></i>
                    Notas
                </a>
            </li>
            <li>
                <a href="{{ route('docente.asistencia.index') }}" class="@if(Str::contains(Route::currentRouteName(), 'docente.asistencia')) active @endif">
                    <i class="fas fa-clipboard-check" style="width: 20px; margin-right: 12px;"></i>
                    Asistencia
                </a>
            </li>
            <li>
                <a href="{{ route('docente.asesoria.index') }}" class="@if(Str::contains(Route::currentRouteName(), 'docente.asesoria')) active @endif">
                    <i class="fas fa-user-graduate" style="width: 20px; margin-right: 12px;"></i>
                    Asesoría
                </a>
            </li>
            <li>
                <a href="{{ route('docente.comunicados.index') }}" class="@if(Str::contains(Route::currentRouteName(), 'docente.comunicados')) active @endif">
                    <i class="fas fa-bullhorn" style="width: 20px; margin-right: 12px;"></i>
                    Comunicados y Avisos
                </a>
            </li>
        @endif
    </ul>
    
    <style>
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: #999;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }
        .sidebar-menu a:hover {
            background-color: #1a1a1a;
            color: #ffffff;
        }
        .sidebar-menu a.active {
            background-color: #ffffff;
            color: #000000;
            font-weight: 600;
        }
    </style>
</aside>
