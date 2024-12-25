<!DOCTYPE html>
<head>
<title>Patient Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Hospital Management System" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="{{ asset('public/BackEnd/css/bootstrap.min.css') }}" >
<!-- Custom CSS -->
<link href="{{ asset('public/BackEnd/css/style.css') }}" rel='stylesheet' type='text/css' />
<link href="{{ asset('public/BackEnd/css/style-responsive.css') }}" rel="stylesheet"/>
<!-- Font Awesome -->
<link href="{{ asset('public/BackEnd/css/font-awesome.css') }}" rel="stylesheet">
<!-- Calendar -->
<link rel="stylesheet" href="{{ asset('public/BackEnd/css/monthly.css') }}">
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .sidebar-menu li.active a {
        background-color: #007bff !important;
        color: #fff !important;
        font-weight: bold;
        border-left: 4px solid #ffffff;
    }

    .sidebar-menu li a {
        padding: 15px 20px;
        display: block;
        transition: all 0.3s ease;
    }

    .sidebar-menu li a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .header.fixed-top {
        background: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        padding: 3px 6px;
        border-radius: 50%;
        font-size: 0.7rem;
        background: #dc3545;
        color: white;
    }

    .content-wrapper {
        padding: 20px;
        background: #f4f6f9;
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            z-index: 1000;
            width: 250px;
            transform: translateX(-100%);
            transition: transform 0.3s;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
    }
</style>

@stack('styles')
</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
    <!--logo start-->
    <div class="brand">
        <a href="{{ route('patient.dashboard') }}" class="logo">
            <img src="{{ asset('public/BackEnd/images/logo.ico') }}" alt="Logo" height="40">
            <span>Patient</span>
        </a>
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars"></div>
        </div>
    </div>
    <!--logo end-->

    <div class="top-nav clearfix">
        <!--search & user info start-->
        <ul class="nav pull-right top-menu">
            <li>
                <input type="text" class="form-control search" placeholder="Search">
            </li>
            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-bs-toggle="dropdown" class="dropdown-toggle" href="#">
                    <img alt="" src="{{ asset('public/BackEnd/images/avatar.jpg') }}">
                  

                    <span class="username">{{ auth()->user()->FullName ?? 'Patient' }}</span>
                    @if(Auth::check())
                        
                    @else
                        <p>Người dùng chưa đăng nhập.</p>
                        {{ dd(Auth::user()) }}
                    @endif
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <li><a href="{{ route('patient.profile') }}"><i class="fa fa-user"></i> Profile</a></li>
                    <li><a href="{{ route('patient.settings') }}"><i class="fa fa-cog"></i> Settings</a></li>
                    <li><a href="{{ route('patient.logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>
<!--header end-->

<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li class="{{ Request::is('patient/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('patient.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ Request::is('patient/appointments*') ? 'active' : '' }}">
                    <a href="{{ route('patient.appointments') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li class="{{ Request::is('patient/treatments*') ? 'active' : '' }}">
                    <a href="{{ route('patient.treatments') }}">
                        <i class="fas fa-procedures"></i>
                        <span>Treatments</span>
                    </a>
                </li>
                <li class="{{ Request::is('patient/lab*') ? 'active' : '' }}">
                    <a href="{{ route('patient.lab') }}">
                        <i class="fas fa-flask"></i>
                        <span>Lab Tests</span>
                    </a>
                </li>
                <li class="{{ Request::is('patient/pharmacy*') ? 'active' : '' }}">
                    <a href="{{ route('patient.pharmacy') }}">
                        <i class="fas fa-pills"></i>
                        <span>Prescriptions</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        @yield('content')
    </section>
    <!-- footer -->
    <div class="footer">
        <div class="wthree-copyright">
            <p>© {{ date('Y') }} Medic Hospital. All rights reserved.</p>
        </div>
    </div>
    <!-- / footer -->
</section>
<!--main content end-->
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('public/BackEnd/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/BackEnd/js/bootstrap.js') }}"></script>
@stack('scripts')
<!-- Flash Messages -->
<script>
    @if(Session::has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ Session::get("success") }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(Session::has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ Session::get("error") }}'
        });
    @endif
</script>

@yield('scripts')
</body>
</html>
