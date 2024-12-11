@extends('admin_layout');
@section('admin_content')

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .status-online {
            color: green;
        }
        .status-offline {
            color: red;
        }
        .chart-placeholder {
            background: #f5f5f5;
            border: 1px dashed #ddd;
            height: 200px;
            position: relative;
            width: 100%;      
            height: 300px;     
            overflow: hidden;  
        }

        .chart-placeholder img {
            object-fit: cover; 
            width: 100%;
            height: 100%;
        }

        .rotate-text {
            transform: rotate(-90deg);
            white-space: nowrap; 
            transform-origin: left bottom;
        }

        input::placeholder,
    select::placeholder {
        color: #6c757d !important; 
        opacity: 1 !important;
    }

   
    select option:first-child {
        color: #6c757d;
    }

  
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: inherit !important;
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        transition: background-color 5000s ease-in-out 0s;
    }

   
    input[type="password"]:placeholder-shown {
        font-family: inherit !important;
    }

    
    input[type="password"]:not(:placeholder-shown) {
        font-family: password !important;
    }

    </style>

    <div class="container my-4">
        <!-- Row 1: Statistics -->
        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <i class="fas fa-user-injured fa-3x mb-2"></i>
                    <h5>Total Patients</h5>
                    <p>20</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <i class="fas fa-user-md fa-3x mb-2"></i>
                    <h5>Total Doctors</h5>
                    <p>20</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <i class="fas fa-hospital fa-3x mb-2"></i>
                    <h5>Total Wards</h5>
                    <p>20</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <i class="fas fa-flask fa-3x mb-2"></i>
                    <h5>Total Labs</h5>
                    <p>20</p>
                </div>
            </div>
        </div>

        <!-- Row 2: Patients Chart and Appointments -->
        <div class="row mb-4">
            <div class="col-md-6">
                    <div class="card p-3">
                        <h5>Patients Chart</h5>
                        <!-- Chart Placeholder -->
                        <div class="chart-placeholder d-flex justify-content-center align-items-center position-relative">
                            <img src="public/BackEnd/images/chart.png" alt="chart" class="w-100 h-100">
                            <!-- Axes Labels -->
                            <div class="position-absolute bottom-0 start-50 translate-middle-x">
                                <p class="mb-0">X</p>
                            </div>
                            <div class="position-absolute top-50 start-0 translate-middle-y">
                                <p class="mb-0 rotate-text">Y</p>
                            </div>
                        </div>
                        <!-- Chart Info -->
                        <div class="mt-3 d-flex justify-content-between">
                            <p>All Time: 41,234</p>
                            <p>30 Days: 41,234</p>
                            <p>7 Days: 41,234</p>
                        </div>
                    </div>

            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Appointments</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Chence Vaccarp</td>
                                <td>19/01/2023</td>
                                <td><span class="badge bg-primary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Destinee Kenter</td>
                                <td>04/12/2023</td>
                                <td><span class="badge bg-danger">Rejected</span></td>
                            </tr>
                            <tr>
                                <td>Paitlyn Lubin</td>
                                <td>19/01/2023</td>
                                <td><span class="badge bg-success">Accepted</span></td>
                            </tr>

                            <tr>
                                <td>Paitlyn Lubin</td>
                                <td>19/01/2023</td>
                                <td><span class="badge bg-success">Accepted</span></td>
                            </tr>

                            <tr>
                                <td>Paitlyn Lubin</td>
                                <td>19/01/2023</td>
                                <td><span class="badge bg-success">Accepted</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Row 3: Recent Doctors  -->
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <h5>Recent Doctors</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Consultancy Charge</th>
                                <th>Education</th>
                                <th>DOB</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Sam</td>
                                <td>+1234567890</td>
                                <td>123 Main Street, City</td>
                                <td>$500</td>
                                <td>MBBS</td>
                                <td>01/01/1980</td>
                                <td><span class="status-online">Online</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>John</td>
                                <td>+0987654321</td>
                                <td>456 Elm Street, City</td>
                                <td>$450</td>
                                <td>MD</td>
                                <td>02/02/1985</td>
                                <td><span class="status-offline">Offline</span></td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>John</td>
                                <td>+0987654321</td>
                                <td>456 Elm Street, City</td>
                                <td>$450</td>
                                <td>MD</td>
                                <td>02/02/1985</td>
                                <td><span class="status-offline">Offline</span></td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>John</td>
                                <td>+0987654321</td>
                                <td>456 Elm Street, City</td>
                                <td>$450</td>
                                <td>MD</td>
                                <td>02/02/1985</td>
                                <td><span class="status-offline">Offline</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Row 4: out of stocks  -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card p-3">
                    <h5>Out of Stock</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Drug Name</th>
                                <th>Expire Date</th>
                                <th>Manufacture Date</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Vitamin C</td>
                                <td>01/01/2024</td>
                                <td>01/01/2022</td>
                                <td>$10</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Paracetamol</td>
                                <td>01/01/2025</td>
                                <td>01/01/2023</td>
                                <td>$5</td>
                                <td>0</td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Paracetamol</td>
                                <td>01/01/2025</td>
                                <td>01/01/2023</td>
                                <td>$5</td>
                                <td>0</td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>Paracetamol</td>
                                <td>01/01/2025</td>
                                <td>01/01/2023</td>
                                <td>$5</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    


@endsection