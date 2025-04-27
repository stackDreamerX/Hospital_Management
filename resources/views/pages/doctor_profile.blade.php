@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Doctor Information Section -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('public/images/doctor-placeholder.jpg') }}" alt="{{ $doctor->user->FullName }}" class="rounded-circle me-4" style="width: 150px; height: 150px; object-fit: cover;">
                        <div>
                            <h1 class="mb-1">{{ $doctor->Title }} {{ $doctor->user->FullName }}</h1>
                            <h4 class="text-muted mb-2">{{ $doctor->Speciality }}</h4>
                            
                            <div class="doctor-rating mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($doctor->avgRating))
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $doctor->avgRating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-1">{{ number_format($doctor->avgRating, 1) }} ({{ $doctor->ratingCount }} reviews)</span>
                            </div>
                            
                            <a href="{{ route('users.appointments') }}" class="btn btn-primary btn-lg"><i class="far fa-calendar-check me-1"></i> Book Appointment</a>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-phone-alt me-2 text-primary"></i> Contact Information</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Email:</strong> {{ $doctor->user->Email }}</li>
                                <li class="mb-2"><strong>Phone:</strong> {{ $doctor->user->PhoneNumber }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Practice Location</h5>
                            <p>{{ $doctor->WorkLocation ?? 'Information not available' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Education & Experience -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-md me-2 text-primary"></i> Professional Information</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Education</h5>
                        <ul>
                            <li>Medical degree from renowned university</li>
                            <li>Specialized training in {{ $doctor->Speciality }}</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Experience</h5>
                        <p>Over 10 years of experience in treating patients with various {{ $doctor->Speciality }} conditions. Specializes in both preventive care and advanced treatments.</p>
                    </div>
                    
                    <div>
                        <h5>Languages</h5>
                        <p>English, Vietnamese</p>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i> Patient Reviews</h4>
                </div>
                <div class="card-body">
                    @if($recentReviews->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">No reviews available for this doctor yet.</p>
                        </div>
                    @else
                        @foreach($recentReviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h5 class="mb-0">
                                            @if($review->is_anonymous)
                                                Anonymous Patient
                                            @else
                                                {{ $review->user->FullName }}
                                            @endif
                                        </h5>
                                        <small class="text-muted">{{ $review->created_at->format('F d, Y') }}</small>
                                    </div>
                                    <div class="doctor-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->doctor_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p>{{ $review->feedback }}</p>
                            </div>
                        @endforeach
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('doctor.public.ratings', $doctor->DoctorID) }}" class="btn btn-outline-primary">View All Reviews</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Available Hours -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="far fa-clock me-2 text-primary"></i> Available Hours</h4>
                </div>
                <div class="card-body">
                    @if($doctor->AvailableHours)
                        <div class="table-responsive">
                            {!! nl2br(e($doctor->AvailableHours)) !!}
                        </div>
                    @else
                        <div>
                            <p class="mb-2"><strong>Monday - Friday:</strong> 8:00 AM - 5:00 PM</p>
                            <p class="mb-2"><strong>Saturday:</strong> 9:00 AM - 12:00 PM</p>
                            <p><strong>Sunday:</strong> Closed</p>
                            <p class="text-muted mt-3"><small>* Default schedule shown. Actual availability may vary. Please book an appointment to confirm.</small></p>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('users.appointments') }}" class="btn btn-primary w-100">Book Now</a>
                    </div>
                </div>
            </div>
            
            <!-- Services Offered -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-stethoscope me-2 text-primary"></i> Services</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Consultations
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Diagnoses & Treatment Plans
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Follow-up Visits
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Specialized {{ $doctor->Speciality }} Care
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Preventive Health Guidance
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Insurance Accepted -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-shield-alt me-2 text-primary"></i> Insurance Accepted</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> Medicare
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> Blue Cross
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> Aetna
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> UnitedHealthcare
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .doctor-rating .fas.fa-star, 
    .doctor-rating .far.fa-star,
    .doctor-rating .fas.fa-star-half-alt {
        color: #ffc107;
    }
</style>
@endsection 