@extends('admin_layout')

@section('admin_content')
<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments mr-2"></i> Feedback Management
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Feedback
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Review
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Highlighted
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $highlightedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Feedback Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock mr-1"></i> Pending Reviews
                    </h6>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-list mr-1"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    @if(count($pendingFeedback) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Patient</th>
                                        <th>Rating</th>
                                        <th>Date</th>
                                        <th width="160">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingFeedback as $feedback)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($feedback->subject, 30) }}</strong>
                                            @if($feedback->category)
                                            <br><small class="text-muted">{{ ucfirst($feedback->category) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $feedback->is_anonymous ? 'Anonymous' : $feedback->user->FullName }}
                                        </td>
                                        <td>
                                            <div class="text-warning">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $feedback->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal{{ $feedback->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal{{ $feedback->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <!-- Approve Modal -->
                                            <div class="modal fade" id="approveModal{{ $feedback->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('feedback.updateStatus', $feedback->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Approve Feedback</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to approve this feedback? It will be visible to the public.</p>
                                                                <div class="form-group">
                                                                    <label for="admin_notes">Admin Notes (Optional)</label>
                                                                    <textarea class="form-control" name="admin_notes" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success">Approve</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $feedback->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('feedback.updateStatus', $feedback->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reject Feedback</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to reject this feedback? It will not be visible to the public.</p>
                                                                <div class="form-group">
                                                                    <label for="admin_notes">Reason for Rejection (Optional)</label>
                                                                    <textarea class="form-control" name="admin_notes" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="admin-custom-pagination">
                            {{ $pendingFeedback->links('vendor.pagination.admin.bootstrap-4') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> No pending feedback at the moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Feedback Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell mr-1"></i> Latest Feedback
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    @if(count($latestFeedback) > 0)
                        @foreach($latestFeedback as $feedback)
                        <a href="{{ route('feedback.show', $feedback->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ Str::limit($feedback->subject, 25) }}</h6>
                                <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mb-1 text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $feedback->rating)
                                        <i class="fas fa-star fa-sm"></i>
                                    @else
                                        <i class="far fa-star fa-sm"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="mb-1">{{ Str::limit($feedback->message, 60) }}</p>
                            <small>
                                @if($feedback->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($feedback->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($feedback->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @endif

                                @if($feedback->is_highlighted)
                                    <span class="badge badge-info">Highlighted</span>
                                @endif
                            </small>
                        </a>
                        @endforeach
                    @else
                        <div class="list-group-item">
                            <p class="mb-0 text-center text-muted">No feedback available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection