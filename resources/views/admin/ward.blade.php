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
    <!-- Ward Management Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Ward Management</h3>
        <button class="btn btn-primary">Generate Report</button>
    </div>

    <!-- Assign Ward Section -->
    <div class="card p-4">
        <h5 class="mb-4">Assign Ward</h5>
        <form>
            <div class="row">
                <!-- Ward ID and Search -->
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Ward ID">
                        <button class="btn btn-warning" type="button">Search</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Ward Name -->
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="Ward Name">
                </div>

                <!-- Number of Assign Doctors -->
                <div class="col-md-6 mb-3">
                    <input type="number" class="form-control" placeholder="Number of Assign Doctors">
                </div>
            </div>
            <div class="row">
                <!-- Number of Nurses -->
                <div class="col-md-6 mb-3">
                    <input type="number" class="form-control" placeholder="Number of Nurses">
                </div>

                <!-- Number of Patients -->
                <div class="col-md-6 mb-3">
                    <input type="number" class="form-control" placeholder="Number of Patients">
                </div>
            </div>
            <div class="row">
                <!-- Total Ward Charge -->
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white">RS:</span>
                        <input type="text" class="form-control" placeholder="Total Ward Charge">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-start">
                <!-- Action Buttons -->
                <button type="button" class="btn btn-success me-2">Add</button>
                <button type="button" class="btn btn-primary me-2">Update</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>

    <!-- Recent Wards -->
    <div class="card mt-4 p-4">
        <h5>Recent Wards</h5>
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</small>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Ward ID</th>
                    <th>Ward Name</th>
                    <th>Number of Nurses</th>
                    <th>Total Ward Charge</th>
                    <th>Number of Assign Doctors</th>
                    <th>Number of Patients</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Rows -->
                <tr>
                    <td>1</td>
                    <td>Base Line</td>
                    <td>15</td>
                    <td>15000.00</td>
                    <td>3</td>
                    <td>18</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Fronte Line</td>
                    <td>10</td>
                    <td>10000.00</td>
                    <td>2</td>
                    <td>10</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Fronte Line</td>
                    <td>10</td>
                    <td>10000.00</td>
                    <td>2</td>
                    <td>10</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>Fronte Line</td>
                    <td>10</td>
                    <td>10000.00</td>
                    <td>2</td>
                    <td>10</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

@endsection
