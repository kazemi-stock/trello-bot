<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/flat/green.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/morris/morris.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/bootstrap-rtl.min.css') }}">
    <!-- template rtl version -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/custom-style.css') }}">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @auth
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <ul class="navbar-nav mr-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-dark dropdown-toggle" data-toggle="dropdown" href="#">
                        {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                        <a href="#" class="dropdown-item text-dark">
                            پروفایل
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-dark"
                           onclick="event.preventDefault(); document.getElementById('logout').submit()">
                            خروج
                        </a>
                        <form id="logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <a href="{{ route('home') }}" class="brand-link">
                <img src="{{ asset('assets/img/trello_logo.png') }}" alt="Trello Logo" class="brand-image">
                <span class="brand-text font-weight-light">پنل مدیریت</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar" style="direction: ltr">
                <div style="direction: rtl">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link text-white active" id="dashboard">
                                    <i class="nav-icon fa fa-dashboard"></i>
                                    <p>
                                        داشبورد
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('contacts.index') }}" class="nav-link text-black-50" id="contact">
                                    <i class="nav-icon fa fa-address-book"></i>
                                    <p>
                                        مخاطبین
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('messages.index') }}" class="nav-link text-black-50" id="inbox">
                                    <i class="nav-icon fa fa-inbox"></i>
                                    <p>
                                        پیام های دریافتی
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('comments.index') }}" class="nav-link text-black-50" id="send">
                                    <i class="nav-icon fa fa-send"></i>
                                    <p>
                                        پیام های ارسالی
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cards.index') }}" class="nav-link text-black-50" id="card">
                                    <i class="nav-icon fa fa-id-card"></i>
                                    <p>
                                        کارت ها
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('actions.index') }}" class="nav-link text-black-50" id="event">
                                    <i class="nav-icon fa fa-tasks"></i>
                                    <p>
                                        رویدادها
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link text-black-50" id="users">
                                    <i class="nav-icon fa fa-users"></i>
                                    <p>
                                        کاربران
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('setting.index') }}" class="nav-link text-black-50" id="setting">
                                    <i class="nav-icon fa fa-cogs"></i>
                                    <p>
                                        تنظیمات
                                    </p>
                                </a>
                            </li>
                            <li class="fixed-bottom text-right mr-5 text-black-50">
                                Version 1.0.0
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
            </div>
            <!-- /sidebar -->
        </aside>
    @endauth
<!-- Content -->
    @yield('content')
<!-- /content -->
    @auth
        <footer class="main-footer text-left">
            <strong>CopyRight &copy; 2023 <a href="http://github.com/kazemi-stock/"
                                             target="_blank">M.Kazemi</a></strong>
        </footer>
    @endauth
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('assets/plugins/knob/jquery.knob.js') }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets/dist/js/dashboard.js') }}"></script>
@stack('scripts')
</body>
</html>
