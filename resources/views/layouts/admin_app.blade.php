<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('template/sweetalert2/css/sweetalert2.min.css') }}"/>
  @yield('style')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
    <aside class="main-sidebar sidebar-light-primary elevation-4">
                <a href="" class="brand-link" style="text-align: center;">
                    <span class="brand-text font-weight-light">{{ config('app.name', 'Taxless.in') }}</span>
                </a>
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                      <a href="#" class="d-block">Welcome {{ Auth::user()?Auth::user()->name:'Welcome' }}</a>
                      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <span class="badge badge-danger">{{ __('Logout') }}</span>
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                      </form>
                    </div>
                  </div>
                <div class="sidebar">
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link @if(Request::segment(1) == 'home' || Request::segment(1) == '') active-now @endif">
                                    <i class="nav-icon fas fa-address-card"></i>
                                    <p>
                                        All Leads
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('manage-employee') }}" class="nav-link @if(Request::segment(1) == 'manage-employee') active-now @endif">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Manage Employees
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
    @yield('content')
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>
<!-- jQuery -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/sweetalert2/js/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/sweetalert.js') }}"></script>
@yield('script')
<style>
  .active-now {
    background-color: #dddddd;
  }
</style>
</body>
</html>
