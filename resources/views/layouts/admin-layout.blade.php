<!DOCTYPE html>
<html lang="es">
@include('layouts.componentes.head')
<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        @include('layouts.componentes.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar/Navbar -->
            @include('layouts.componentes.navbar')

            <!-- Content -->
            <div class="content" style="padding: 30px;">
                @if(session('success'))
                    <div class="alert alert-success" style="display:none;">{{ session('success') }}</div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger" style="display:none;">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts and Global Modals -->
    @include('layouts.componentes.footer')
</body>
</html>