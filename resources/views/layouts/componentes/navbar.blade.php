<div class="topbar">
    <div class="topbar-title" style="font-size: 1.25rem; font-weight: 600; color: #111827;">
        @yield('page-title', 'Panel de Administración')
    </div>
    @if(Auth::user()->hasRole('administrador'))
    <div style="flex: 1; display: flex; justify-content: center;">
        <form action="{{ route('admin.cambiar-gestion') }}" method="POST" style="display: flex; align-items: center; gap: 10px;">
            @csrf
            <span style="font-size: 14px; font-weight: 500; color: #374151;">Gestión:</span>
            <select name="anio_id" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 14px; color: #111827; cursor: pointer;">
                @foreach($globalAnios as $anio)
                    <option value="{{ $anio->id }}" {{ $globalAnioActual && $globalAnioActual->id == $anio->id ? 'selected' : '' }}>
                        {{ $anio->nombre }} {{ $anio->estaus ? '(Activa)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @else
    <div style="flex: 1;"></div>
    @endif
    <div class="topbar-user" style="display: flex; align-items: center; gap: 15px;">
        <div class="user-info" style="text-align: right;">
            <p style="margin: 0; font-size: 12px; color: #6b7280;">Bienvenido</p>
            <strong style="display: block; font-size: 14px; color: #111827;">{{ Auth::user()->nombres ?? 'Usuario' }}</strong>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn" style="background: white; border: 1px solid #e5e7eb; color: #ef4444; padding: 8px 16px; border-radius: 8px; font-weight: 500; cursor: pointer; transition: 0.2s;">
                <i class="fas fa-sign-out-alt"></i> Salir
            </button>
        </form>
    </div>
</div>
