@extends('admin_layout')

@section('admin_content')
<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comment mr-2"></i> Feedback Details
        </h1>
        <div>
            <a href="{{ route('admin.feedback') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
            <a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Feedback Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-quote-left mr-1"></i> {{ $feedback->subject }}
                    </h6>
                    <div>
                        @if($feedback->status == 'pending')
                            <span class="badge badge-warning px-3 py-2">Pending Review</span>
                        @elseif($feedback->status == 'approved')
                            <span class="badge badge-success px-3 py-2">Approved</span>
                        @elseif($feedback->status == 'rejected')
                            <span class="badge badge-danger px-3 py-2">Rejected</span>
                        @endif

                        @if($feedback->is_highlighted)
                            <span class="badge badge-info px-3 py-2 ml-1">
                                <i class="fas fa-star mr-1"></i> Highlighted
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Feedback Content -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-warning mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $feedback->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="text-dark ml-2">{{ $feedback->rating }}/5</span>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-clock mr-1"></i> Submitted: {{ $feedback->created_at->format('M d, Y, h:i A') }}
                                </div>
                            </div>
                            @if($feedback->admin_reviewed_at)
                            <div class="text-muted small">
                                <i class="fas fa-clipboard-check mr-1"></i> Reviewed: {{ $feedback->admin_reviewed_at->format('M d, Y, h:i A') }}
                            </div>
                            @endif
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="card-text">{{ $feedback->message }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Status Actions -->
                    @if($feedback->status == 'pending')
                    <div class="d-flex mb-4">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#approveModal">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#highlightModal">
                            <i class="fas fa-star mr-1"></i> {{ $feedback->is_highlighted ? 'Remove Highlight' : 'Highlight' }}
                        </button>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    <div class="card border-left-info mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-info mb-2">
                                <i class="fas fa-sticky-note mr-1"></i> Admin Notes
                            </h6>
                            @if($feedback->admin_notes)
                                <p>{{ $feedback->admin_notes }}</p>
                            @else
                                <p class="text-muted">No admin notes have been added.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Feedback Attributes -->
                    <h6 class="font-weight-bold mb-3">Feedback Details</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="150" class="bg-light">Category</th>
                                    <td>{{ $feedback->category ? ucfirst($feedback->category) : 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Department</th>
                                    <td>{{ $feedback->department ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Doctor</th>
                                    <td>{{ $feedback->doctor_name ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Anonymous</th>
                                    <td>{{ $feedback->is_anonymous ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status</th>
                                    <td>
                                        @if($feedback->status == 'pending')
                                            <span class="badge badge-warning">Pending Review</span>
                                        @elseif($feedback->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($feedback->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card bg-light border-danger mb-2">
                        <div class="card-body">
                            <h6 class="text-danger font-weight-bold mb-2">Danger Zone</h6>
                            <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" id="deleteFeedbackForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" id="deleteFeedbackBtn">
                                    <i class="fas fa-trash mr-1"></i> Delete Feedback
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Patient Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user mr-1"></i> Patient Information
                    </h6>
                </div>
                <div class="card-body">
                    @if($feedback->is_anonymous)
                        <div class="text-center py-3">
                            <i class="fas fa-user-secret fa-3x text-muted mb-3"></i>
                            <h5>Anonymous Feedback</h5>
                            <p class="text-muted">The patient chose to submit this feedback anonymously.</p>
                        </div>
                    @else
                        <div class="text-center mb-3">
                            <img class="img-profile rounded-circle" src="{{ asset('avatar.jpg') }}" width="80">
                            <h5 class="mt-3">{{ $feedback->user->FullName }}</h5>
                            <p class="text-muted mb-2">Patient ID: {{ $feedback->user->UserID }}</p>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="text-muted small">Email</div>
                                <div>{{ $feedback->user->Email }}</div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="text-muted small">Phone</div>
                                <div>{{ $feedback->user->PhoneNumber }}</div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="text-muted small">Account Created</div>
                                <div>{{ $feedback->user->created_at ? $feedback->user->created_at->format('M d, Y') : 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary btn-block">
                                <i class="fas fa-address-card mr-1"></i> View Patient Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Other Feedback Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-1"></i> Patient's Other Feedback
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    @php
                        $otherFeedback = \App\Models\Feedback::where('user_id', $feedback->user_id)
                            ->where('id', '!=', $feedback->id)
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @if(count($otherFeedback) > 0)
                        @foreach($otherFeedback as $other)
                        <a href="{{ route('feedback.show', $other->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ Str::limit($other->subject, 25) }}</h6>
                                <small>{{ $other->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="mb-1 text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $other->rating)
                                        <i class="fas fa-star fa-sm"></i>
                                    @else
                                        <i class="far fa-star fa-sm"></i>
                                    @endif
                                @endfor
                            </div>
                            <small>
                                @if($other->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($other->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($other->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </small>
                        </a>
                        @endforeach
                    @else
                        <div class="list-group-item">
                            <p class="mb-0 text-center text-muted">No other feedback from this patient.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <textarea class="form-control" name="admin_notes" rows="3">{{ $feedback->admin_notes }}</textarea>
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
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <textarea class="form-control" name="admin_notes" rows="3">{{ $feedback->admin_notes }}</textarea>
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

<!-- Highlight Modal -->
<div class="modal fade" id="highlightModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="subject" value="{{ $feedback->subject }}">
                <input type="hidden" name="message" value="{{ $feedback->message }}">
                <input type="hidden" name="rating" value="{{ $feedback->rating }}">
                <input type="hidden" name="status" value="{{ $feedback->status }}">
                <input type="hidden" name="is_highlighted" value="{{ $feedback->is_highlighted ? '0' : '1' }}">

                <div class="modal-header">
                    <h5 class="modal-title">{{ $feedback->is_highlighted ? 'Remove Highlight' : 'Highlight Feedback' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($feedback->is_highlighted)
                        <p>Are you sure you want to remove this feedback from highlighted section?</p>
                    @else
                        <p>Are you sure you want to highlight this feedback? It will appear in the featured section of the public feedback page.</p>

                        @if($feedback->status != 'approved')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> This feedback is not currently approved. Highlighting it will automatically approve it as well.
                            <input type="hidden" name="status" value="approved">
                        </div>
                        @endif
                    @endif

                    <div class="form-group">
                        <label for="admin_notes">Admin Notes (Optional)</label>
                        <textarea class="form-control" name="admin_notes" rows="3">{{ $feedback->admin_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        {{ $feedback->is_highlighted ? 'Remove Highlight' : 'Highlight Feedback' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 for better notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Replace default confirm with SweetAlert for delete button
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteFeedbackForm');
        const deleteButton = document.getElementById('deleteFeedbackBtn');

        deleteButton.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this feedback. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete this feedback',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit the form
                    deleteForm.submit();
                }
            });
        });
    });
</script>
@endsection