@extends('layout')

@section('content')
<div class="admin-login-container">
    <div class="login-wrapper">
        <div class="login-card-container" data-aos="fade-up">
            <div class="login-card-left">
                <div class="hospital-brand">
                    <div class="brand-row">
                        <img src="{{ asset('public/images/logo.png') }}" alt="Medic Hospital Logo" class="hospital-logo">
                        <h1>Medic Hospital</h1>
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
                    <h3>Sign in to access your account</h3>
                </div>
                
                <div class="login-body">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                        </div>
                    @endif
                    
                    @if (session('timeout'))
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-clock me-2"></i> {{ session('timeout') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('home_dashboard') }}" method="post">
                        @csrf
                        <div class="input-icon-group">
                            <i class="input-icon fas fa-user"></i>
                            <input type="text" class="form-control input-with-icon" name="username" placeholder="Username" required>
                        </div>
                        
                        <div class="input-icon-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" class="form-control input-with-icon" name="password" placeholder="Password" required>
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

<!-- Session Timeout Modal -->
<div class="modal fade" id="timeoutModal" tabindex="-1" aria-labelledby="timeoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timeoutModalLabel"><i class="fas fa-clock"></i> Session Timeout Warning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Your session is about to expire due to inactivity.</p>
                <p>You will be automatically logged out in <span id="countdown">60</span> seconds.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="logoutNow">Logout Now</button>
                <button type="button" class="btn btn-primary" id="stayLoggedIn">Stay Logged In</button>
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
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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
    
    .login-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .login-header h3 {
        color: #777;
        font-size: 1.1rem;
        font-weight: normal;
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
        color: var(--primary-color);
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
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 146, 216, 0.1);
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
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .forgot-password:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }
    
    .btn-login {
        background: var(--gradient-primary);
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
        box-shadow: 0 10px 20px rgba(0, 146, 216, 0.2);
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
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .register-link a:hover {
        color: var(--primary-dark);
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Session timeout settings
        const inactivityTime = 30 * 60 * 1000; // 30 minutes in milliseconds
        const warningTime = 60 * 1000; // 1 minute warning before timeout
        const countdownDuration = 60; // 60 seconds countdown
        
        let inactivityTimer;
        let countdownTimer;
        let countdownValue = countdownDuration;
        let timeoutModal;
        
        // Initialize the modal (requires Bootstrap 5)
        if (typeof bootstrap !== 'undefined') {
            timeoutModal = new bootstrap.Modal(document.getElementById('timeoutModal'));
        }
        
        // Function to reset the inactivity timer
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(showWarning, inactivityTime - warningTime);
        }
        
        // Function to show the warning modal
        function showWarning() {
            // Reset countdown
            countdownValue = countdownDuration;
            document.getElementById('countdown').innerText = countdownValue;
            
            // Show the modal
            if (timeoutModal) {
                timeoutModal.show();
            } else {
                // Fallback if bootstrap is not available
                alert(`Your session will expire in ${countdownValue} seconds due to inactivity.`);
            }
            
            // Start the countdown
            startCountdown();
        }
        
        // Function to start the countdown
        function startCountdown() {
            countdownTimer = setInterval(function() {
                countdownValue--;
                
                if (countdownValue <= 0) {
                    clearInterval(countdownTimer);
                    logout();
                    return;
                }
                
                document.getElementById('countdown').innerText = countdownValue;
            }, 1000);
        }
        
        // Function to handle logout
        function logout() {
            window.location.href = "{{ route('home.logout') }}";
        }
        
        // Function to stay logged in
        function stayLoggedIn() {
            clearInterval(countdownTimer);
            resetInactivityTimer();
            
            if (timeoutModal) {
                timeoutModal.hide();
            }
        }
        
        // Event listeners for user activity
        const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'];
        
        activityEvents.forEach(function(eventName) {
            document.addEventListener(eventName, resetInactivityTimer, true);
        });
        
        // Button event listeners
        document.getElementById('stayLoggedIn').addEventListener('click', stayLoggedIn);
        document.getElementById('logoutNow').addEventListener('click', logout);
        
        // Initialize the timer when the page loads
        resetInactivityTimer();
    });
</script>
@endsection
