@extends('layout')

@section('styles')
<style>
    .custom-pagination .pagination {
        justify-content: center;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        font-weight: normal;
        text-shadow: none;
        box-shadow: none;
    }
    .custom-pagination .page-link {
        color: var(--primary-color);
        padding: 0.5rem 0.75rem;
        min-width: 38px;
        text-align: center;
    }
    .custom-pagination .page-link:hover {
        color: var(--primary-dark);
        background-color: rgba(0, 146, 216, 0.1);
    }
    .doctor-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
    <div class="container my-5">
        <!-- Search Section -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4">Find a Doctor</h1>

                <!-- Search Form -->
                <form class="card shadow-sm p-4" action="{{ route('search.doctors') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Doctor Name</label>
                            <input type="text" name="doctor_name" class="form-control" placeholder="Enter doctor's name" value="{{ request('doctor_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Specialty</label>
                            <select class="form-select" name="specialty">
                                <option value="">Select specialty</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>{{ $specialty }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <select class="form-select" name="location">
                                <option value="">Select location</option>
                                <option value="Main Campus" {{ request('location') == 'Main Campus' ? 'selected' : '' }}>Main Campus</option>
                                <option value="Downtown Clinic" {{ request('location') == 'Downtown Clinic' ? 'selected' : '' }}>Downtown Clinic</option>
                                <option value="West Medical Center" {{ request('location') == 'West Medical Center' ? 'selected' : '' }}>West Medical Center</option>
                                <!-- Add more locations -->
                            </select>
                        </div>


                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary px-4 py-2">Search Doctors</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('staff') }}" class="btn btn-outline-secondary px-4 py-2 ms-2">Clear Search</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row g-4">
            @if(isset($search) && $search && $doctors->isEmpty())
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <h4>No doctors found matching your criteria</h4>
                        <p>Please try different search parameters.</p>
                    </div>
                </div>
            @elseif($doctors->isNotEmpty())
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 mb-0">Found {{ $doctors->total() }} doctors</h2>
                        <div>
                            <span class="text-muted">Showing {{ $doctors->firstItem() }} - {{ $doctors->lastItem() }} of {{ $doctors->total() }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Doctor Cards -->
            <div class="row mt-4">
                @foreach($doctors as $doctor)
                <!-- Doctor Card -->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <div class="card doctor-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->user->FullName }}" class="doctor-img me-3">
                                <div class="card-content">
                                    <h5 class="card-title mb-0">{{ $doctor->Title }} {{ $doctor->user->FullName }}</h5>
                                    <span class="doctor-specialty">{{ $doctor->Speciality }}</span>
                                </div>
                            </div>
                            <div class="doctor-rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($doctor->avgRating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $doctor->avgRating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="ms-1">{{ number_format($doctor->avgRating, 1) }} ({{ $doctor->ratingCount }} reviews)</span>
                            </div>
                            <p class="card-text">Experienced specialist in <br> {{ $doctor->Speciality }}</p>
                            <div class="doctor-details mt-3">
                                <div class="mb-2"><i class="fas fa-graduation-cap me-2"></i> Medical School Qualification</div>
                                <div class="mb-2"><i class="fas fa-hospital me-2"></i> Hospital Affiliation</div>
                                <div><i class="fas fa-language me-2"></i> Languages: English</div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('doctor.public.profile', $doctor->DoctorID) }}" class="btn btn-outline-primary me-2"><i class="fas fa-user-md me-1"></i> View Profile</a>
                                <a href="{{ route('users.appointments') }}" class="btn btn-primary"><i class="far fa-calendar-check me-1"></i> Book</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if($doctors->isNotEmpty())
        <!-- Pagination -->
        <nav class="mt-5" aria-label="Page navigation">
            <div class="custom-pagination">
                {{ $doctors->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </nav>
        @endif
    </div>
@endsection
