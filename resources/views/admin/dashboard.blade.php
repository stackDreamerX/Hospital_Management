@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('admin_content')

<div class="container my-4">
    <!-- Row 1: Statistics -->
    <div class="row text-center g-3 mb-4">
        @php 
            $cards = [
                ['icon' => 'fa-user-injured', 'title' => 'Total Patients', 'count' => 20],
                ['icon' => 'fa-user-md', 'title' => 'Total Doctors', 'count' => 20],
                ['icon' => 'fa-hospital', 'title' => 'Total Wards', 'count' => 20],
                ['icon' => 'fa-flask', 'title' => 'Total Labs', 'count' => 20],
            ];
        @endphp
        @foreach ($cards as $card)
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <i class="fas {{ $card['icon'] }} fa-3x mb-2 text-primary"></i>
                <h5>{{ $card['title'] }}</h5>
                <p class="fw-bold">{{ $card['count'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Row 2: Patients Chart and Appointments -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5>Patients Chart</h5>
                <img src="public/BackEnd/images/chart.png" alt="chart" class="img-fluid rounded">
                <div class="mt-3 d-flex justify-content-between">
                    <p>All Time: 41,234</p>
                    <p>30 Days: 41,234</p>
                    <p>7 Days: 41,234</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5>Appointments</h5>
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Row 3: Recent Doctors -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card p-3 shadow-sm">
                <h5>Recent Doctors</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
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
                            <td><span class="badge bg-success">Online</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>John</td>
                            <td>+0987654321</td>
                            <td>456 Elm Street, City</td>
                            <td>$450</td>
                            <td>MD</td>
                            <td>02/02/1985</td>
                            <td><span class="badge bg-secondary">Offline</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Row 4: Out of Stocks -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card p-3 shadow-sm">
                <h5>Out of Stock</h5>
                <table class="table table-hover table-bordered">
                    <thead class="table-danger">
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
