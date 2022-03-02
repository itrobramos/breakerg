<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Breaker G </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ env('DEPLOY_URL') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/dist/js/jquery.toast.min.css">

    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="{{ env('DEPLOY_URL') }}/plugins/select2/css/select2.min.css">

    <!-- jQuery -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ env('DEPLOY_URL') }}/dist/js/jquery.toast.min.js"></script>

    <!-- Select2 -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/select2/js/select2.full.min.js"></script>

    

    @include('partials.alerts')
    @include('partials.scripts')


</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('home') }}" class="brand-link">
                <span class="brand-text font-weight-light">Breaker G</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-2 mb-3 d-flex">
                    <div class="info">
                        <a href="{{ url('users/changePassword') }}" class="d-block">
                            {{ Auth::user()->name }}
                        </a>
                    </div>
                </div>

                

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="true">

                        <li class="nav-item">
                            <a href="/home" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Inicio
                                    <i class="right"></i>
                                </p>
                            </a>
                        </li>
                        
                        <li class="nav-header">Catálogos</li>

                        <li class="nav-item">
                            <a href="{{ url('clients') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Clientes
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('product_types') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Tipos de productos
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('products') }}" class="nav-link">
                                <i class="fas fa-solid fa-box"></i>
                                <p>
                                    Productos
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('suppliers') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Proveedores
                                </p>
                            </a>
                        </li>

                        @if(Auth::user()->admin == true)
                        <li class="nav-item">
                            <a href="{{ url('users') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Usuarios
                                </p>
                            </a>
                        </li>
                        @endif

                        <li class="nav-header">Operación</li>

                        <li class="nav-item">
                            <a href="{{ url('entries/add') }}" class="nav-link">
                                <i class="fas fa-solid fa-box"></i>
                                <p class="text">Entrada Mercancía</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('sales/add') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i>
                                <p class="text">Vender</p>
                            </a>
                        </li>

                        @if(Auth::user()->admin == true)
                        <li class="nav-item">
                            <a href="{{ url('reports') }}" class="nav-link">
                                <i class="fas fa-chart-bar"></i>
                                <p>Reportes</p>
                            </a>
                        </li>
                        @endif
                    
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                <i class="nav-icon far fa-circle text-danger"></i>
                                <p class="text">Cerrar sesión</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="{{ env('DEPLOY_URL') }}/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ env('DEPLOY_URL') }}/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="{{ env('DEPLOY_URL') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ env('DEPLOY_URL') }}/dist/js/adminlte.js"></script>
    <script src="{{ env('DEPLOY_URL') }}/dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ env('DEPLOY_URL') }}/dist/js/demo.js"></script>
    <script src="{{ env('DEPLOY_URL') }}/plugins/datatables/jquery.dataTables.min.js"></script>



</body>

</html>
