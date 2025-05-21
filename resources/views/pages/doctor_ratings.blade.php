@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Doctor Info Section -->
        <div class="col-md-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('search.doctors') }}">Doctors</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.public.profile', $doctor->DoctorID) }}">Dr. {{ $doctor->user->FullName }}</a></li>
                    <li class="breadcrumb-item active">Reviews</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->user->FullName }}" class="rounded-circle me-4" style="width: 100px; height: 100px; object-fit: cover;">
                        <div>
                            <h1 class="mb-1">{{ $doctor->Title ?? 'Dr.' }} {{ $doctor->user->FullName }}</h1>
                            <h4 class="text-muted mb-3">{{ $doctor->Speciality }}</h4>
                            <div class="d-flex align-items-center">
                                <div class="doctor-rating d-flex align-items-center me-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($avgRatings['doctor']))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $avgRatings['doctor'])
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <h3 class="mb-0 fw-bold">{{ number_format($avgRatings['doctor'], 1) }}</h3>
                                <span class="ms-2 text-muted">{{ $ratings->total() }} reviews</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('doctor.schedule', $doctor->DoctorID) }}" class="btn btn-primary">
                            <i class="far fa-calendar-check me-1"></i> Book Appointment
                        </a>
                        <a href="{{ route('doctor.public.profile', $doctor->DoctorID) }}" class="btn btn-outline-primary ms-2">
                            <i class="fas fa-user-md me-1"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ratings Overview -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i> Rating Overview</h4>
                </div>
                <div class="card-body">
                    <!-- Rating Distribution -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Rating Distribution</h5>

                        @for($i = 5; $i >= 1; $i--)
                            @php
                                $count = $distribution[$i] ?? 0;
                                $percentage = $ratings->count() > 0 ? ($count / $ratings->count()) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2" style="width: 60px">
                                    @for($j = 1; $j <= 5; $j++)
                                        @if($j <= $i)
                                            <i class="fas fa-star text-warning small"></i>
                                        @else
                                            <i class="far fa-star text-warning small"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="flex-grow-1">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="ms-2" style="width: 40px; text-align: right;">{{ $count }}</div>
                            </div>
                        @endfor
                    </div>

                    <!-- Other Rating Aspects -->
                    <div>
                        <h5 class="text-muted mb-3">Other Aspects</h5>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Service</span>
                                <span class="fw-bold">{{ number_format($avgRatings['service'], 1) }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ ($avgRatings['service'] / 5) * 100 }}%;"
                                    aria-valuenow="{{ ($avgRatings['service'] / 5) * 100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cleanliness</span>
                                <span class="fw-bold">{{ number_format($avgRatings['cleanliness'], 1) }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ ($avgRatings['cleanliness'] / 5) * 100 }}%;"
                                    aria-valuenow="{{ ($avgRatings['cleanliness'] / 5) * 100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Staff</span>
                                <span class="fw-bold">{{ number_format($avgRatings['staff'], 1) }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ ($avgRatings['staff'] / 5) * 100 }}%;"
                                    aria-valuenow="{{ ($avgRatings['staff'] / 5) * 100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Wait Time</span>
                                <span class="fw-bold">{{ number_format($avgRatings['wait_time'], 1) }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ ($avgRatings['wait_time'] / 5) * 100 }}%;"
                                    aria-valuenow="{{ ($avgRatings['wait_time'] / 5) * 100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Write a Review CTA -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="mb-3">Have you visited this doctor?</h5>
                    <p class="text-muted mb-4">Share your experience to help other patients</p>
                    @auth
                        <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                            <i class="fas fa-pen me-2"></i> Write a Review
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to Write a Review
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-comments text-primary me-2"></i> Patient Reviews</h4>
                </div>
                <div class="card-body">
                    @if($ratings->isEmpty())
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-comment-slash fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No reviews available yet</h5>
                            <p>Be the first to share your experience with Dr. {{ $doctor->user->FullName }}</p>
                        </div>
                    @else
                        @foreach($ratings as $review)
                            <div class="review-item mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-0">
                                            @if($review->is_anonymous)
                                                Anonymous Patient
                                            @else
                                                {{ $review->user->FullName }}
                                            @endif
                                        </h5>
                                        <span class="text-muted">{{ $review->created_at->format('F d, Y') }}</span>
                                    </div>
                                    <div class="doctor-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->doctor_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                <p class="mb-0">{{ $review->feedback }}</p>

                                @if($review->service_rating || $review->cleanliness_rating || $review->staff_rating || $review->wait_time_rating)
                                    <div class="other-ratings mt-3">
                                        <small class="text-muted d-block mb-2">Other ratings:</small>
                                        <div class="d-flex flex-wrap">
                                            @if($review->service_rating)
                                                <div class="me-3 mb-2">
                                                    <span class="badge bg-light text-dark">
                                                        Service: {{ $review->service_rating }}/5
                                                    </span>
                                                </div>
                                            @endif

                                            @if($review->cleanliness_rating)
                                                <div class="me-3 mb-2">
                                                    <span class="badge bg-light text-dark">
                                                        Cleanliness: {{ $review->cleanliness_rating }}/5
                                                    </span>
                                                </div>
                                            @endif

                                            @if($review->staff_rating)
                                                <div class="me-3 mb-2">
                                                    <span class="badge bg-light text-dark">
                                                        Staff: {{ $review->staff_rating }}/5
                                                    </span>
                                                </div>
                                            @endif

                                            @if($review->wait_time_rating)
                                                <div class="me-3 mb-2">
                                                    <span class="badge bg-light text-dark">
                                                        Wait Time: {{ $review->wait_time_rating }}/5
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $ratings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Write Review Modal -->
<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="writeReviewModalLabel">
                    <i class="fas fa-star me-2"></i> Rate Dr. {{ $doctor->user->FullName }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm" action="{{ route('doctor.review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->DoctorID }}">

                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">How would you rate your doctor?</label>
                        <div class="star-rating text-center">
                            <input type="radio" id="doctor5" name="doctor_rating" value="5" required />
                            <label for="doctor5" class="fas fa-star"></label>
                            <input type="radio" id="doctor4" name="doctor_rating" value="4" />
                            <label for="doctor4" class="fas fa-star"></label>
                            <input type="radio" id="doctor3" name="doctor_rating" value="3" />
                            <label for="doctor3" class="fas fa-star"></label>
                            <input type="radio" id="doctor2" name="doctor_rating" value="2" />
                            <label for="doctor2" class="fas fa-star"></label>
                            <input type="radio" id="doctor1" name="doctor_rating" value="1" />
                            <label for="doctor1" class="fas fa-star"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Service Rating (Optional)</label>
                        <div class="star-rating text-center">
                            <input type="radio" id="service5" name="service_rating" value="5" />
                            <label for="service5" class="fas fa-star"></label>
                            <input type="radio" id="service4" name="service_rating" value="4" />
                            <label for="service4" class="fas fa-star"></label>
                            <input type="radio" id="service3" name="service_rating" value="3" />
                            <label for="service3" class="fas fa-star"></label>
                            <input type="radio" id="service2" name="service_rating" value="2" />
                            <label for="service2" class="fas fa-star"></label>
                            <input type="radio" id="service1" name="service_rating" value="1" />
                            <label for="service1" class="fas fa-star"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Cleanliness Rating (Optional)</label>
                        <div class="star-rating text-center">
                            <input type="radio" id="clean5" name="cleanliness_rating" value="5" />
                            <label for="clean5" class="fas fa-star"></label>
                            <input type="radio" id="clean4" name="cleanliness_rating" value="4" />
                            <label for="clean4" class="fas fa-star"></label>
                            <input type="radio" id="clean3" name="cleanliness_rating" value="3" />
                            <label for="clean3" class="fas fa-star"></label>
                            <input type="radio" id="clean2" name="cleanliness_rating" value="2" />
                            <label for="clean2" class="fas fa-star"></label>
                            <input type="radio" id="clean1" name="cleanliness_rating" value="1" />
                            <label for="clean1" class="fas fa-star"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="feedback" class="form-label fw-bold">Your Review</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="4"
                            placeholder="Share your experience with this doctor..." required></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                        <label class="form-check-label" for="is_anonymous">
                            Post anonymously
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .doctor-rating .fas.fa-star,
    .doctor-rating .far.fa-star,
    .doctor-rating .fas.fa-star-half-alt {
        color: #ffc107;
        margin-right: 2px;
    }

    /* Rating stars styling */
    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
        font-size: 1.5em;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ddd;
        cursor: pointer;
        padding: 0 0.1em;
        transition: color 0.2s;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffc107;
    }

    .review-item:last-child {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
        border-bottom: none !important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Rating stars functionality
        if (document.querySelector('.star-rating')) {
            const stars = document.querySelectorAll('.star-rating input');
            stars.forEach(star => {
                star.addEventListener('change', function() {
                    const rating = this.value;
                    console.log(`Selected rating: ${rating}`);
                });
            });
        }

        // Form submission with AJAX
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                // Show loading indicator
                const submitBtn = reviewForm.querySelector('button[type="submit"]');
                const originalBtnHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Hide modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('writeReviewModal'));
                        modal.hide();

                        // Show success message
                        alert('Your review has been submitted and is pending approval.');

                        // Optional: Reload page to show the updated reviews
                        // window.location.reload();
                    } else {
                        alert('Error: ' + (result.message || 'Something went wrong. Please try again.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your review. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                });
            });
        }
    });
</script>
@endsection