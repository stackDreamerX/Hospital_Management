<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
            overflow-y: auto;
        }

        .container {
            margin-top: 2rem;
            margin-bottom: 2rem;
            height: auto;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            height: auto;
            overflow: visible;
        }

        .card-header {
            background-color: #2575fc;
            color: white;
            text-align: center;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem 1rem;
            position: relative;
        }

        .card-header h3,
        .card-header p {
            color: white !important;
            margin: 0;
        }

        .card-header h3 {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: #2575fc;
            border-color: #2575fc;
        }
        .btn-primary:hover {
            background-color: #1b63d0;
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(37, 117, 252, 0.6);
        }
        .text-muted {
            color: #6c757d !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <h3>Create Your Account</h3>
                        <p class="mb-0">Fill in the details to get started</p>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <!-- Hiển thị lỗi -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Form -->
                        <form action="{{ url('/sign-up') }}" method="POST">
                                @csrf
                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="RoleID" class="form-label">Role</label>
                                    <select name="RoleID" id="RoleID" class="form-select" required>
                                        <option value="" disabled selected>Select Role</option>
                                        <option value="patient">Patient</option>                                      
                                    </select>
                                </div>

                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" value="{{ old('username') }}" required>
                                </div>

                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label for="FullName" class="form-label">Full Name</label>
                                    <input type="text" name="FullName" id="FullName" class="form-control" placeholder="Enter your full name" value="{{ old('FullName') }}" required>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="Email" class="form-label">Email Address</label>
                                    <input type="email" name="Email" id="Email" class="form-control" placeholder="Enter your email" value="{{ old('Email') }}" required>
                                </div>

                                <!-- Phone Number -->
                                <div class="mb-3">
                                    <label for="PhoneNumber" class="form-label">Phone Number</label>
                                    <input type="tel" name="PhoneNumber" id="PhoneNumber" class="form-control" placeholder="Enter your phone number" value="{{ old('PhoneNumber') }}" required>
                                </div>

                                <!-- Password -->
                                <div class="mb-3 position-relative">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                        <span class="input-group-text toggle-password" data-target="#password">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3 position-relative">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-enter your password" required>
                                        <span class="input-group-text toggle-password" data-target="#password_confirmation">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                            </form>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer text-center bg-light py-3">
                        <p class="mb-0">Already have an account?
                            <a href="{{ url('/sign-in') }}" class="text-primary">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add this script before closing body tag -->
    <script>
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
</script>
</body>
</html>
