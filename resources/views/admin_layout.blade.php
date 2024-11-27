<!DOCTYPE html>
<head>
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="public/BackEnd/css/bootstrap.min.css" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="{{ asset('public/BackEnd/css/style.css') }}" rel='stylesheet' type='text/css' />
<link href="public/BackEnd/css/style-responsive.css" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="public/BackEnd/css/font.css" type="text/css"/>
<link href="public/BackEnd/css/font-awesome.css" rel="stylesheet"> 
<link rel="stylesheet" href="public/BackEnd/css/morris.css" type="text/css"/>

<!-- calendar -->
<link rel="stylesheet" href="public/BackEnd/css/monthly.css">
<!-- //calendar -->

<!-- add -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<!-- add -->

<!-- //font-awesome icons -->
<script src="public/BackEnd/js/raphael-min.js"></script>
<script src="public/BackEnd/js/morris.js"></script>

<style>

    .sidebar-menu li.active a {
        background-color: #007bff !important; 
        color: #fff !important;
        font-weight: bold;
        border-left: 4px solid #ffffff;
        transition: all 0.3s ease;
    }

    .sidebar-menu li.active i,
    .sidebar-menu li.active span {
        color: #fff !important; 
    }

   
    .sidebar-menu li:hover a {
        background-color: #0056b3;
        color: #fff !important;
    }
    .header.fixed-top {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1030;
      height: auto;
      background: rgb(240, 188, 180);
      padding: 10px 15px;
      box-sizing: border-box;
  }

  .header .brand img {
      height: 40px !important;
      width: auto; 
      max-height: none;
  }

  .dropdown-menu.extended {
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1050;
      min-width: 160px;
      padding: 5px 10px;
      background-color: #fff;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 5px;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
  }
  .header.fixed-top {
      z-index: 1050 !important;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); 

 
.top-nav .search {
    max-width: 300px; 
    margin-right: 15px; 
    float: right; 
    display: inline-block; 
}

.top-nav .dropdown {
    float: right;
    display: inline-block; 
    margin-right: 15px; 
}

.top-nav .dropdown-menu {
    right: 0 ;   
    left: auto; 
    text-align: left; 
}
.top-nav .search {
    float: right !important;
}
  
</style>

</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">
    <a href="{{ url('/dashboard')  }}" class="logo">        
        <img alt="" src="public/BackEnd/images/logo.ico" height="40px">
        <span>Admin</span>
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->


<div class="top-nav d-flex align-items-center">
    <!--search & user info start-->
    <ul class="nav ms-auto top-menu">
        <li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-bs-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="public/BackEnd/images/2.png">
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
                    <a href="{{ url('/dashboard') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="{{ Request::is('staff') ? 'active' : '' }}">
                    <a href="{{ url('/staff') }}">
                        <i class="fa fa-users"></i>
                        <span>Staff</span>
                    </a>
                </li>

                <li class="{{ Request::is('lab') ? 'active' : '' }}">
                    <a href="{{ url('/lab') }}">
                        <i class="fa fa-flask"></i>
                        <span>Lab</span>
                    </a>
                </li>

                <li class="{{ Request::is('ward') ? 'active' : '' }}">
                    <a href="{{ url('/ward') }}">
                        <i class="fa fa-hospital-o"></i>
                        <span>Ward</span>
                    </a>
                </li>

                <li class="{{ Request::is('treatment') ? 'active' : '' }}">
                    <a href="{{ url('/treatment') }}">
                        <i class="fa fa-medkit"></i>
                        <span>Treatment</span>
                    </a>
                </li>

                <li class="{{ Request::is('pharmacy') ? 'active' : '' }}">
                    <a href="{{ url('/pharmacy') }}">
                        <i class="fa fa-plus-square"></i>
                        <span>Pharmacy</span>
                    </a>
                </li>

                <li class="{{ Request::is('patient') ? 'active' : '' }}">
                    <a href="{{ url('/patient') }}">
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
<script src="public/BackEnd/js/bootstrap.js"></script>
<script src="public/BackEnd/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="public/BackEnd/js/scripts.js"></script>
<script src="public/BackEnd/js/jquery.slimscroll.js"></script>
<script src="public/BackEnd/js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="public/BackEnd/js/jquery.scrollTo.js"></script>
<!-- morris JavaScript -->	
<script>
	$(document).ready(function() {
		//BOX BUTTON SHOW AND CLOSE
	   jQuery('.small-graph-box').hover(function() {
		  jQuery(this).find('.box-button').fadeIn('fast');
	   }, function() {
		  jQuery(this).find('.box-button').fadeOut('fast');
	   });
	   jQuery('.small-graph-box .box-close').click(function() {
		  jQuery(this).closest('.small-graph-box').fadeOut(200);
		  return false;
	   });
	   
	    //CHARTS
	    function gd(year, day, month) {
			return new Date(year, month - 1, day).getTime();
		}
		
		graphArea2 = Morris.Area({
			element: 'hero-area',
			padding: 10,
        behaveLikeLine: true,
        gridEnabled: false,
        gridLineColor: '#dddddd',
        axes: true,
        resize: true,
        smooth:true,
        pointSize: 0,
        lineWidth: 0,
        fillOpacity:0.85,
			data: [
				{period: '2015 Q1', iphone: 2668, ipad: null, itouch: 2649},
				{period: '2015 Q2', iphone: 15780, ipad: 13799, itouch: 12051},
				{period: '2015 Q3', iphone: 12920, ipad: 10975, itouch: 9910},
				{period: '2015 Q4', iphone: 8770, ipad: 6600, itouch: 6695},
				{period: '2016 Q1', iphone: 10820, ipad: 10924, itouch: 12300},
				{period: '2016 Q2', iphone: 9680, ipad: 9010, itouch: 7891},
				{period: '2016 Q3', iphone: 4830, ipad: 3805, itouch: 1598},
				{period: '2016 Q4', iphone: 15083, ipad: 8977, itouch: 5185},
				{period: '2017 Q1', iphone: 10697, ipad: 4470, itouch: 2038},
			
			],
			lineColors:['#eb6f6f','#926383','#eb6f6f'],
			xkey: 'period',
            redraw: true,
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['All Visitors', 'Returning Visitors', 'Unique Visitors'],
			pointSize: 2,
			hideHover: 'auto',
			resize: true
		});
		
	   
	});
	</script>
<!-- calendar -->
	<script type="text/javascript" src="public/BackEnd/js/monthly.js"></script>
	<script type="text/javascript">
		$(window).load( function() {

			$('#mycalendar').monthly({
				mode: 'event',
				
			});

			$('#mycalendar2').monthly({
				mode: 'picker',
				target: '#mytarget',
				setWidth: '250px',
				startHidden: true,
				showTrigger: '#mytarget',
				stylePast: true,
				disablePast: true
			});

		switch(window.location.protocol) {
		case 'http:':
		case 'https:':
		// running on a server, should be good.
		break;
		case 'file:':
		alert('Just a heads-up, events will not work when run locally.');
		}

		});
	</script>
	<!-- //calendar -->
</body>
</html>
