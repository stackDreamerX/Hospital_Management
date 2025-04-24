<!DOCTYPE html>
<head>
<title>Admin Dashboard</title>
<link rel="icon" type="image/x-icon" href="{{ asset('public/logo.ico') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ asset('public/css/admin_layout.css') }}" rel="stylesheet">

<style>
    /* Submenu styles */
    .sidebar-menu .collapse {
        padding-left: 20px;
    }
    
    .sidebar-menu .collapse li a {
        padding: 10px 10px 10px 30px;
        font-size: 14px;
        border-left: 3px solid transparent;
    }
    
    .sidebar-menu .collapse li.active a {
        background: rgba(255, 255, 255, 0.1);
        border-left: 3px solid #fff;
    }
    
    .sidebar-menu .dropdown-toggle::after {
        display: inline-block;
        float: right;
        content: "\f107";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        transition: transform 0.3s;
    }
    
    .sidebar-menu .dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }
</style>

<?php use Illuminate\Support\Facades\Session; ?>

@stack('styles')
</head>


<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<!--logo start-->
<div class="brand">
    <a href="{{ url('admin/dashboard')  }}" class="logo">
        <img alt="" src="{{ asset('public/logo.ico') }}" height="40px">
        <span>Admin</span>
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->


<div class="nav notify-row" id="top_menu">
    
</div>

<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img alt="" src="{{ asset('public/2.png') }}">
                <span class="username">{{ auth()->user()->FullName ?? 'Admin' }}</span>
                <b class="caret"></b>   
            </a>
            <ul class="dropdown-menu dropdown-menu-end logout">
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-suitcase"></i> Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->

       
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="{{ Request::is('admin/staff') ? 'active' : '' }}">
                    <a href="{{ url('/admin/staff') }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Staff</span>
                    </a>
                </li>

                <li class="{{ Request::is('admin/lab') ? 'active' : '' }}">
                    <a href="{{ url('/admin/lab') }}">
                        <i class="fa-solid fa-flask"></i>
                        <span>Lab</span>
                    </a>
                </li>
<!-- 
                <li class="{{ Request::is('admin/ward') ? 'active' : '' }}">
                    <a href="{{ url('/admin/ward') }}">
                        <i class="fa-solid fa-hospital"></i>
                        <span>Ward</span>
                    </a>
                </li> -->

                <li class="{{ Request::is('admin/treatment') ? 'active' : '' }}">
                    <a href="{{ url('/admin/treatment') }}">
                        <i class="fa-solid fa-briefcase-medical"></i>
                        <span>Treatment</span>
                    </a>
                </li>

                <li class="{{ Request::is('admin/pharmacy') ? 'active' : '' }}">
                    <a href="{{ url('/admin/pharmacy') }}">
                        <i class="fa-solid fa-prescription-bottle-medical"></i>
                        <span>Pharmacy</span>
                    </a>
                </li>

                <li class="{{ Request::is('admin/patient') ? 'active' : '' }}">
                    <a href="{{ url('/admin/patient') }}">
                        <i class="fa-solid fa-hospital-user"></i>
                        <span>Patient</span>
                    </a>
                </li>

                <li class="{{ Request::is('admin/ward*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/ward') }}">
                        <i class="fa-solid fa-hospital"></i>
                        <span>ward</span>
                    </a>
                </li>


                <li class="{{ Request::is('admin/feedback*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/feedback') }}">
                        <i class="fa-solid fa-comment-dots"></i>
                        <span>feedback</span>
                    </a>
                </li>

                <li class="{{ Request::is('beds*') || Request::is('allocations*') || Request::is('bed-history*') ? 'active' : '' }}">
                    <a class="dropdown-toggle" href="#bedManagement" data-bs-toggle="collapse" aria-expanded="false">
                        <i class="fa-solid fa-bed"></i>
                        <span>Bed Management</span>
                    </a>
                    <ul class="collapse list-unstyled {{ Request::is('beds*') || Request::is('allocations*') || Request::is('bed-history*') ? 'show' : '' }}" id="bedManagement">
                        <li class="{{ Request::is('beds*') && !Request::is('beds/*/history') ? 'active' : '' }}">
                            <a href="{{ route('beds.index') }}">
                                <i class="fa-solid fa-bed"></i> Beds List
                            </a>
                        </li>
                        <li class="{{ Request::is('allocations*') ? 'active' : '' }}">
                            <a href="{{ route('allocations.index') }}">
                                <i class="fa-solid fa-user-plus"></i> Patient Allocations
                            </a>
                        </li>
                        <li class="{{ Request::is('bed-history*') || Request::is('beds/*/history') ? 'active' : '' }}">
                            <a href="{{ route('bed-history.index') }}">
                                <i class="fa-solid fa-clock-rotate-left"></i> Bed History
                            </a>
                        </li>
                        <li class="{{ Request::is('bed-utilization-report') ? 'active' : '' }}">
                            <a href="{{ route('bed-history.report') }}">
                                <i class="fa-solid fa-chart-pie"></i> Utilization Report
                            </a>
                        </li>
                    </ul>
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
                @yield('admin_content')
        </section>
    <!-- footer -->
            <div class="footer">
                <div class="wthree-copyright">
                <p>Welcome to my website <a href="{{ url('/') }}">Medic Hospital</a></p>
                </div>
            </div>
    <!-- / footer -->
    </section>
    <!--main content end-->

</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@stack('scripts')
<script>
    // Initialize Bootstrap dropdowns when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap 5 dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function(dropdown) {
            return new bootstrap.Dropdown(dropdown);
        });
    });

    $(document).ready(function () {
        // Toggle sidebar on mobile
        $('.sidebar-toggle-box').on('click', function() {
            $('#sidebar').toggleClass('show');
        });
        
        // Handle sidebar menu click events
        $('.sidebar-menu li a').on('click', function () {
            // Remove active class from all items
            $('.sidebar-menu li').removeClass('active');
            // Add active class to clicked item
            $(this).parent('li').addClass('active');
        });

        // Manual dropdown handling (backup in case Bootstrap initialization fails)
        $('.dropdown-toggle').on('click', function(e) {
            $(this).siblings('.dropdown-menu').toggleClass('show');
        });

        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
        
        // Sidebar submenu toggle
        $('.sidebar-menu .dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            
            // Toggle the collapse element
            const target = $(this).attr('href');
            $(target).collapse('toggle');
            
            // Toggle the arrow icon
            $(this).find('.fa-angle-down, .fa-angle-up').toggleClass('fa-angle-down fa-angle-up');
        });
        
        // Auto-expand active submenus on page load
        $('.sidebar-menu .collapse').each(function() {
            if ($(this).find('li.active').length) {
                $(this).addClass('show');
            }
        });
    });
</script>
@yield('scripts')
</body>
</html>