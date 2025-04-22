@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow login-card" data-aos="fade-up">
                <div class="login-header">
                    <img src="{{ asset('public/images/logo-ccf.png') }}" alt="Medic Hospital Logo" class="hospital-logo">
                    <h2>Welcome Back</h2>
                    <p>Sign in to access your account</p>
                </div>
                
                <div class="card-body login-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <form action="{{ route('home_dashboard') }}" method="post">
                        @csrf
                        <div class="input-icon-group mb-3">
                            <i class="input-icon fas fa-user"></i>
                            <input type="text" class="form-control input-with-icon" name="username" placeholder="Username" required>
                        </div>
                        
                        <div class="input-icon-group mb-3">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" class="form-control input-with-icon" name="password" placeholder="Password" required>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="forgot-password">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-login" name="sign-in">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </button>
                    </form>
                </div>
                
                <div class="card-footer register-link py-3">
                    <p class="mb-0">Don't have an account? 
                        <a href="{{ url('/sign-up') }}" class="btn-register">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-card {
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .login-header {
        background: var(--gradient-primary);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }
    
    .login-header h2 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .login-body {
        padding: 2rem;
    }
    
    .hospital-logo {
        max-width: 80px;
        margin-bottom: 1rem;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .input-icon-group {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: var(--primary-color);
        font-size: 18px;
    }
    
    .input-with-icon {
        padding-left: 45px;
        height: 50px;
        border-radius: calc(var(--border-radius) / 2);
    }
    
    .btn-login {
        height: 48px;
        background: var(--gradient-primary);
        border: none;
        font-weight: 600;
        border-radius: calc(var(--border-radius) / 2);
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .forgot-password {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .forgot-password:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }
    
    .register-link {
        background-color: #f8f9fa;
        text-align: center;
        border-top: 1px solid #eee;
    }
    
    .btn-register {
        background-color: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        padding: 0.4rem 1rem;
        border-radius: calc(var(--border-radius) / 2);
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        margin-left: 0.5rem;
    }
    
    .btn-register:hover {
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
    }
</style>
@endsection
