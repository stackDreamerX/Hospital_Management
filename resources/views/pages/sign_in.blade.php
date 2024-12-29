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


<style>

    
</style>
</head>
<body class="login-body">
<div class="log-w3">
    <div class="w3layouts-main">
        <h2>Đăng nhập</h2>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ $errors->first() }}
               
            </div>
        @endif
        
        <form action="{{ route('home_dashboard') }}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="USERNAME" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="PASSWORD" required="">
                </div>
                <div class="checkbox">
                    <label><input type="checkbox"> Nhớ đăng nhập</label>
                </div>
                <div class="form-group text-right">
                    <a href="#">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="btn btn-success btn-block" name="sign-in">Đăng nhập</button>
        </form>
        <div class="text-center mt-3">
            <p>Không có tài khoản? <a href="{{ url('/sign-up') }}" class="btn btn-yellow btn-sm">Đăng ký</a></p>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
