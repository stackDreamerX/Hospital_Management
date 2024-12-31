@extends('layout')

@section('content')
    <div class="container my-5">
        <!-- Search Section -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4">Find a Doctor</h1>

                <!-- Search Form -->
                <form class="card shadow-sm p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Doctor Name</label>
                            <input type="text" class="form-control" placeholder="Enter doctor's name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Specialty</label>
                            <select class="form-select">
                                <option value="">Select specialty</option>
                                <option>Cardiology</option>
                                <option>Neurology</option>
                                <option>Orthopedics</option>
                                <option>Pediatrics</option>
                                <!-- Add more specialties -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <select class="form-select">
                                <option value="">Select location</option>
                                <option>Main Campus</option>
                                <option>Downtown Clinic</option>
                                <option>West Medical Center</option>
                                <!-- Add more locations -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Insurance</label>
                            <select class="form-select">
                                <option value="">Select insurance</option>
                                <option>Medicare</option>
                                <option>Blue Cross</option>
                                <option>Aetna</option>
                                <!-- Add more insurance options -->
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary px-4 py-2">Search Doctors</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row g-4">
            <!-- Sample Doctor Card -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="public/FrontEnd/images/doctor-placeholder.jpg" alt="Doctor"
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-1">Dr. John Smith</h5>
                                <p class="card-subtitle text-muted">Cardiology</p>
                            </div>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Main Campus<br>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>20 years experience<br>
                            <i class="fas fa-star text-warning me-1"></i>4.8 (120 reviews)
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-outline-primary">View Profile</button>
                            <button class="btn btn-primary">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Add more doctor cards here -->

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="public/FrontEnd/images/doctor-placeholder.jpg" alt="Doctor"
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-1">Dr. Jane Doe</h5>
                                <p class="card-subtitle text-muted">Neurology</p>
                            </div>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Main Campus<br>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>15 years experience<br>
                            <i class="fas fa-star text-warning me-1"></i>4.7 (90 reviews)
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-outline-primary">View Profile</button>
                            <button class="btn btn-primary">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="public/FrontEnd/images/doctor-placeholder.jpg" alt="Doctor"
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-1">Dr. Alice Green</h5>
                                <p class="card-subtitle text-muted">Cardiology</p>
                            </div>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Main Campus<br>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>12 years experience<br>
                            <i class="fas fa-star text-warning me-1"></i>4.9 (105 reviews)<br>
                            <strong>Insurance:</strong> Medicare
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-outline-primary">View Profile</button>
                            <button class="btn btn-primary">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="public/FrontEnd/images/doctor-placeholder.jpg" alt="Doctor"
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-1">Dr. Brian Carter</h5>
                                <p class="card-subtitle text-muted">Neurology</p>
                            </div>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Downtown Clinic<br>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>10 years experience<br>
                            <i class="fas fa-star text-warning me-1"></i>4.8 (85 reviews)<br>
                            <strong>Insurance:</strong> Blue Cross
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-outline-primary">View Profile</button>
                            <button class="btn btn-primary">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="public/FrontEnd/images/doctor-placeholder.jpg" alt="Doctor"
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-1">Dr. Susan Taylor</h5>
                                <p class="card-subtitle text-muted">Orthopedics</p>
                            </div>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>West Medical Center<br>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>8 years experience<br>
                            <i class="fas fa-star text-warning me-1"></i>4.7 (70 reviews)<br>
                            <strong>Insurance:</strong> Aetna
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-outline-primary">View Profile</button>
                            <button class="btn btn-primary">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="public/FrontEnd/images/doctor-placeholder.jpg" alt="Doctor"
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-1">Dr. Kevin Wilson</h5>
                                <p class="card-subtitle text-muted">Pediatrics</p>
                            </div>
                        </div>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Main Campus<br>
                            <i class="fas fa-graduation-cap text-primary me-2"></i>5 years experience<br>
                            <i class="fas fa-star text-warning me-1"></i>4.6 (60 reviews)<br>
                            <strong>Insurance:</strong> Blue Cross
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-outline-primary">View Profile</button>
                            <button class="btn btn-primary">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <!-- Pagination -->
        <nav class="mt-5" aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Additional Styles -->
    <style>
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
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

        .text-primary {
            color: #0078BF !important;
        }

        .page-link {
            color: #0078BF;
        }

        .page-item.active .page-link {
            background-color: #0078BF;
            border-color: #0078BF;
        }
    </style>
@endsection
