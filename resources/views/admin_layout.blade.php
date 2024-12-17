<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<head>
<title>Visitors an Admin Panel Category Bootstrap Responsive Website Template | Home :: w3layouts</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="../public/Backend/css/bootstrap.min.css" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="../public/Backend/css/style.css" rel='stylesheet' type='text/css' />
<link href="../public/Backend/css/style-responsive.css" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="../public/Backend/css/font.css" type="text/css"/>
<link href="../public/Backend/css/font-awesome.css" rel="stylesheet"> 
<link rel="stylesheet" href="../public/Backend/css/morris.css" type="text/css"/>
<!-- calendar -->
<link rel="stylesheet" href="../public/Backend/css/monthly.css">
<!-- //calendar -->
<!-- //font-awesome icons -->
<script src="../public/Backend/js/jquery2.0.3.min.js"></script>
<script src="../public/Backend/js/raphael-min.js"></script>
<script src="../public/Backend/js/morris.js"></script>

<style> 
        /* Sidebar menu hover and click effect */
    .sidebar-menu li a {
        transition: background-color 0.3s, color 0.3s;
    }

    .sidebar-menu li a:hover, 
    .sidebar-menu li.active > a {
        background-color: #3d7fc1; /* Đổi màu nền */
        color: #fff; /* Đổi màu chữ */
        border-radius: 5px;
    }
    
    /* Hiệu ứng click */
    .sidebar-menu li a:active {
        transform: scale(0.98); /* Hiệu ứng nhấn */
    }
</style>

@stack('styles')
</head>


<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">  
<!--logo start-->
<!--logo start-->
<div class="brand">
    <a href="{{ url('/dashboard')  }}" class="logo">        
        <img alt="" src="../public/Backend/images/logo.ico" height="40px">
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
            <a data-bs-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="../public/Backend/images/2.png">
                <span class="username">
					<?php
						$name = Session::get("admin_name");
						if ($name) {
								echo $name;								
						}
					?>
				</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-menu-end logout">
                <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-key"></i> Đăng xuất</a></li>
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
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="{{ Request::is('staff') ? 'active' : '' }}">
                    <a href="{{ url('/admin/staff') }}">
                        <i class="fa fa-users"></i>
                        <span>Staff</span>
                    </a>
                </li>

                <li class="{{ Request::is('lab') ? 'active' : '' }}">
                    <a href="{{ url('/admin/lab') }}">
                        <i class="fa fa-flask"></i>
                        <span>Lab</span>
                    </a>
                </li>

                <li class="{{ Request::is('ward') ? 'active' : '' }}">
                    <a href="{{ url('/admin/ward') }}">
                        <i class="fa fa-hospital-o"></i>
                        <span>Ward</span>
                    </a>
                </li>

                <li class="{{ Request::is('treatment') ? 'active' : '' }}">
                    <a href="{{ url('/admin/treatment') }}">
                        <i class="fa fa-medkit"></i>
                        <span>Treatment</span>
                    </a>
                </li>

                <li class="{{ Request::is('pharmacy') ? 'active' : '' }}">
                    <a href="{{ url('/admin/pharmacy') }}">
                        <i class="fa fa-plus-square"></i>
                        <span>Pharmacy</span>
                    </a>
                </li>

                <li class="{{ Request::is('patient') ? 'active' : '' }}">
                    <a href="{{ url('/admin/patient') }}">
                        <i class="fa fa-user"></i>
                        <span>Patient</span>
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

<script src="../public/Backend/js/bootstrap.js"></script>
<script src="../public/Backend/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="../public/Backend/js/scripts.js"></script>
<script src="../public/Backend/js/jquery.slimscroll.js"></script>
<script src="../public/Backend/js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="../public/Backend/js/jquery.scrollTo.js"></script>
<!-- morris JavaScript -->	
@stack('scripts')
<script>  
    $(document).ready(function () {
    // Xử lý sự kiện click vào sidebar menu
    $('.sidebar-menu li a').on('click', function () {
        // Xóa class active ở tất cả các mục
        $('.sidebar-menu li').removeClass('active');
        // Thêm class active vào mục đang được click
        $(this).parent('li').addClass('active');
    });
});

</script>
@yield('scripts')
</body>
</html>