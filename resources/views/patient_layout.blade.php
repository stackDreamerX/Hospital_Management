<!DOCTYPE html>
<html lang="en">
<head>
<title>Patient Dashboard</title>
<link rel="icon" type="image/x-icon" href="{{ asset('public/logo.ico') }}">

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Hospital Management System" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>


<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('public/css/patient_layout.css') }}">



@stack('styles')
</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
    <!--logo start-->
    <div class="brand">
        <a href="{{ route('users.dashboard') }}" class="logo">
            <img src="{{ asset('public/logo.ico') }}" alt="Logo" height="40">
            <span>Patient Portal</span>
        </a>
        <div class="sidebar-toggle-box" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </div>
    </div>
    <!--logo end-->

    <div class="top-nav clearfix">
        <!--search & user info start-->
        <ul class="nav top-menu">
            <li>
                <input type="text" class="form-control search" placeholder="Search">
            </li>
            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-bs-toggle="dropdown" class="dropdown-toggle" href="#">
                    <img alt="" src="{{ asset('public/avatar.jpg') }}">
                    <span class="username">{{ auth()->user()->FullName ?? 'Patient' }}</span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <li><a href="{{ route('patient.profile') }}"><i class="fa fa-user"></i> Profile</a></li>
                    <li><a href="{{ route('patient.settings') }}"><i class="fa fa-cog"></i> Settings</a></li>
                    <li><a href="{{ route('patient.logout') }}"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
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
                    <a href="{{ route('patient.appointments.index') }}">
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
                <li class="{{ Request::is('feedback*') ? 'active' : '' }}">
                    <a href="{{ route('feedback.create') }}">
                        <i class="fas fa-comment-dots"></i>
                        <span>Provide Feedback</span>
                    </a>
                </li>
                <li class="{{ Request::is('my-feedback*') ? 'active' : '' }}">
                    <a href="{{ route('feedback.user') }}">
                        <i class="fas fa-comments"></i>
                        <span>My Feedback</span>
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
    <section class="wrapper" style="background: linear-gradient(135deg, #1a3a8f 0%, #2c3e50 100%); color: #fff;">
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

<!-- Sidebar Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
    });
</script>

<!-- Flash Messages -->
@if(Session::has('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ Session::get("success") }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif

@if(Session::has('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ Session::get("error") }}'
        });
    });
</script>
@endif

<!-- Session Keeper -->
<script>
    // Session activity variables
    let lastActivity = new Date();
    let isIdle = false;
    const sessionTimeout = {{ config('session.lifetime', 30) * 60 * 1000 }}; // Convert minutes to milliseconds

    // Update last activity time on user interaction
    function updateActivity() {
        const now = new Date();
        // If user was idle and is now active, refresh the page to ensure proper state
        if (isIdle) {
            location.reload();
            return;
        }
        lastActivity = now;
    }

    // Check if session might be expired
    function checkSession() {
        const now = new Date();
        const timeSinceLastActivity = now - lastActivity;
        
        // If we've been inactive for more than session timeout minus 1 minute
        if (timeSinceLastActivity > (sessionTimeout - 60000)) {
            isIdle = true;
        }
        
        // Keep the session alive if user is active
        if (timeSinceLastActivity < (sessionTimeout / 2)) {
            // Send heartbeat to keep session alive
            fetch('{{ route("patient.dashboard") }}', {
                method: 'HEAD',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(error => console.error('Session heartbeat error:', error));
        }
    }

    // Set up event listeners for user activity
    ['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll'].forEach(event => {
        document.addEventListener(event, updateActivity, true);
    });

    // Check session every minute
    setInterval(checkSession, 60000);
</script>

@yield('scripts')
</body>
</html>
