@extends('layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-2" data-aos="fade-up">Locations and Directions</h1>
    <p class="text-center text-muted mb-5" data-aos="fade-up" data-aos-delay="100">Our hospital locations across the city designed for your convenience</p>

    <!-- Locations Section -->
    <div class="row g-4">
        <!-- Thong Nhat Location -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 location-card">
                <div class="location-badge">Main Center</div>
                <img src="{{ asset('public/images/ThongNhat-location.png') }}" class="card-img-top" alt="Thong Nhat Location">
                <div class="card-body">
                    <div class="card-content">
                        <h5 class="card-title">Medic Hospital Thong Nhat</h5>
                        <p class="card-text">1 Lý Thường Kiệt, Phường 7, Tân Bình, Hồ Chí Minh 700000, Việt Nam
                        </p>
                    </div>
                    <div class="location-info">
                        <div class="location-info-item">
                            <i class="fas fa-clock"></i> Open 24/7
                        </div>
                        <div class="location-info-item">
                            <i class="fas fa-phone"></i> 037.864.9957
                        </div>
                    </div>
                    <a href="https://maps.app.goo.gl/KQguonZejpjuxxhJA" target="_blank" class="btn btn-primary mt-3">
                        <i class="fas fa-map-marker-alt me-2"></i> Get Direction
                    </a>
                </div>
            </div>
        </div>

        <!-- Cho Ray Location -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 location-card">
                <div class="location-badge">Specialized Care</div>
                <img src="{{ asset('public/images/ChoRay-location.png') }}" class="card-img-top" alt="Cho Ray Location">
                <div class="card-body">
                    <div class="card-content">
                        <h5 class="card-title">Medic Hospital Cho Ray</h5>
                        <p class="card-text">201B Đ. Nguyễn Chí Thanh, Phường 12, Quận 5, Hồ Chí Minh 700000
                        </p>
                    </div>
                    <div class="location-info">
                        <div class="location-info-item">
                            <i class="fas fa-clock"></i> 8:00 AM - 8:00 PM
                        </div>
                        <div class="location-info-item">
                            <i class="fas fa-phone"></i> 037.864.9958
                        </div>
                    </div>
                    <a href="https://maps.app.goo.gl/CBHjiyWks5MUQC4JA" target="_blank" class="btn btn-primary mt-3">
                        <i class="fas fa-map-marker-alt me-2"></i> Get Direction
                    </a>
                </div>
            </div>
        </div>

        <!-- Da Khoa Thu Duc Location -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 location-card">
                <div class="location-badge">Community Center</div>
                <img src="{{ asset('public/images/DaKhoa-location.jpg') }}" class="card-img-top" alt="Da Khoa Thu Duc Location">
                <div class="card-body">
                    <div class="card-content">
                        <h5 class="card-title">Medic Hospital Da Khoa Thu Duc</h5>
                        <p class="card-text">64 Lê Văn Chí, Phường Linh Trung, Thủ Đức, Hồ Chí Minh, Việt Nam
                        </p>
                    </div>
                    <div class="location-info">
                        <div class="location-info-item">
                            <i class="fas fa-clock"></i> 7:00 AM - 9:00 PM
                        </div>
                        <div class="location-info-item">
                            <i class="fas fa-phone"></i> 037.864.9959
                        </div>
                    </div>
                    <a href="https://maps.app.goo.gl/8XgAZ6jFdpw3Ex696" target="_blank" class="btn btn-primary mt-3">
                        <i class="fas fa-map-marker-alt me-2"></i> Get Direction
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Location Info Section -->
    <div class="row mt-5 g-4">
        <div class="col-lg-6" data-aos="fade-right">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Transportation Options</h3>
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="transport-icon">
                                <i class="fas fa-bus"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Bus Lines</h5>
                                <p class="mb-0">Routes 8, 14, 23, and 45 stop near all hospital locations.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="transport-icon">
                                <i class="fas fa-taxi"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Taxi Services</h5>
                                <p class="mb-0">Taxi stands are available at all locations. Ride-sharing pickup points clearly marked.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="transport-icon">
                                <i class="fas fa-parking"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Parking</h5>
                                <p class="mb-0">Free parking available for patients and visitors. Valet service at main location.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Accessibility Information</h3>
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="transport-icon">
                                <i class="fas fa-wheelchair"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Wheelchair Access</h5>
                                <p class="mb-0">All locations feature ramps, elevators, and accessible restrooms.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="transport-icon">
                                <i class="fas fa-sign-language"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Language Services</h5>
                                <p class="mb-0">Interpreters available for multiple languages. Sign language services upon request.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="transport-icon">
                                <i class="fas fa-baby"></i>
                            </div>
                            <div class="ms-3">
                                <h5>Family Facilities</h5>
                                <p class="mb-0">Childcare services, nursing rooms, and family waiting areas available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.location-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--gradient-primary);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.location-info {
    display: flex;
    flex-wrap: wrap;
    margin: 1rem 0;
    gap: 12px;
}

.location-info-item {
    background-color: var(--primary-light);
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    color: var(--primary-dark);
}

.location-info-item i {
    margin-right: 5px;
}

.transport-icon {
    width: 50px;
    height: 50px;
    background-color: var(--primary-light);
    color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
</style>
@endsection
