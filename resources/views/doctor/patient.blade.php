@extends('admin_layout')
@section('admin_content')


<style>
    /* Base placeholder styles */
    input::placeholder,
    textarea::placeholder,
    select::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
    }

    /* Specific email input styles */
    input[type="email"] {
        color: #212529;  /* Default text color */
    }
    input[type="email"]::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
    }

    /* Password specific styles */
    input[type="password"] {
        font-family: inherit;
    }
    input[type="password"]::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
        font-family: inherit !important;
    }
    input[type="password"]:not(:placeholder-shown) {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial !important;
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

    /* Make all form control placeholders visible */
    .form-control::placeholder,
    .form-select::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
    }

    /* Specific style for textarea placeholder */
    textarea.form-control::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
    }

    /* Ensure email input placeholder is visible */
    input[type="email"]::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
    }

    /* Update password placeholder styles */
    input[type="password"]::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
        font-family: inherit !important;
    }
</style>

<section class="container mt-4">
    <!-- Title -->
    <h3 class="mb-3">Patient Management</h3>

    <!-- Search and Generate Report Section -->
    <div class="d-flex justify-content-between mb-4">
        <button class="btn btn-primary">Generate Report</button>
        <div class="input-group" style="width: 300px;">
            <input type="text" class="form-control" placeholder="Patient ID" aria-label="Patient ID">
            <button class="btn btn-warning" type="button">Search</button>
        </div>
    </div>

    <!-- Patient Form -->
    <div class="card p-4">
        <form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="Mobile Number" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="CCCD" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="date" class="form-control" placeholder="Date of Birth" required>
                </div>
                <div class="col-md-12 mb-3">
                    <textarea class="form-control" placeholder="Address" rows="3" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <select class="form-select">
                        <option value="" disabled selected>Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="password" class="form-control" placeholder="Confirm Password" required>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success me-2">Add</button>
                <button type="button" class="btn btn-primary me-2">Update</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>

    <!-- Recent Patients Table -->
    <div class="card mt-4 p-4">
        <h5>Recent Patients</h5>
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</small>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>CCCD</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Sam</td>
                    <td>Sapooht</td>
                    <td>61622626V</td>
                    <td>hsn@gmail.com</td>
                    <td>0774596005</td>
                    <td>2022-01-13</td>
                    <td>Male</td>
                    <td>Galle</td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>


                <tr>
                    <td>2</td>
                    <td>Sam</td>
                    <td>Sapooht</td>
                    <td>61622626V</td>
                    <td>hsn@gmail.com</td>
                    <td>0774596005</td>
                    <td>2022-01-13</td>
                    <td>Male</td>
                    <td>Galle</td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>



                <tr>
                    <td>3</td>
                    <td>Sam</td>
                    <td>Sapooht</td>
                    <td>61622626V</td>
                    <td>hsn@gmail.com</td>
                    <td>0774596005</td>
                    <td>2022-01-13</td>
                    <td>Male</td>
                    <td>Galle</td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>



                <tr>
                    <td>4</td>
                    <td>Sam</td>
                    <td>Sapooht</td>
                    <td>61622626V</td>
                    <td>hsn@gmail.com</td>
                    <td>0774596005</td>
                    <td>2022-01-13</td>
                    <td>Male</td>
                    <td>Galle</td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <!-- Repeat rows as needed -->
            </tbody>
        </table>
    </div>
</section>

<!-- Optional Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

@endsection
