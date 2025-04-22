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
            <!-- Doctor Cards -->
            <div class="row mt-4">
                <!-- Doctor 1 -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="Dr. Sarah Johnson" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">Dr. Sarah Johnson</h5>
                                    <span class="doctor-specialty">Cardiology</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-1">4.7 (125 reviews)</span>
                            </div>
                            <p class="card-text">15+ years of experience specializing in cardiovascular health </p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> Harvard Medical School</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Thong Nhat Hospital</div>
                                <div><i class="fas fa-language me-2"></i> English, Vietnamese</div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="#" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor 2 -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="Dr. Michael Chen" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">Dr. Michael Chen</h5>
                                    <span class="doctor-specialty">Neurology</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ms-1">4.9 (98 reviews)</span>
                            </div>
                            <p class="card-text">Expert in neurological disorders with innovative treatment approaches.</p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> Johns Hopkins University</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Cho Ray Hospital</div>
                                <div><i class="fas fa-language me-2"></i> English, Vietnamese, Mandarin</div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="#" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor 3 -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="Dr. Emily Nguyen" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">Dr. Emily Nguyen</h5>
                                    <span class="doctor-specialty">Pediatrics</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ms-1">4.0 (72 reviews)</span>
                            </div>
                            <p class="card-text">Compassionate care for children with a focus on developmental health.</p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> Stanford University</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Da Khoa Thu Duc Hospital</div>
                                <div><i class="fas fa-language me-2"></i> English, Vietnamese</div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="#" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor 4 -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="Dr. James Wilson" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">Dr. James Wilson</h5>
                                    <span class="doctor-specialty">Orthopedics</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-1">4.6 (83 reviews)</span>
                            </div>
                            <p class="card-text">Specializing in sports medicine and minimally invasive surgical</p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> UCLA Medical School</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Thong Nhat Hospital</div>
                                <div><i class="fas fa-language me-2"></i> English</div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="#" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor 5 -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="Dr. Lily Tran" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">Dr. Lily Tran</h5>
                                    <span class="doctor-specialty">Dermatology</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ms-1">5.0 (105 reviews)</span>
                            </div>
                            <p class="card-text">Expert in cosmetic and medical dermatology with a holistic approach.</p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> Yale School of Medicine</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Cho Ray Hospital</div>
                                <div><i class="fas fa-language me-2"></i> English, Vietnamese</div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="#" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor 6 -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="Dr. Robert Pham" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">Dr. Robert Pham</h5>
                                    <span class="doctor-specialty">Internal Medicine</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ms-1">4.2 (67 reviews)</span>
                            </div>
                            <p class="card-text">Comprehensive care focused on chronic disease management</p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> University of California</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Da Khoa Thu Duc Hospital</div>
                                <div><i class="fas fa-language me-2"></i> English, Vietnamese</div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="#" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
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
@endsection
