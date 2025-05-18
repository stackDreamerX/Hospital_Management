@extends('layout')

@section('content')
<?php use Illuminate\Support\Facades\Session; ?>
<div class="admin-login-container">
    <div class="login-wrapper">
        <div class="login-card-container" data-aos="fade-up">
            <div class="login-card-left">
                <div class="hospital-brand">
                    <div class="brand-row">
                        <img src="{{ asset('images/logo.png') }}" alt="Medic Hospital Logo" class="hospital-logo">
                        <h1>Admin Medic Hospital</h1>
                    </div>
                </div>
                <div class="login-decoration">
                    <div class="medical-icon-container">
                        <i class="fas fa-heartbeat pulse-icon"></i>
                        <i class="fas fa-stethoscope float-icon"></i>
                        <i class="fas fa-user-md float-icon-alt"></i>
                        <i class="fas fa-pills pulse-icon-alt"></i>
                    </div>
                </div>
            </div>

            <div class="login-card-right">
                <div class="login-header">
                    <h1>Welcome Back</h1>
                    <h3>Sign in to your admin dashboard</h3>
                </div>

                <div class="login-body">
                <?php
                    $message = Session::get("message");
                    if ($message) {
                        echo '<div class="alert alert-danger alert-dismissable">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                        echo $message;
                        echo '</div>';
                        Session::put('message', null);
                    }
                ?>

                    <form action="{{ route('dashboard') }}" method="post">
                        @csrf
                        <div class="input-icon-group">
                            <i class="input-icon fas fa-user"></i>
                            <input type="text" class="form-control input-with-icon" name="admin_email" placeholder="Username" required>
                        </div>

                        <div class="input-icon-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" class="form-control input-with-icon" name="admin_password" placeholder="Password" required>
                        </div>

                        <div class="login-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="forgot-password">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn-login" name="sign-in">
                            <span>Sign In</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>

                        <div class="register-link">
                            <p>Don't have an account?
                                <a href="{{ url('/sign-up') }}">Create Account</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .login-wrapper {
        width: 100%;
        max-width: 1000px;
    }

    .login-card-container {
        display: flex;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        height: 600px;
    }

    .login-card-left {
        width: 40%;
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        color: white;
        display: flex;
        flex-direction: column;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .hospital-brand {
        text-align: center;
        z-index: 2;
        margin-bottom: 1.5rem;
    }

    .brand-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .hospital-logo {
        max-width: 150px;
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.3));
    }

    .hospital-brand h1 {
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin: 0;
        color: white;
    }

    .login-decoration {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .medical-icon-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .medical-icon-container i {
        position: absolute;
        font-size: 2.5rem;
        opacity: 0.5;
    }

    .pulse-icon {
        top: 20%;
        left: 30%;
        animation: pulse 3s infinite;
    }

    .pulse-icon-alt {
        bottom: 20%;
        right: 30%;
        animation: pulse 4s infinite;
    }

    .float-icon {
        top: 40%;
        right: 20%;
        animation: float 6s infinite;
    }

    .float-icon-alt {
        bottom: 40%;
        left: 20%;
        animation: float 5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
        100% { opacity: 0.3; transform: scale(1); }
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }

    .login-card-right {
        width: 60%;
        padding: 3rem;
        display: flex;
        flex-direction: column;
    }

    .login-header {
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .login-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .login-header p {
        color: #777;
        font-size: 1rem;
    }

    .login-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .input-icon-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #4b6cb7;
        font-size: 1.2rem;
    }

    .input-with-icon {
        padding: 1rem 1rem 1rem 3rem;
        border-radius: 10px;
        border: 2px solid #e1e5ee;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .input-with-icon:focus {
        border-color: #4b6cb7;
        box-shadow: 0 0 0 3px rgba(75, 108, 183, 0.1);
    }

    .login-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .form-check-label {
        color: #666;
    }

    .forgot-password {
        color: #4b6cb7;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .forgot-password:hover {
        color: #182848;
        text-decoration: underline;
    }

    .btn-login {
        background: linear-gradient(to right, #4b6cb7, #182848);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .btn-login span {
        margin-right: 0.5rem;
    }

    .btn-login i {
        transition: transform 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(24, 40, 72, 0.2);
    }

    .btn-login:hover i {
        transform: translateX(5px);
    }

    .register-link {
        text-align: center;
    }

    .register-link p {
        color: #666;
        margin: 0;
    }

    .register-link a {
        color: #4b6cb7;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .register-link a:hover {
        color: #182848;
        text-decoration: underline;
    }

    .alert {
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    /* Responsive design */
    @media (max-width: 992px) {
        .login-card-container {
            flex-direction: column;
            height: auto;
        }

        .login-card-left, .login-card-right {
            width: 100%;
        }

        .login-card-left {
            padding: 2rem;
            min-height: 200px;
        }
    }
</style>
@endsection
