@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Ratings & Reviews</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ratings You've Submitted</h6>
        </div>
        <div class="card-body">
            @if($ratings->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Appointment Date</th>
                            <th>Doctor Rating</th>
                            <th>Service Rating</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ratings as $rating)
                        <tr>
                            <td>
                                @if($rating->doctor && $rating->doctor->user)
                                    Dr. {{ $rating->doctor->user->FullName }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($rating->appointment)
                                    {{ date('M d, Y', strtotime($rating->appointment->AppointmentDate)) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($rating->doctor_rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->doctor_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($rating->service_rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->service_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($rating->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($rating->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $rating->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $ratings->links() }}
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-star text-warning fa-3x mb-3"></i>
                <h5>You haven't submitted any ratings yet</h5>
                <p>After completing an appointment, you'll be able to rate your experience.</p>
                <a href="{{ route('patient.appointments.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-calendar-check mr-1"></i> View My Appointments
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
