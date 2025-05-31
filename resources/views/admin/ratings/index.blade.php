@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Ratings</h1>
        <div>
            <a href="{{ route('admin.ratings.dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
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

    <!-- Ratings Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Ratings</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ratings.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-3 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="doctor">Doctor</label>
                    <select class="form-control" id="doctor" name="doctor_id">
                        <option value="">All Doctors</option>
                        @foreach(\App\Models\Doctor::with('user')->get() as $doctor)
                            <option value="{{ $doctor->DoctorID }}" {{ request('doctor_id') == $doctor->DoctorID ? 'selected' : '' }}>
                                Dr. {{ $doctor->user->FullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="rating">Minimum Rating</label>
                    <select class="form-control" id="rating" name="min_rating">
                        <option value="">Any Rating</option>
                        <option value="5" {{ request('min_rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                        <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                        <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                        <option value="1" {{ request('min_rating') == '1' ? 'selected' : '' }}>1+ Star</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.ratings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ratings List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Ratings</h6>
        </div>
        <div class="card-body">
            @if($ratings->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Doctor Rating</th>
                            <th>Service Rating</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ratings as $rating)
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>
                                @if($rating->is_anonymous)
                                    <span class="badge badge-secondary">Anonymous</span>
                                @else
                                    {{ $rating->user->FullName ?? 'N/A' }}
                                @endif
                            </td>
                            <td>
                                @if($rating->doctor && $rating->doctor->user)
                                    <a href="{{ route('admin.doctor.ratings', $rating->doctor->DoctorID) }}">
                                        Dr. {{ $rating->doctor->user->FullName }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($rating->doctor_rating)
                                    <div class="d-flex align-items-center">
                                        <span class="mr-2">{{ $rating->doctor_rating }}</span>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->doctor_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($rating->service_rating)
                                    <div class="d-flex align-items-center">
                                        <span class="mr-2">{{ $rating->service_rating }}</span>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->service_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
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
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-feedback" data-toggle="modal" data-target="#viewFeedbackModal" data-feedback="{{ $rating->feedback }}" data-id="{{ $rating->id }}">
                                    <i class="far fa-eye"></i>
                                </button>

                                @if($rating->status == 'pending')
                                <form action="{{ route('admin.ratings.updateStatus', $rating->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.ratings.updateStatus', $rating->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.ratings.destroy', $rating->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-custom-pagination">
                {{ $ratings->links('vendor.pagination.admin.bootstrap-4') }}
            </div>
            @else
            <div class="text-center py-4">
                <p>No ratings found matching your criteria.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="viewFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="viewFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFeedbackModalLabel">Rating Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="feedbackContent">
                    <!-- Feedback content will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // View feedback modal
        $('.view-feedback').on('click', function() {
            const feedback = $(this).data('feedback');
            const ratingId = $(this).data('id');

            if (feedback) {
                $('#feedbackContent').html('<p>' + feedback + '</p>');
            } else {
                $('#feedbackContent').html('<p class="text-muted">No feedback provided for this rating.</p>');
            }

            $('#viewFeedbackModalLabel').text('Rating #' + ratingId + ' Feedback');
        });

        // Confirm delete
        $('.delete-form').on('submit', function(e) {
            if (!confirm('Are you sure you want to delete this rating? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection