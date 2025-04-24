@extends('layout')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <h1 class="display-4 fw-bold mb-3">Patient Feedback</h1>
            <p class="lead mb-4">See what our patients are saying about their experience at our hospital</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('feedback.create') }}" class="btn btn-primary btn-lg px-4 me-3">
                    <i class="fas fa-comment-medical me-2"></i>Share Your Experience
                </a>
            </div>
        </div>
    </div>
    
    <!-- Highlighted Feedback Section -->
    @if(count($highlightedFeedback) > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4 border-bottom pb-2">
                <i class="fas fa-star text-warning me-2"></i>Featured Feedback
            </h2>
        </div>
        
        @foreach($highlightedFeedback as $feedback)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0 highlighted-feedback">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">{{ $feedback->subject }}</h5>
                        <span class="badge bg-info">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                    </div>
                    
                    <div class="mb-3 text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $feedback->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    
                    <p class="card-text">{{ Str::limit($feedback->message, 150) }}</p>
                    
                    @if($feedback->category)
                    <div class="mb-1">
                        <span class="badge bg-secondary">{{ ucfirst($feedback->category) }}</span>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ $feedback->is_anonymous ? 'Anonymous' : $feedback->user->FullName }}
                        </small>
                    </div>
                    <small class="text-muted">{{ $feedback->created_at->format('M d, Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Regular Feedback Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4 border-bottom pb-2">
                <i class="fas fa-comments me-2"></i>Recent Feedback
            </h2>
        </div>
    </div>
    
    <div class="row">
        @if(count($regularFeedback) > 0)
            @foreach($regularFeedback as $feedback)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $feedback->subject }}</h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $feedback->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-muted">{{ $feedback->rating }}/5</span>
                        </div>
                        
                        <p class="card-text mb-3">{{ Str::limit($feedback->message, 120) }}</p>
                        
                        @if($feedback->category)
                        <div class="mb-2">
                            <span class="badge bg-light text-dark">{{ ucfirst($feedback->category) }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            {{ $feedback->is_anonymous ? 'Anonymous' : $feedback->user->FullName }}
                        </small>
                        <small class="text-muted">{{ $feedback->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $regularFeedback->links() }}
            </div>
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No feedback available at the moment.
                </div>
            </div>
        @endif
    </div>
    
    <!-- Call to Action -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-3">We Value Your Feedback</h3>
                    <p class="mb-4">Your opinion helps us improve our services and provide better care for all patients.</p>
                    <a href="{{ route('feedback.create') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-pen me-2"></i>Submit Your Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    background: linear-gradient(135deg, #0a58ca, #084298);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.card {
    border-radius: 10px;
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
.highlighted-feedback {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    border-top: 4px solid #0d6efd;
}
.pagination {
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
}
</style>
@endsection 