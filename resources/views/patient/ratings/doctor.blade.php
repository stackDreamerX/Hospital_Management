@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Doctor Ratings</h1>
        <a href="{{ route('patient.appointments.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-calendar fa-sm text-white-50"></i> Book Appointment
        </a>
    </div>

    <!-- Doctor Information -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Doctor Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="avatar mb-3">
                                <div style="width: 120px; height: 120px; background-color: #4e73df; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                    <span class="text-white font-weight-bold" style="font-size: 36px;">{{ substr($doctor->user->FullName, 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h4>Dr. {{ $doctor->user->FullName }}</h4>
                            <p><strong>Specialty:</strong> {{ $doctor->Speciality }}</p>
                            
                            <!-- Overall Rating -->
                            <div class="mt-3">
                                <h5>Overall Rating</h5>
                                <div class="d-flex align-items-center">
                                    <div class="h2 mb-0 font-weight-bold text-gray-800 mr-3">
                                        {{ number_format($avgRatings['doctor'] ?? 0, 1) }}
                                        <small>/5</small>
                                    </div>
                                    <div>
                                        @php 
                                            $doctorRating = round($avgRatings['doctor'] ?? 0); 
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $doctorRating)
                                                <i class="fas fa-star text-warning fa-lg"></i>
                                            @else
                                                <i class="far fa-star text-warning fa-lg"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-2 text-muted">{{ $ratings->count() }} reviews</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('patient.appointments.index') }}?doctor={{ $doctor->DoctorID }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus mr-1"></i> Book an Appointment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Rating Categories -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rating Categories</h6>
                </div>
                <div class="card-body">
                    <div class="rating-category mb-4">
                        <h6>Medical Knowledge & Expertise</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 mr-3" style="height: 10px;">
                                @php
                                    $doctorPercent = $avgRatings['doctor'] ? ($avgRatings['doctor'] / 5) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $doctorPercent }}%" aria-valuenow="{{ $doctorPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div style="width: 40px;">{{ number_format($avgRatings['doctor'] ?? 0, 1) }}</div>
                        </div>
                    </div>
                    
                    <div class="rating-category mb-4">
                        <h6>Service Quality</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 mr-3" style="height: 10px;">
                                @php
                                    $servicePercent = $avgRatings['service'] ? ($avgRatings['service'] / 5) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $servicePercent }}%" aria-valuenow="{{ $servicePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div style="width: 40px;">{{ number_format($avgRatings['service'] ?? 0, 1) }}</div>
                        </div>
                    </div>
                    
                    <div class="rating-category mb-4">
                        <h6>Cleanliness</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 mr-3" style="height: 10px;">
                                @php
                                    $cleanlinessPercent = $avgRatings['cleanliness'] ? ($avgRatings['cleanliness'] / 5) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cleanlinessPercent }}%" aria-valuenow="{{ $cleanlinessPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div style="width: 40px;">{{ number_format($avgRatings['cleanliness'] ?? 0, 1) }}</div>
                        </div>
                    </div>
                    
                    <div class="rating-category mb-4">
                        <h6>Staff Behavior</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 mr-3" style="height: 10px;">
                                @php
                                    $staffPercent = $avgRatings['staff'] ? ($avgRatings['staff'] / 5) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $staffPercent }}%" aria-valuenow="{{ $staffPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div style="width: 40px;">{{ number_format($avgRatings['staff'] ?? 0, 1) }}</div>
                        </div>
                    </div>
                    
                    <div class="rating-category">
                        <h6>Wait Time</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 mr-3" style="height: 10px;">
                                @php
                                    $waitTimePercent = $avgRatings['wait_time'] ? ($avgRatings['wait_time'] / 5) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $waitTimePercent }}%" aria-valuenow="{{ $waitTimePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div style="width: 40px;">{{ number_format($avgRatings['wait_time'] ?? 0, 1) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Reviews -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Patient Reviews</h6>
                </div>
                <div class="card-body">
                    @if($ratings->count() > 0)
                    <div class="list-group">
                        @foreach($ratings as $rating)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0">
                                        @if($rating->is_anonymous)
                                            Anonymous Patient
                                        @else
                                            {{ $rating->user->FullName }}
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $rating->created_at->format('F d, Y') }}</small>
                                </div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->doctor_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @if($rating->feedback)
                            <p class="mb-2">{{ $rating->feedback }}</p>
                            @endif
                            <div class="d-flex flex-wrap text-muted small">
                                @if($rating->service_rating)
                                <div class="mr-4">
                                    <span>Service:</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->service_rating)
                                            <i class="fas fa-star text-warning small"></i>
                                        @else
                                            <i class="far fa-star text-warning small"></i>
                                        @endif
                                    @endfor
                                </div>
                                @endif
                                
                                @if($rating->cleanliness_rating)
                                <div class="mr-4">
                                    <span>Cleanliness:</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->cleanliness_rating)
                                            <i class="fas fa-star text-warning small"></i>
                                        @else
                                            <i class="far fa-star text-warning small"></i>
                                        @endif
                                    @endfor
                                </div>
                                @endif
                                
                                @if($rating->staff_rating)
                                <div class="mr-4">
                                    <span>Staff:</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->staff_rating)
                                            <i class="fas fa-star text-warning small"></i>
                                        @else
                                            <i class="far fa-star text-warning small"></i>
                                        @endif
                                    @endfor
                                </div>
                                @endif
                                
                                @if($rating->wait_time_rating)
                                <div>
                                    <span>Wait Time:</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->wait_time_rating)
                                            <i class="fas fa-star text-warning small"></i>
                                        @else
                                            <i class="far fa-star text-warning small"></i>
                                        @endif
                                    @endfor
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $ratings->links() }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p>No reviews available for this doctor yet.</p>
                        <a href="{{ route('patient.appointments.index') }}?doctor={{ $doctor->DoctorID }}" class="btn btn-primary mt-2">
                            <i class="fas fa-calendar-plus mr-1"></i> Be the first to book with this doctor
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 