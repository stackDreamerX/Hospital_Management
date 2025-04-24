@extends('patient_layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">    
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comment-alt me-2"></i>
                        Share Your Feedback
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('feedback.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-bold">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category">
                                    <option value="" selected>Select a category</option>
                                    <option value="doctor" {{ old('category') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="facility" {{ old('category') == 'facility' ? 'selected' : '' }}>Facility</option>
                                    <option value="staff" {{ old('category') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="treatment" {{ old('category') == 'treatment' ? 'selected' : '' }}>Treatment</option>
                                    <option value="overall" {{ old('category') == 'overall' ? 'selected' : '' }}>Overall Experience</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="department" class="form-label fw-bold">Department</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                    id="department" name="department" value="{{ old('department') }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3" id="doctorNameField" style="display: none;">
                            <label for="doctor_name" class="form-label fw-bold">Doctor's Name</label>
                            <input type="text" class="form-control @error('doctor_name') is-invalid @enderror" 
                                id="doctor_name" name="doctor_name" value="{{ old('doctor_name') }}">
                            @error('doctor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                            <div class="star-rating">
                                <div class="rating-group">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input class="rating__input" name="rating" id="rating-{{ $i }}" value="{{ $i }}" type="radio" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label class="rating__label" for="rating-{{ $i }}">
                                            <i class="rating__icon rating__icon--star fa fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label fw-bold">Your Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Please share your experience with our hospital services.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">
                                    Submit anonymously
                                </label>
                                <div class="form-text">
                                    Your name will not be displayed publicly if you check this option.
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users.dashboard') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-paper-plane me-1"></i> Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
.btn-gradient-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    color: white;
    transition: all 0.3s ease;
}
.btn-gradient-primary:hover {
    background: linear-gradient(135deg, #0a58ca, #084298);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
}
.rating-group {
    display: inline-flex;
    flex-direction: row-reverse;
}
.rating__input {
    position: absolute !important;
    left: -9999px !important;
}
.rating__label {
    cursor: pointer;
    padding: 0 0.1em;
    font-size: 2rem;
}
.rating__icon--star {
    color: #ddd;
}
/* When no star is checked, all remain gray */
.rating__input:not(:checked) ~ .rating__label .rating__icon--star {
    color: #ddd;
}
/* When a star is checked, it and its preceding stars become gold */
.rating__input:checked ~ .rating__label .rating__icon--star {
    color: #f8ce0b;
}
/* On hover, all stars reset to gray */
.rating-group:hover .rating__label .rating__icon--star {
    color: #ddd;
}
/* On hover, the hovered star and its preceding stars become gold */
.rating-group:hover .rating__label:hover .rating__icon--star,
.rating-group:hover .rating__label:hover ~ .rating__label .rating__icon--star {
    color: #f8ce0b;
}
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const doctorNameField = document.getElementById('doctorNameField');
        
        // Show/hide doctor name field based on category selection
        function toggleDoctorNameField() {
            if (categorySelect.value === 'doctor') {
                doctorNameField.style.display = 'block';
            } else {
                doctorNameField.style.display = 'none';
            }
        }
        
        // Initial check
        toggleDoctorNameField();
        
        // Add event listener
        categorySelect.addEventListener('change', toggleDoctorNameField);
    });
</script>
@endsection 