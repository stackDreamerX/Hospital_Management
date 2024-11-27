<!DOCTYPE html>
<head>
<title>Sign In</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="public/BackEnd/css/bootstrap.min.css" >
<!-- Custom CSS -->
<link href="public/BackEnd/css/style.css" rel='stylesheet' type='text/css' />
<link href="public/BackEnd/css/style-responsive.css" rel="stylesheet"/>
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="public/BackEnd/css/font.css" type="text/css"/>
<link href="public/BackEnd/css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="js/jquery2.0.3.min.js"></script>
</head>
<body class="login-body">
<div class="log-w3">
    <div class="w3layouts-main">
        <h2>Đăng nhập</h2>
        <?php
            $message = Session::get("message");
            if ($message) {
                echo '<span class="text-alert">'.$message. '</span>';
                Session::put('message',null);
            }
        ?>
        <form action="{{ url('/home-dashboard') }}" method="post">
            @csrf
            <!-- {{ csrf_field() }}   -->
            <input type="text" class="form-control" name="email" placeholder="E-MAIL" required="">
            <input type="password" class="form-control" name="password" placeholder="PASSWORD" required="">
            <div class="d-flex justify-content-between">
                <span><input type="checkbox" /> Nhớ đăng nhập</span>
                <h6><a href="#">Quên mật khẩu?</a></h6>
            </div>
            <div class="clearfix"></div>
            <input type="submit" value="Đăng nhập" name="sign-in" class="btn btn-success btn-block">
        </form>
        <div class="text-center mt-3">
            <p>Không có tài khoản? <a href="{{ url('/sign-up') }}" class="btn btn-yellow btn-sm">Đăng ký</a></p>
        </div>
    </div>
</div>
<script src="public/BackEnd/js/bootstrap.js"></script>
<script src="public/BackEnd/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="public/BackEnd/js/scripts.js"></script>
<script src="public/BackEnd/js/jquery.slimscroll.js"></script>
<script src="public/BackEnd/js/jquery.nicescroll.js"></script>
<script src="public/BackEnd/js/jquery.scrollTo.js"></script>
</body>
</html>
