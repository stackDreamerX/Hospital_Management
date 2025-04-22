@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ratings Dashboard</h1>
        <a href="{{ route('admin.ratings.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> View All Ratings
        </a>
    </div>

    <!-- Clinic Overall Ratings -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Overall Clinic Ratings</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="text-center">
                                <h5>Service Quality</h5>
                                <div class="h2 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($clinicRatings['service'] ?? 0, 1) }}
                                    <small>/5</small>
                                </div>
                                <div class="mt-2">
                                    @php 
                                        $serviceRating = round($clinicRatings['service'] ?? 0); 
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $serviceRating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="text-center">
                                <h5>Cleanliness</h5>
                                <div class="h2 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($clinicRatings['cleanliness'] ?? 0, 1) }}
                                    <small>/5</small>
                                </div>
                                <div class="mt-2">
                                    @php 
                                        $cleanlinessRating = round($clinicRatings['cleanliness'] ?? 0); 
                                    @endphp
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
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="text-center">
                                <h5>Staff Behavior</h5>
                                <div class="h2 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($clinicRatings['staff'] ?? 0, 1) }}
                                    <small>/5</small>
                                </div>
                                <div class="mt-2">
                                    @php 
                                        $staffRating = round($clinicRatings['staff'] ?? 0); 
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $staffRating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="text-center">
                                <h5>Wait Time</h5>
                                <div class="h2 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($clinicRatings['wait_time'] ?? 0, 1) }}
                                    <small>/5</small>
                                </div>
                                <div class="mt-2">
                                    @php 
                                        $waitTimeRating = round($clinicRatings['wait_time'] ?? 0); 
                                    @endphp
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
        </div>
    </div>

    <div class="row">
        <!-- Top Rated Doctors -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Rated Doctors</h6>
                </div>
                <div class="card-body">
                    @if(count($topDoctors) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Doctor</th>
                                    <th>Specialty</th>
                                    <th>Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDoctors as $doctor)
                                <tr>
                                    <td>{{ $doctor->user->FullName }}</td>
                                    <td>{{ $doctor->Speciality }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">{{ number_format($doctor->average_rating, 1) }}</span>
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($doctor->average_rating))
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.doctor.ratings', $doctor->DoctorID) }}" class="btn btn-sm btn-info">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p>No rated doctors yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Reviews & Overview -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ratings Summary</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">Pending Reviews</h6>
                                <div class="h4 mb-0">{{ $pendingCount }}</div>
                                @if($pendingCount > 0)
                                <a href="{{ route('admin.ratings.index') }}?status=pending" class="btn btn-sm btn-warning mt-2">
                                    Review Pending Ratings
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="my-4">
                        <h5 class="mb-3">Monthly Rating Trends</h5>
                        <canvas id="ratingTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Reviews</h6>
                </div>
                <div class="card-body">
                    @if(count($recentRatings) > 0)
                    <div class="list-group">
                        @foreach($recentRatings as $rating)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between mb-2">
                                <h5 class="mb-1">
                                    @if($rating->is_anonymous)
                                        Anonymous Patient
                                    @else
                                        {{ $rating->user->FullName }}
                                    @endif
                                    <small class="text-muted ml-2">rated Dr. {{ $rating->doctor->user->FullName }}</small>
                                </h5>
                                <small>{{ $rating->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mb-2">
                                <span class="mr-3">Doctor: 
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->doctor_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span>Service: 
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->service_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            @if($rating->feedback)
                            <p class="mb-1">{{ $rating->feedback }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p>No ratings yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Set up monthly trends chart
        const trendsData = {!! json_encode($monthlyTrends) !!};
        
        if (trendsData.length > 0) {
            const labels = trendsData.map(item => {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[item.month - 1] + ' ' + item.year;
            }).reverse();
            
            const doctorRatings = trendsData.map(item => item.avg_doctor_rating).reverse();
            const serviceRatings = trendsData.map(item => item.avg_service_rating).reverse();
            
            const ctx = document.getElementById('ratingTrendsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doctor Ratings',
                            data: doctorRatings,
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Service Ratings',
                            data: serviceRatings,
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 0,
                            max: 5,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + parseFloat(context.raw).toFixed(1) + '/5';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection 