@extends('layout')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="mb-3" data-aos="fade-up">Patients & Visitors Information</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Everything you need to know for your visit to Medic Hospital</p>
            <div class="hero-badges mt-4" data-aos="fade-up" data-aos-delay="200">
                <span class="badge-item"><i class="fas fa-info-circle"></i> Latest COVID-19 Guidelines</span>
                <span class="badge-item"><i class="fas fa-map-marker-alt"></i> Campus Maps</span>
                <span class="badge-item"><i class="fas fa-phone"></i> Contact Us</span>
            </div>
        </div>
    </div>

    <!-- Quick Links Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 info-card">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Appointments</h5>
                    <p class="card-text">Schedule, reschedule, or cancel your appointment</p>
                    <div class="link-list mt-4">
                        <a href="/appointments" class="link-item"><i class="fas fa-check-circle"></i> Schedule New</a>
                        <a href="/appointments" class="link-item"><i class="fas fa-edit"></i> Manage Existing</a>
                        <a href="/appointments" class="link-item"><i class="fas fa-calendar-check"></i> Visit History</a>
                    </div>
                    <a href="/appointments" class="btn btn-outline-primary mt-3">Manage Appointments</a>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 info-card">
                <div class="card-body">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Find a Doctor</h5>
                    <p class="card-text">Search for doctors by specialty or location</p>
                    <div class="link-list mt-4">
                        <a href="/staff" class="link-item"><i class="fas fa-search"></i> Find by Specialty</a>
                        <a href="/staff" class="link-item"><i class="fas fa-map-marker-alt"></i> Find by Location</a>
                        <a href="/staff" class="link-item"><i class="fas fa-star"></i> Top Rated Doctors</a>
                    </div>
                    <a href="/staff" class="btn btn-outline-primary mt-3">Search Doctors</a>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 info-card">
                <div class="card-body">
                    <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Locations & Directions</h5>
                    <p class="card-text">Find your way to our facilities</p>
                    <div class="link-list mt-4">
                        <a href="/locations" class="link-item"><i class="fas fa-hospital"></i> All Locations</a>
                        <a href="/locations" class="link-item"><i class="fas fa-parking"></i> Parking Information</a>
                        <a href="/locations" class="link-item"><i class="fas fa-bus"></i> Public Transport</a>
                    </div>
                    <a href="/locations" class="btn btn-outline-primary mt-3">Get Directions</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Sections -->
    <div class="row g-4 mb-5">
        <!-- Patient Resources -->
        <div class="col-lg-6" data-aos="fade-right">
            <div class="card shadow-lg h-100 hover-card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Patient Resources</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush resources-list">
                        <li class="list-group-item">
                            <i class="fas fa-file-medical text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Medical Records</a>
                            <p class="text-muted small mt-1 mb-0">Access, request or transfer your medical records</p>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Billing & Insurance</a>
                            <p class="text-muted small mt-1 mb-0">View bills, make payments, and insurance information</p>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-laptop-medical text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">MyChart Patient Portal</a>
                            <p class="text-muted small mt-1 mb-0">Secure online access to your health information</p>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-pills text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Pharmacy Services</a>
                            <p class="text-muted small mt-1 mb-0">Prescription refills, transfers and medication information</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Visitor Information -->
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card shadow-lg h-100 hover-card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Visitor Information</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush resources-list">
                        <li class="list-group-item">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Visiting Hours</a>
                            <p class="text-muted small mt-1 mb-0">Hours and guidelines for visiting patients</p>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-parking text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Parking Information</a>
                            <p class="text-muted small mt-1 mb-0">Parking locations, rates and accessibility information</p>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-hotel text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Hospital Amenities</a>
                            <p class="text-muted small mt-1 mb-0">ATMs, gift shops, wifi and other hospital amenities</p>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-utensils text-primary me-2"></i>
                            <a href="#" class="text-decoration-none">Dining Options</a>
                            <p class="text-muted small mt-1 mb-0">Cafeteria hours, menu information and nearby restaurants</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Services -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4" data-aos="fade-up">Featured Services</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h5>Preventative Care</h5>
                        <p>Regular check-ups and screenings to keep you healthy</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h5>Heart Health</h5>
                        <p>Comprehensive cardiac care from our specialists</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h5>Neurology</h5>
                        <p>Advanced neurological treatments and therapies</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-baby"></i>
                        </div>
                        <h5>Maternity</h5>
                        <p>Family-centered maternity care and services</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="row mt-5">
        <div class="col-12" data-aos="fade-up">
            <div class="card bg-light shadow-lg contact-card">
                <div class="card-body text-center p-5">
                    <h4 class="mb-3">Need Help?</h4>
                    <p class="mb-4">Our patient representatives are here to assist you</p>
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <a href="tel:037.864.9957" class="btn btn-primary btn-lg">
                            <i class="fas fa-phone me-2"></i>037.864.9957
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-comment-dots me-2"></i>Live Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Styles -->
<style>
.hero-badges {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
}

.badge-item {
    background: var(--primary-light);
    color: var(--primary-dark);
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.badge-item:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.badge-item i {
    margin-right: 8px;
}

.link-list {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.link-item {
    color: var(--text-color);
    padding: 8px 0;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--medium-gray);
}

.link-item:last-child {
    border-bottom: none;
}

.link-item i {
    color: var(--primary-color);
    margin-right: 10px;
    transition: all 0.3s ease;
}

.link-item:hover {
    color: var(--primary-color);
    transform: translateX(8px);
}

.link-item:hover i {
    transform: scale(1.2);
}

.resources-list .list-group-item {
    padding: 1.25rem;
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--medium-gray);
}

.resources-list .list-group-item:hover {
    background-color: var(--primary-light);
    transform: translateX(8px);
}

.resources-list .list-group-item i {
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.resources-list .list-group-item:hover i {
    transform: scale(1.2);
    color: var(--primary-dark);
}

.resources-list a {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--text-color);
    transition: all 0.3s ease;
}

.resources-list a:hover {
    color: var(--primary-color);
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-10px);
}

.feature-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: var(--gradient-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin: 0 auto 1.5rem;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(10deg);
    box-shadow: 0 10px 20px rgba(0,146,216,0.3);
}

.feature-card h5 {
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--primary-dark);
}

.contact-card {
    border-radius: var(--border-radius);
    background: linear-gradient(135deg, #f8f9fa 0%, var(--primary-light) 100%);
    overflow: hidden;
    position: relative;
}

.contact-card::before {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    top: -100px;
    right: -100px;
}

.contact-card::after {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    bottom: -75px;
    left: -75px;
}

.contact-card .card-body {
    position: relative;
    z-index: 1;
}
</style>
@endsection