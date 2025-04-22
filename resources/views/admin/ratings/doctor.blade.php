@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Doctor Rating Details</h1>
        <div>
            <a href="{{ route('admin.ratings.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Ratings
            </a>
            <a href="{{ route('admin.ratings.dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-chart-line fa-sm text-white-50"></i> Ratings Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

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
                            <p><strong>Email:</strong> {{ $doctor->user->Email }}</p>
                            <p><strong>Phone:</strong> {{ $doctor->user->PhoneNumber }}</p>
                            
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
                                        <span class="ml-2 text-muted">{{ $ratings->where('status', 'approved')->count() }} reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Rating Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rating Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="rating-bars">
                        @for($i = 5; $i >= 1; $i--)
                        <div class="rating-bar mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <div class="rating-label mr-3" style="width: 60px;">{{ $i }} stars</div>
                                <div class="progress flex-grow-1" style="height: 12px;">
                                    @php
                                        $count = $distribution['doctor'][$i] ?? 0;
                                        $total = $ratings->where('status', 'approved')->count();
                                        $percent = $total > 0 ? ($count / $total) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="ml-3" style="width: 40px;">{{ $count }}</div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-6 text-center">
                            <h6>Service</h6>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($avgRatings['service'] ?? 0, 1) }}
                            </div>
                            <div class="mt-1">
                                @php $serviceRating = round($avgRatings['service'] ?? 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $serviceRating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <h6>Cleanliness</h6>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($avgRatings['cleanliness'] ?? 0, 1) }}
                            </div>
                            <div class="mt-1">
                                @php $cleanlinessRating = round($avgRatings['cleanliness'] ?? 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $cleanlinessRating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-6 text-center">
                            <h6>Staff</h6>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($avgRatings['staff'] ?? 0, 1) }}
                            </div>
                            <div class="mt-1">
                                @php $staffRating = round($avgRatings['staff'] ?? 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $staffRating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <h6>Wait Time</h6>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($avgRatings['wait_time'] ?? 0, 1) }}
                            </div>
                            <div class="mt-1">
                                @php $waitTimeRating = round($avgRatings['wait_time'] ?? 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $waitTimeRating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Reviews -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Patient Reviews</h6>
                </div>
                <div class="card-body">
                    @if($ratings->where('status', 'approved')->count() > 0)
                    <div class="list-group">
                        @foreach($ratings->where('status', 'approved') as $rating)
                        <div class="list-group-item">
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
                            <p class="mb-0">{{ $rating->feedback }}</p>
                            @else
                            <p class="text-muted mb-0">No written feedback provided.</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p>No approved reviews for this doctor yet.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Pending Reviews -->
            @if($ratings->where('status', 'pending')->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Pending Reviews</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($ratings->where('status', 'pending') as $rating)
                        <div class="list-group-item">
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
                            <p class="mb-0">{{ $rating->feedback }}</p>
                            @else
                            <p class="text-muted mb-0">No written feedback provided.</p>
                            @endif
                            <div class="mt-2">
                                <form action="{{ route('admin.ratings.updateStatus', $rating->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.ratings.updateStatus', $rating->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 