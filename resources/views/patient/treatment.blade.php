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
    <!-- Title -->
    <h3 class="mb-3">Treatment Management</h3>

    <!-- Form Section -->
    <div class="card p-4">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <button class="btn btn-primary mb-3">Generate Report</button>
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Treatment Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Doctor Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Lab Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" placeholder="Number of nurses">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" placeholder="Total Ward charge">
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control">
                                <option selected disabled>Patient Name</option>
                                <option>Patient 1</option>
                                <option>Patient 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-success me-2">Add</button>
                        <button type="button" class="btn btn-primary me-2">Update</button>
                        <button type="button" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Treatment ID">
                    <button class="btn btn-warning">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Treatments Table -->
    <div class="card mt-4 p-4">
        <h5>Recent Treatment</h5>
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</small>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Treatment ID</th>
                    <th>Treatment Name</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Number of Nurses</th>
                    <th>Lab Name</th>
                    <th>Total Ward Charge</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Dengue</td>
                    <td>David</td>
                    <td>Sam</td>
                    <td>3</td>
                    <td>Blood</td>
                    <td>1500.00</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Dengue</td>
                    <td>David</td>
                    <td>Sam</td>
                    <td>3</td>
                    <td>Blood</td>
                    <td>1500.00</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Dengue</td>
                    <td>David</td>
                    <td>Sam</td>
                    <td>3</td>
                    <td>Blood</td>
                    <td>1500.00</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>



                <tr>
                    <td>4</td>
                    <td>Dengue</td>
                    <td>David</td>
                    <td>Sam</td>
                    <td>3</td>
                    <td>Blood</td>
                    <td>1500.00</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>


                <!-- Repeat table rows for more data -->
            </tbody>
        </table>
    </div>
</section>

@endsection
