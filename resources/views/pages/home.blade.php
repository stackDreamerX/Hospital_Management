@extends('layout')

@section('content')

<section class="hero theme-blue-pattern hero--hasDesktopImage">
    <div class="hero__image hero__image--shadeBottomDesktop">
        <div class="container">
            <div class="hero__text">
                <h1 class="hero__title fadeInUp">We're here when you need us — for every care in the world.</h1>
                <p class="lead text-white mb-4 fadeInUp" style="animation-delay: 0.2s;">Providing excellent healthcare with compassion and innovation.</p>
                <a href="{{ route('users.appointments') }}" class="btn btn-light btn-lg pulse">Schedule an Appointment</a>
            </div>
        </div>
    </div>
</section>


<div class="homepage-content">
    <section class="story-panel js-story-panel story-panel__white-background" id="aenterprise_home-page-cards">
        <div class="container py-5">
            <h2 class="text-center mb-5 fadeInUp">How Can We Help You Today?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="story-panel__card">
                        <div class="story-panel__card-content">
                            <a class="story-panel__image-link" href="{{ route('users.staff') }}">
                                <div class="story-panel__image-overlay">
                                    <img class="js-story-panel__image img-fluid" src="public/images/find-a-doctor-card-new.jpg" alt="Find a Doctor" style="height: 100%; object-fit: cover;">
                                </div>
                            </a>
                            <div class="p-4">
                                <a class="story-panel__title-link" href="{{ route('users.staff') }}">
                                    <h3 class="story-panel__title">Our Doctors</h3>
                                </a>
                                <p class="story-panel__description">Search by name, specialty, location and more.</p>
                                <a class="story-panel__button" href="{{ route('users.staff') }}">Find a doctor</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="story-panel__card">
                        <div class="story-panel__card-content">
                            <a class="story-panel__image-link" href="{{ route('users.locations') }}">
                                <div class="story-panel__image-overlay">
                                    <img class="js-story-panel__image img-fluid" src="public/images/ChoRay-location.png" alt="Locations" style="height: 100%; object-fit: cover;">
                                </div>
                            </a>
                            <div class="p-4">
                                <a class="story-panel__title-link" href="{{ route('users.locations') }}">
                                    <h3 class="story-panel__title">Locations &amp; <br> Directions</h3>
                                </a>
                                <p class="story-panel__description">Find any of our locations across the city.</p>
                                <a class="story-panel__button" href="{{ route('users.locations') }}">Get directions</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="story-panel__card">
                        <div class="story-panel__card-content">
                            <a class="story-panel__image-link" href="{{ route('users.appointments') }}">
                                <div class="story-panel__image-overlay">
                                    <img class="js-story-panel__image img-fluid" src="public/images/appointments-card.jpg" alt="Appointments" style="height: 100%; object-fit: cover;">
                                </div>
                            </a>
                            <div class="p-4">
                                <a class="story-panel__title-link" href="{{ route('users.appointments') }}">
                                    <h3 class="story-panel__title">Appointments</h3>
                                </a>
                                <p class="story-panel__description">Get the in person or virtual care you need.</p>
                                <a class="story-panel__button" href="{{ route('users.appointments') }}">Schedule now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section class="care-widget py-5" id="health-library">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="care-widget__header">
                        <div class="care-widget__header-wrapper d-flex align-items-center">
                            <div class="care-widget__logo me-3">
                                <svg version="1.1" viewBox="0 0 73 73" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px" width="50" height="50">
                                    <g>
                                        <path class="-path" fill="#fff" d="M55.9,22.3v12.5H67c3,0,5.3-2.4,5.3-5.3V5.9c0-3-2.4-5.3-5.3-5.3H43.4c-3,0-5.3,2.4-5.3,5.3v11.1h12.5 C53.6,16.9,55.9,19.3,55.9,22.3z M15.8,22.3v12.5H4.8c-3,0-5.3-2.4-5.3-5.3V5.9c0-3,2.4-5.3,5.3-5.3h23.6c3,0,5.3,2.4,5.3,5.3v11.1 H21.2C18.2,16.9,15.8,19.3,15.8,22.3z"></path>
                                        <path class="-path" fill="#fff" d="M15.8,51.7V39.2H4.8c-3,0-5.3,2.4-5.3,5.3v23.6c0,3,2.4,5.3,5.3,5.3h23.6c3,0,5.3-2.4,5.3-5.3V57H21.2 C18.2,57,15.8,54.7,15.8,51.7z M55.9,51.7V39.2H67c3,0,5.3,2.4,5.3,5.3v23.6c0,3-2.4,5.3-5.3,5.3H43.4c-3,0-5.3-2.4-5.3-5.3V57 h12.5C53.6,57,55.9,54.7,55.9,51.7z"></path>
                                    </g>
                                </svg>
                            </div>
                            <div class="care-widget__title">Health Library</div>
                        </div>
                    </div>
                    <div class="care-widget__content">
                        <ul class="care-widget__ul">
                            <li>
                                <a href="/health/diseases"><i class="fas fa-virus me-2"></i> Diseases &amp; Conditions</a>
                            </li>
                            <li>
                                <a href="/health/diagnostics"><i class="fas fa-microscope me-2"></i> Diagnostics &amp; Testing</a>
                            </li>
                            <li>
                                <a href="/health/treatments"><i class="fas fa-procedures me-2"></i> Treatments &amp; Procedures</a>
                            </li>
                            <li>
                                <a href="/health/body"><i class="fas fa-heart me-2"></i> Body Systems &amp; Organs</a>
                            </li>
                            <li>
                                <a href="/health/drugs"><i class="fas fa-pills me-2"></i> Drugs, Devices &amp; Supplements</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fadeInUp">Why Choose Medic Hospital?</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="public/images/patient-centered-care.svg" alt="patient centered care icon" class="mb-4" style="width: 80px;">
                            <h3 class="h4 mb-3">Patient-centered care</h3>
                            <p>We don't just care for your health conditions. We care about you. That means our providers take the time to listen to what's important to you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="public/images/national-recognition.svg" alt="national recognition icon" class="mb-4" style="width: 80px;">
                            <h3 class="h4 mb-3">National recognition</h3>
                            <p>Medic Hospital is recognized in the country and throughout the world for its expertise and exceptional quality of care.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="public/images/skilled-collaborative-providers.svg" alt="skilled and collaborative providers icon" class="mb-4" style="width: 80px;">
                            <h3 class="h4 mb-3">Collaborative providers</h3>
                            <p>You'll get care from board-certified and fellowship trained experts who work together to create a treatment plan just for you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="public/images/innovation-research.svg" alt="Innovation and Research Icon" class="mb-4" style="width: 80px;">
                            <h3 class="h4 mb-3">Innovation and research</h3>
                            <p>We're focused on today — and tomorrow. Our focus on research and offering the latest options means access to cutting-edge treatments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Medic Hospital Care Near You</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="location-card card h-100">
                        <img class="card-img-top" src="public/images/cleveland-clinic-main-2.jpg" alt="Medic Hospital Main">
                        <div class="card-body">
                            <h3 class="card-title h5">Medic Hospital Main</h3>
                            <a href="{{ route('users.locations') }}" class="btn btn-primary mt-3">View Location</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="location-card card h-100">
                        <img class="card-img-top" src="public/images/cleveland-clinic-florida-2.jpg" alt="Medic Hospital North">
                        <div class="card-body">
                            <h3 class="card-title h5">Medic Hospital North</h3>
                            <a href="{{ route('users.locations') }}" class="btn btn-primary mt-3">View Location</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="location-card card h-100">
                        <img class="card-img-top" src="public/images/cleveland-clinic-abu-dhabi-2.jpg" alt="Medic Hospital East">
                        <div class="card-body">
                            <h3 class="card-title h5">Medic Hospital East</h3>
                            <a href="{{ route('users.locations') }}" class="btn btn-primary mt-3">View Location</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('users.locations') }}" class="btn btn-outline-primary">View All Locations</a>
            </div>
        </div>
    </section>
</div>

@endsection