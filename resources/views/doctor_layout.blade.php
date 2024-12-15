<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2C3E50;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 1rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .content-wrapper {
            min-height: 100vh;
            background: #f4f6f9;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: 1rem;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 3px 6px;
            border-radius: 50%;
            font-size: 0.7rem;
            background: #dc3545;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- User Profile -->
            <div class="user-profile">
                <img src="https://via.placeholder.com/40" alt="Doctor">
                <div>
                    <div class="fw-bold">Dr. {{ Session::get('doctor_name', 'Doctor') }}</div>
                    <small>Doctor</small>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="nav flex-column">
                <a class="nav-link {{ Request::is('doctor/dashboard*') ? 'active' : '' }}" 
                   href="{{ route('doctor.dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link {{ Request::is('doctor/appointments*') ? 'active' : '' }}" 
                   href="{{ route('doctor.appointments') }}">
                    <i class="fas fa-calendar-alt"></i> Appointments
                    @if(isset($pendingAppointments) && $pendingAppointments > 0)
                        <span class="notification-badge">{{ $pendingAppointments }}</span>
                    @endif
                </a>
                <a class="nav-link {{ Request::is('doctor/patients*') ? 'active' : '' }}" 
                   href="{{ route('doctor.patients') }}">
                    <i class="fas fa-users"></i> My Patients
                </a>
                <a class="nav-link {{ Request::is('doctor/treatments*') ? 'active' : '' }}" 
                   href="{{ route('doctor.treatments') }}">
                    <i class="fas fa-procedures"></i> Treatments
                </a>
                <a class="nav-link {{ Request::is('doctor/lab*') ? 'active' : '' }}" 
                   href="{{ route('doctor.lab') }}">
                    <i class="fas fa-flask"></i> Lab Tests
                    @if(isset($pendingLabs) && $pendingLabs > 0)
                        <span class="notification-badge">{{ $pendingLabs }}</span>
                    @endif
                </a>
                <a class="nav-link {{ Request::is('doctor/pharmacy*') ? 'active' : '' }}" 
                   href="{{ route('doctor.pharmacy') }}">
                    <i class="fas fa-pills"></i> Pharmacy
                </a>                
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="btn btn-link d-lg-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                @if(isset($notifications) && count($notifications) > 0)
                                    <span class="notification-badge">{{ count($notifications) }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if(isset($notifications) && count($notifications) > 0)
                                    @foreach($notifications as $notification)
                                        <a class="dropdown-item" href="#">{{ $notification['message'] }}</a>
                                    @endforeach
                                @else
                                    <div class="dropdown-item">No new notifications</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Flash messages
            @if(Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ Session::get("success") }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ Session::get("error") }}'
                });
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>
