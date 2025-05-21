@extends('layout')

@section('title', 'My Feedback')

@section('styles')
<style>
    .feedback-container {
        padding: 3rem 0;
        min-height: 75vh;
        background-color: #f8f9fa;
    }
    .feedback-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }
    .feedback-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection

@section('content')
<div class="container feedback-container py-4">
    <div class="row feedback-header">
        <div class="col-md-8">
            <h2 class="mb-2">
                <i class="fas fa-comments me-2"></i>My Feedback History
            </h2>
            <p class="text-muted">Review your previous feedback submissions and their status.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('feedback.create') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-plus-circle me-2"></i>Submit New Feedback
            </a>
        </div>
    </div>

    @if(count($feedbacks) > 0)
        <div class="row">
            @foreach($feedbacks as $feedback)
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm feedback-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">{{ $feedback->subject }}</h5>
                        <span class="fw-normal text-muted fs-6">{{ $feedback->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning me-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $feedback->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="fw-bold">{{ $feedback->rating }}/5</span>
                            </div>
                            <p class="card-text mb-4">{{ Str::limit($feedback->message, 150) }}</p>

                            @if($feedback->category)
                            <div class="mb-2">
                                <span class="text-muted">Category:</span>
                                <span class="fw-medium">{{ ucfirst($feedback->category) }}</span>
                            </div>
                            @endif

                            @if($feedback->department)
                            <div class="mb-2">
                                <span class="text-muted">Department:</span>
                                <span class="fw-medium">{{ $feedback->department }}</span>
                            </div>
                            @endif

                            @if($feedback->doctor_name)
                            <div class="mb-2">
                                <span class="text-muted">Doctor:</span>
                                <span class="fw-medium">{{ $feedback->doctor_name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div>
                            @if($feedback->status == 'pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i> Pending Review
                                </span>
                            @elseif($feedback->status == 'approved')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Approved
                                </span>
                            @elseif($feedback->status == 'rejected')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i> Rejected
                                </span>
                            @endif

                            @if($feedback->is_highlighted)
                                <span class="badge bg-info ms-1">
                                    <i class="fas fa-star me-1"></i> Highlighted
                                </span>
                            @endif
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary view-feedback"
                                data-bs-toggle="modal" data-bs-target="#viewFeedbackModal{{ $feedback->id }}">
                                <i class="fas fa-eye me-1"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Feedback Details -->
            <div class="modal fade" id="viewFeedbackModal{{ $feedback->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-comment-alt me-2"></i>Feedback Details
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <h4>{{ $feedback->subject }}</h4>
                                    <div class="text-warning mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $feedback->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 text-dark">{{ $feedback->rating }}/5</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar me-1"></i> {{ $feedback->created_at->format('M d, Y') }}
                                    </div>
                                    @if($feedback->admin_reviewed_at)
                                    <div class="text-muted">
                                        <i class="fas fa-clipboard-check me-1"></i> Reviewed: {{ $feedback->admin_reviewed_at->format('M d, Y') }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Your Message:</h6>
                                    <p>{{ $feedback->message }}</p>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">Feedback Information</h6>
                                            <ul class="list-group list-group-flush">
                                                @if($feedback->category)
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <span>Category:</span>
                                                    <span class="fw-medium">{{ ucfirst($feedback->category) }}</span>
                                                </li>
                                                @endif

                                                @if($feedback->department)
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <span>Department:</span>
                                                    <span class="fw-medium">{{ $feedback->department }}</span>
                                                </li>
                                                @endif

                                                @if($feedback->doctor_name)
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <span>Doctor:</span>
                                                    <span class="fw-medium">{{ $feedback->doctor_name }}</span>
                                                </li>
                                                @endif

                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <span>Status:</span>
                                                    @if($feedback->status == 'pending')
                                                        <span class="badge bg-warning text-dark px-3 py-2">Pending Review</span>
                                                    @elseif($feedback->status == 'approved')
                                                        <span class="badge bg-success px-3 py-2">Approved</span>
                                                    @elseif($feedback->status == 'rejected')
                                                        <span class="badge bg-danger px-3 py-2">Rejected</span>
                                                    @endif
                                                </li>

                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <span>Anonymous:</span>
                                                    <span>{{ $feedback->is_anonymous ? 'Yes' : 'No' }}</span>
                                                </li>

                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <span>Highlighted:</span>
                                                    <span>{{ $feedback->is_highlighted ? 'Yes' : 'No' }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                @if($feedback->admin_notes)
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">Admin Response</h6>
                                            <p>{{ $feedback->admin_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $feedbacks->links() }}
        </div>
    @else
        <div class="card text-center py-5 my-4">
            <div class="card-body">
                <i class="fas fa-comment-slash fa-4x mb-3 text-muted"></i>
                <h3 class="mb-3">No Feedback Submissions Yet</h3>
                <p class="mb-4">You haven't submitted any feedback yet. We'd love to hear about your experience!</p>
                <a href="{{ route('feedback.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-1"></i> Create Your First Feedback
                </a>
            </div>
        </div>
    @endif
</div>
@endsection