@extends('layout')

@section('title', 'Feedback Submitted')

@section('styles')
<style>
    .thank-you-container {
        padding: 5rem 0;
        min-height: 70vh;
    }
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
    .success-icon {
        font-size: 5rem;
        color: #28a745;
        margin-bottom: 1.5rem;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container thank-you-container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle success-icon"></i>
                    </div>
                    <h2 class="mb-3">Thank You for Your Feedback!</h2>
                    <p class="text-muted mb-4">
                        We greatly appreciate you taking the time to share your thoughts with us.
                        Your feedback helps us improve our services and provide better healthcare experiences.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('users.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Return to Dashboard
                        </a>
                        <a href="{{ route('feedback.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Submit Another Feedback
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-light rounded">
                <div class="small text-muted">
                    <p class="mb-1">Your feedback has been submitted successfully and is currently under review.</p>
                    <p class="mb-0">If you have any urgent concerns, please contact our support team directly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection