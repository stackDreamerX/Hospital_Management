@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow register-card" data-aos="fade-up">
                <!-- Card Header -->
                <div class="card-header register-header">
                    <img src="{{ asset('public/images/logo-ccf.png') }}" alt="Medic Hospital Logo" class="register-logo">
                    <h2>Create Your Account</h2>
                    <p class="mb-0">Fill in the details to get started</p>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-4">
                    <!-- Display errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Form -->
                    <form action="{{ url('/sign-up') }}" method="POST">
                        @csrf
                        <!-- Role -->
                        <div class="mb-3">
                            <label for="RoleID" class="form-label">Role</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-user-tag"></i>
                                <select name="RoleID" id="RoleID" class="form-select input-with-icon" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="patient">Patient</option>                                      
                                </select>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" name="username" id="username" class="form-control input-with-icon" placeholder="Enter your username" value="{{ old('username') }}" required>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="FullName" class="form-label">Full Name</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-id-card"></i>
                                <input type="text" name="FullName" id="FullName" class="form-control input-with-icon" placeholder="Enter your full name" value="{{ old('FullName') }}" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="Email" class="form-label">Email Address</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-envelope"></i>
                                <input type="email" name="Email" id="Email" class="form-control input-with-icon" placeholder="Enter your email" value="{{ old('Email') }}" required>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="PhoneNumber" class="form-label">Phone Number</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-phone"></i>
                                <input type="tel" name="PhoneNumber" id="PhoneNumber" class="form-control input-with-icon" placeholder="Enter your phone number" value="{{ old('PhoneNumber') }}" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-lock"></i>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control input-with-icon" placeholder="Enter your password" required>
                                    <span class="input-group-text toggle-password" data-target="#password">
                                        <i class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-icon-group">
                                <i class="input-icon fas fa-lock"></i>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-with-icon" placeholder="Re-enter your password" required>
                                    <span class="input-group-text toggle-password" data-target="#password_confirmation">
                                        <i class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 btn-register-submit">
                            <i class="fas fa-user-plus me-2"></i> Sign Up
                        </button>
                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer text-center bg-light py-3">
                    <p class="mb-0">Already have an account?
                        <a href="{{ url('/sign-in') }}" class="btn-sign-in">
                            <i class="fas fa-sign-in-alt me-1"></i> Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .register-card {
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .register-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .register-header {
        background: var(--gradient-primary);
        color: white;
        text-align: center;
        padding: 2rem;
        border: none;
    }
    
    .register-header h2 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .register-logo {
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
        z-index: 10;
    }
    
    .input-with-icon {
        padding-left: 45px;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        height: 48px;
        border-radius: calc(var(--border-radius) / 2);
        border: 2px solid #e0e6ed;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 146, 216, 0.25);
    }
    
    .btn-register-submit {
        height: 48px;
        background: var(--gradient-primary);
        border: none;
        font-weight: 600;
        font-size: 16px;
        border-radius: calc(var(--border-radius) / 2);
        margin-top: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-register-submit:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-sign-in {
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
    
    .btn-sign-in:hover {
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
    }
    
    .toggle-password {
        cursor: pointer;
        border-left: none;
        background-color: white;
    }
    
    .input-group .form-control {
        border-right: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password Visibility
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                const targetInput = document.querySelector(this.dataset.target);
                const icon = this.querySelector('i');

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    targetInput.type = 'password';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });
    });
</script>
@endsection
