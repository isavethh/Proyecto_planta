<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Panel')</title>

    {{-- Importar CSS y JS directamente --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    
    <style>
        .main-sidebar {
            min-height: 100vh;
        }
        .content-wrapper {
            margin-left: 250px;
        }
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0;
            }
        }
        .sidebar-dark-primary {
            background-color: #343a40 !important;
        }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link {
            color: #c2c7d0;
        }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            color: #fff;
            background-color: #007bff;
        }
        .nav-header {
            color: #6c757d;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
        }
    </style>
    @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            {{-- Navbar: enlaces izquierda --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/dashboard') }}" class="nav-link">Inicio</a>
                </li>
            </ul>

            {{-- Navbar: acciones derecha --}}
            <ul class="navbar-nav ml-auto">
                @if(session('user_id'))
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i> Cerrar sesión
                        </button>
                    </form>
                </li>
                @endif
            </ul>
        </nav>

        {{-- Sidebar --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            {{-- Brand Logo --}}
            <a href="{{ url('/dashboard') }}" class="brand-link">
                <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>

            {{-- Sidebar --}}
            <div class="sidebar">
                {{-- Sidebar Menu --}}
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        @if(session('user_role') === 'admin')
                            <li class="nav-header">GESTIÓN DEL SISTEMA</li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/envios') }}" class="nav-link">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Envíos (Admin)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/envios') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/envios') }}" class="nav-link">
                                    <i class="nav-icon fas fa-car"></i>
                                    <p>Vehículos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/envios') }}" class="nav-link">
                                    <i class="nav-icon fas fa-box"></i>
                                    <p>Productos</p>
                                </a>
                            </li>
                        @else
                            <li class="nav-header">MI CUENTA</li>
                            <li class="nav-item">
                                <a href="{{ url('/envios/create') }}" class="nav-link">
                                    <i class="nav-icon fas fa-plus-circle"></i>
                                    <p>Crear Envío</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/mis-envios') }}" class="nav-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>Mis Envíos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/reglamento') }}" class="nav-link">
                                    <i class="nav-icon fas fa-file-contract"></i>
                                    <p>Reglamento</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/documento-pedido') }}" class="nav-link">
                                    <i class="nav-icon fas fa-file-pdf"></i>
                                    <p>Documento de Pedido</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        {{-- Contenido --}}
        <div class="content-wrapper p-3">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="main-footer text-sm text-center">
            <strong>&copy; {{ date('Y') }}</strong> — Laravel + AdminLTE
        </footer>
    </div>

    @yield('js')
</body>
</html>
