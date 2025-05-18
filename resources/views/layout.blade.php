<!DOCTYPE html>
<html lang="en" dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medic Hospital</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp

        <meta name="description" content="Medic Hospital, a non-profit academic medical center, provides clinical and hospital care and is a leader in research, education and health information.">
        <meta property="og:title" content="Access Anytime Anywhere | Medic Hospital">
        <meta property="twitter:title" content="Access Anytime Anywhere | Medic Hospital">
        <meta property="og:description" content="Medic Hospital">
        <meta property="twitter:description" content="Medic Hospital">
        <meta property="og:image" content="images/logo-ccf.png">
        <meta property="twitter:image" content="images/logo-ccf.png">
        <meta property="twitter:card" content="summary">
        <link rel="canonical" href="{{ url('/users.dashboard') }}">
        <meta property="og:url" content="{{ url('/users.dashboard') }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Medic Hospital">
        <meta property="twitter:site" content="@MedicHospital">
        <meta property="twitter:creator" content="@MedicHospital">


        <link rel="alternate" href="{{ url('/users.dashboard') }}" hreflang="x-default">

<!-- Add Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Add Bootstrap and FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/layout.css') }}">

<style>
    .avatar-circle {
        width: 32px;
        height: 32px;
        background-color: var(--primary-color);
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .avatar-initials {
        font-size: 14px;
        line-height: 1;
        text-transform: uppercase;
    }
    .dropdown-item i {
        width: 20px;
        text-align: center;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


<!-- header start -->

<header id="site-header" class="header js-site-header site-header__has-banner">

    <nav class="nav--utility js-nav--utility bg-light py-2">
        <div class="container">
            <ul class="nav justify-content-end align-items-center mb-0">
                <li class="nav-item">
                    <a href="/#notification-banner__lightbox-popup" class="nav-link text-dark">
                        <i class="fas fa-exclamation-circle text-warning"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark" href="tel:037.864.9957">
                        <i class="fas fa-phone-alt me-1"></i> Hotline: 037.864.9957
                    </a>
                </li>

                <li class="nav-item"><a class="nav-link text-dark" href="/about/history">10 Years of Medic Hospital</a></li>
                <li class="nav-item"><a class="nav-link text-dark" title="Need help?" href="/help">Need help?</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="/search" aria-label="Search" aria-controls="search-box">Search</a></li>

                @guest
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/sign-in') }}">
                        <i class="fas fa-sign-in-alt me-1"></i> Sign In
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-primary btn-sm" href="{{ url('/sign-up') }}">
                        Sign Up
                    </a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle me-2">
                            <span class="avatar-initials">{{ substr(Auth::user()->FullName, 0, 1) }}</span>
                        </div>
                        <span>{{ Auth::user()->FullName }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('home.logout') }}">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="d-flex align-items-center px-4 py-2 bg-light shadow-sm">

        <!-- Logo -->
        <span class="header__logo">
            <a href="{{ url('/users.dashboard') }}">
                <img src="{{ asset('images/logo-ccf.png') }}" alt="Medic Hospital logo">
            </a>
        </span>

        <!-- Navbar -->
        <nav>
            <ul class="nav nav-pills">
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('users.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('staff') ? 'active' : '' }}" href="{{ route('users.staff') }}">Find a Doctor</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('locations') ? 'active' : '' }}" href="{{ route('users.locations') }}">Locations & Directions</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('patients') ? 'active' : '' }}" href="{{ route('users.patients') }}">Patients & Visitors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('appointments') ? 'active' : '' }}" href="{{ route('users.appointments') }}">Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('feedback*') ? 'active' : '' }}" href="{{ route('feedback.public') }}">FeedBack</a>
                </li>
            </ul>
        </nav>

    </div>


</header>

<!-- header end -->

@yield('content')


<section class="contact-box-ribbon js-contact-box-ribbon theme-blue-gray-pattern">
    <div class="container content-pad">

        <ul class="contact-box">

                        <li class="contact-box__phone">Appointments <a href="tel:8663204573">0378.649.957</a></li>
                        <li class="contact-box__phone">Questions   <a href="tel:2164442200">0924.184.107</a></li>
                        <li><a href="/appointments" class="button--strong button--full button--arrow">Request an Appointment</a></li>

        </ul>
    </div>
</section>

<section class="footer-social js-footer-social">
        <div class="container">
            <a href="https://www.facebook.com/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-facebook" role="img" aria-label="Facebook Icon"></i> <span class="element-invisible">Facebook</span>
            </a>
            <a href="https://twitter.com/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-twitter" role="img" aria-label="Twitter Icon"></i> <span class="element-invisible">Twitter</span>
            </a>
            <a href="https://www.youtube.com/user/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-youtube" role="img" aria-label="YouTube Icon"></i> <span class="element-invisible">YouTube</span>
            </a>
            <a href="https://www.instagram.com/MedicHospital/" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-instagram" role="img" aria-label="Instagram Icon"></i> <span class="element-invisible">Instagram</span>
            </a>
            <a href="https://www.linkedin.com/company/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-linkedin" role="img" aria-label="LinkedIn Icon"></i> <span class="element-invisible">LinkedIn</span>
            </a>
            <a href="https://www.pinterest.com/MedicHospital/" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-pinterest" role="img" aria-label="Pinterest Icon"></i> <span class="element-invisible">Pinterest</span>
            </a>
            <a href="https://www.snapchat.com/add/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-snapchat" role="img" aria-label="Snapchat Icon"></i> <span class="element-invisible">Snapchat</span>
            </a>
        </div>
</section>
<footer class="footer bg-dark text-light pt-5 pb-3">
    <div class="container">
        <div class="row">
            <!-- Actions -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">Actions</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/patients/information/access">Appointments & Access</a>
                    <a href="/patients/accepted-insurance">Accepted Insurance</a>
                    <a href="/">Events Calendar</a>
                    <a href="/patients/billing-finance/financial-assistance">Financial Assistance</a>
                    <a href="/giving">Give to Cleveland Clinic</a>
                    <a href="/patients/billing-finance/payment-options">Pay Your Bill Online</a>
                    <a href="/patients/billing-finance/comprehensive-hospital-charges">Price Transparency</a>
                    <a href="/professionals/referring">Refer a Patient</a>
                    <a href="/about/contact/phone-directory">Phone Directory</a>
                    <a href="/online-services/virtual-second-opinions">Virtual Second Opinions</a>
                    <a href="/online-services/virtual-visits">Virtual Visits</a>
                </nav>
            </div>

            <!-- Blog, News & Apps -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">Blog, News & Apps</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/">Consult QD</a>
                    <a href="/">Health Essentials</a>
                    <a href="/">Newsroom</a>
                    <a href="/mobile-apps/myclevelandclinic">MyMedicHospital</a>
                    <a href="/online-services/mychart">MyChart</a>
                </nav>
            </div>

            <!-- About Medic Hospital -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">About Medic Hospital</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/about/history">10 Years of Medic Hospital</a>
                    <a href="/about">About Us</a>
                    <a href="/locations">Locations</a>
                    <a href="/departments/patient-experience/depts/quality-patient-safety">Quality & Patient Safety</a>
                    <a href="/about/community/diversity">Diversity & Inclusion</a>
                    <a href="/departments/patient-experience/depts/office-patient-experience">Patient Experience</a>
                    <a href="/research">Research & Innovations</a>
                    <a href="/about/community">Community Commitment</a>
                    <a href="/">Careers</a>
                    <a href="/about/for-employees">For Employees</a>
                    <a href="/professionals">Resources for Medical Professionals</a>
                </nav>
            </div>

            <!-- Site Info -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">Site Information & Policies</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/webcontact/webmail">Send Us Feedback</a>
                    <a href="/about/website/site-map">Site Map</a>
                    <a href="/about/website">About this Website</a>
                    <a href="/about/website/reprints-licensing">Copyright & Licensing</a>
                    <a href="/about/website/terms-of-use">Terms of Use</a>
                    <a href="/about/website/privacy-security">Privacy Policy</a>
                    <a href="/about/website/privacy-practices">Privacy Practices</a>
                    <a href="/about/website/non-discrimination-notice">Non-Discrimination Notice</a>
                </nav>
            </div>
        </div>

        <div class="text-center mt-4 small">
            9500 Euclid Avenue, Cleveland, Ohio 44195 |
            <a href="tel:8002232273" class="text-light">800.223.2273</a> |
            © 2024 Medic Hospital. All Rights Reserved.
        </div>
    </div>
</footer>

<script lang="javascript">var __vnp = {code : 24859,key:'', secret : '01a01bf0569564fa3230269838262e78'};(function() {var ga = document.createElement('script');ga.type = 'text/javascript';ga.async=true; ga.defer=true;ga.src = '//core.vchat.vn/code/tracking.js?v=44821'; var s = document.getElementsByTagName('script');s[0].parentNode.insertBefore(ga, s[0]);})();</script>

<!-- Session Timeout Modal -->
<div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="sessionTimeoutModalLabel">Session Timeout Warning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Your session is about to expire due to inactivity.</p>
                <p>You will be logged out in <span id="sessionCountdown">60</span> seconds.</p>
                <p>Would you like to stay logged in?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="logoutNowBtn">Logout Now</button>
                <button type="button" class="btn btn-primary" id="stayLoggedInBtn">Stay Logged In</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize AOS animations
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    });
</script>

@if(Auth::check())
<!-- Session Management Script - Only for authenticated users -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Session activity variables
        let lastActivity = new Date();
        let isIdle = false;
        let countdownTimer = null;
        let timeoutModal = null;
        let secondsRemaining = 60; // Countdown time in seconds

        // Session timeout in milliseconds
        const sessionTimeout = 15 * 60 * 1000; // 15 minutes
        const warningTime = 60 * 1000; // Show warning 1 minute before timeout

        // Initialize the modal
        timeoutModal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'));

        // Update last activity time on user interaction
        function updateActivity() {
            // Only update if user wasn't already idle
            if (!isIdle) {
                lastActivity = new Date();
            }
        }

        // Start countdown timer
        function startCountdown() {
            secondsRemaining = 60;
            updateCountdown();

            countdownTimer = setInterval(function() {
                secondsRemaining--;
                updateCountdown();

                if (secondsRemaining <= 0) {
                    clearInterval(countdownTimer);
                    performLogout();
                }
            }, 1000);
        }

        // Update countdown display
        function updateCountdown() {
            document.getElementById('sessionCountdown').textContent = secondsRemaining;
        }

        // Reset the session timeout
        function resetSession() {
            isIdle = false;
            lastActivity = new Date();
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }
            if (timeoutModal) {
                timeoutModal.hide();
            }
        }

        // Perform logout
        function performLogout() {
            window.location.href = "{{ route('home.logout') }}";
        }

        // Check if session might be expired
        function checkSession() {
            const now = new Date();
            const timeSinceLastActivity = now - lastActivity;

            // If we're about to timeout (less than warning time left)
            if (timeSinceLastActivity > (sessionTimeout - warningTime) && !isIdle) {
                isIdle = true;
                timeoutModal.show();
                startCountdown();
            }

            // Keep the session alive if user is active and not in countdown mode
            if (timeSinceLastActivity < (sessionTimeout / 2) && !isIdle) {
                // Send heartbeat to keep session alive
                fetch('{{ url("/users.dashboard") }}', {
                    method: 'HEAD',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).catch(error => console.error('Session heartbeat error:', error));
            }
        }

        // Set up event listeners for user activity
        ['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll'].forEach(function(event) {
            document.addEventListener(event, updateActivity, true);
        });

        // Set up button event listeners
        document.getElementById('stayLoggedInBtn').addEventListener('click', function() {
            resetSession();
        });

        document.getElementById('logoutNowBtn').addEventListener('click', function() {
            performLogout();
        });

        // Check session every minute
        setInterval(checkSession, 60000);
    });
</script>
@endif






