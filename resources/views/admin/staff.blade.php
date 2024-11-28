@extends('admin_layout')
@section('admin_content') 


<style>
    input::placeholder,
    select::placeholder {
        color: #6c757d !important; /* Added !important to override any other styles */
        opacity: 1 !important;
    }

    /* Add this to ensure placeholder text in select elements is visible */
    select option:first-child {
        color: #6c757d;
    }

    /* Reset autofill styles */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: inherit !important;
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Make empty password input show placeholder */
    input[type="password"]:placeholder-shown {
        font-family: inherit !important;
    }

    /* Keep the dots when user starts typing */
    input[type="password"]:not(:placeholder-shown) {
        font-family: password !important;
    }
</style>


<section class="container mt-4">
    <!-- Staff Management Title -->
    <h3 class="mb-3">Staff Management</h3>
    
    <!-- Form Section -->
    <div class="card p-4">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <button class="btn btn-primary mb-3">Generate Report</button>
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="First Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Last Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control">
                                <option>Role</option>
                                <option>Doctor</option>
                                <option>Nurse</option>
                                <option>Pharmacist</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control">
                                <option>Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Mobile Number">
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" placeholder="Address">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="CCCD">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="date" class="form-control" placeholder="Date of Birth">
                        </div>
                        <div class="col-md-6 mb-3 position-relative">
                            <input type="password" 
                                   id="password" 
                                   class="form-control" 
                                   placeholder="Password"
                                   autocomplete="new-password">
                            <i class="fas fa-eye position-absolute" style="top: 50%; right: 10px; cursor: pointer; transform: translateY(-50%);" onclick="togglePassword('password', this)"></i>
                        </div>
                        <div class="col-md-6 mb-3 position-relative">
                            <input type="password" 
                                   id="confirmPassword" 
                                   class="form-control" 
                                   placeholder="Confirm Password"
                                   autocomplete="new-password">
                            <i class="fas fa-eye position-absolute" style="top: 50%; right: 10px; cursor: pointer; transform: translateY(-50%);" onclick="togglePassword('confirmPassword', this)"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-success me-2">Register</button>
                        <button type="button" class="btn btn-primary me-2">Update</button>
                        <button type="button" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>

            <!-- Right Column (Search ID) -->
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="ID">
                    <button class="btn btn-warning">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Doctors Table -->
    <div class="card mt-4 p-4">
        <h5>Recent Doctors</h5>
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</small>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>NIC</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Madhusha</td>
                    <td>Doctor</td>
                    <td>Male</td>
                    <td>madhusha@gmail.com</td>
                    <td>078-66622616</td>
                    <td>86362626</td>
                    <td>1999-04-13</td>
                    <td><span class="badge bg-success">Online</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteDoctor(1)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Prasad</td>
                    <td>Doctor</td>
                    <td>Male</td>
                    <td>prasad@gmail.com</td>
                    <td>078-12345678</td>
                    <td>11161616</td>
                    <td>1999-04-13</td>
                    <td><span class="badge bg-success">Online</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteDoctor(2)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Prasad</td>
                    <td>Doctor</td>
                    <td>Male</td>
                    <td>prasad@gmail.com</td>
                    <td>078-12345678</td>
                    <td>11161616</td>
                    <td>1999-04-13</td>
                    <td><span class="badge bg-success">Online</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteDoctor(2)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>Prasad</td>
                    <td>Doctor</td>
                    <td>Male</td>
                    <td>prasad@gmail.com</td>
                    <td>078-12345678</td>
                    <td>11161616</td>
                    <td>1999-04-13</td>
                    <td><span class="badge bg-success">Online</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteDoctor(2)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<script>
    function deleteDoctor(doctorId) {
        if (confirm('Are you sure you want to delete this doctor?')) {
            // Here, you would send a request to your backend to delete the doctor.
            console.log('Doctor with ID ' + doctorId + ' deleted');
            // Optionally, remove the row from the table using JavaScript.
        }
    }

    function togglePassword(inputId, eyeIcon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

@endsection
