@extends('layout')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="mb-4">Patients & Visitors Information</h1>
            <p class="lead">Everything you need to know for your visit to Medic Hospital</p>
        </div>
    </div>

    <!-- Quick Links Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Appointments</h5>
                    <p class="card-text">Schedule, reschedule, or cancel your appointment</p>
                    <a href="/appointments" class="btn btn-outline-primary">Manage Appointments</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Find a Doctor</h5>
                    <p class="card-text">Search for doctors by specialty or location</p>
                    <a href="/staff" class="btn btn-outline-primary">Search Doctors</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Locations & Directions</h5>
                    <p class="card-text">Find your way to our facilities</p>
                    <a href="/locations" class="btn btn-outline-primary">Get Directions</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Sections -->
    <div class="row g-4">
        <!-- Patient Resources -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Patient Resources</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-file-medical text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Medical Records</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Billing & Insurance</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-laptop-medical text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">MyChart Patient Portal</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-pills text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Pharmacy Services</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Visitor Information -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Visitor Information</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Visiting Hours</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-parking text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Parking Information</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-hotel text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Hospital Amenities</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-utensils text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Dining Options</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h4>Need Help?</h4>
                    <p class="mb-3">Our patient representatives are here to assist you</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="tel:037.864.9957" class="btn btn-primary">
                            <i class="fas fa-phone me-2"></i>037.864.9957
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Styles -->
<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.bg-primary {
    background-color: #0078BF !important;
}

.text-primary {
    color: #0078BF !important;
}

.btn-primary {
    background-color: #0078BF;
    border-color: #0078BF;
}

.btn-primary:hover {
    background-color: #006ca8;
    border-color: #006ca8;
}

.btn-outline-primary {
    color: #0078BF;
    border-color: #0078BF;
}

.btn-outline-primary:hover {
    background-color: #0078BF;
    border-color: #0078BF;
}

.list-group-item a {
    color: #333;
}

.list-group-item a:hover {
    color: #0078BF;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>
@endsection