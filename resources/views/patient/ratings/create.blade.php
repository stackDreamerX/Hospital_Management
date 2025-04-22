@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rate Your Experience</h1>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Rate your appointment with Dr. {{ $appointment->doctor->user->FullName }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p><strong>Appointment Date:</strong> {{ date('F d, Y', strtotime($appointment->AppointmentDate)) }}</p>
                        <p><strong>Appointment Time:</strong> {{ date('h:i A', strtotime($appointment->AppointmentTime)) }}</p>
                        <p><strong>Status:</strong> <span class="badge badge-success">{{ $appointment->Status }}</span></p>
                    </div>

                    <form action="{{ route('patient.ratings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        <input type="hidden" name="doctor_id" value="{{ $appointment->doctor->DoctorID }}">

                        <div class="mb-4">
                            <label class="form-label font-weight-bold mb-2">How would you rate your doctor?</label>
                            <div class="rating-stars d-flex">
                                @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="radio" name="doctor_rating" id="doctor{{ $i }}" value="{{ $i }}">
                                    <label class="form-check-label" for="doctor{{ $i }}">
                                        @for ($j = 1; $j <= $i; $j++)
                                        <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($j = $i + 1; $j <= 5; $j++)
                                        <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold mb-2">How would you rate our service?</label>
                            <div class="rating-stars d-flex">
                                @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="radio" name="service_rating" id="service{{ $i }}" value="{{ $i }}">
                                    <label class="form-check-label" for="service{{ $i }}">
                                        @for ($j = 1; $j <= $i; $j++)
                                        <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($j = $i + 1; $j <= 5; $j++)
                                        <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold mb-2">How would you rate the cleanliness?</label>
                            <div class="rating-stars d-flex">
                                @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="radio" name="cleanliness_rating" id="cleanliness{{ $i }}" value="{{ $i }}">
                                    <label class="form-check-label" for="cleanliness{{ $i }}">
                                        @for ($j = 1; $j <= $i; $j++)
                                        <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($j = $i + 1; $j <= 5; $j++)
                                        <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold mb-2">How would you rate our staff?</label>
                            <div class="rating-stars d-flex">
                                @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="radio" name="staff_rating" id="staff{{ $i }}" value="{{ $i }}">
                                    <label class="form-check-label" for="staff{{ $i }}">
                                        @for ($j = 1; $j <= $i; $j++)
                                        <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($j = $i + 1; $j <= 5; $j++)
                                        <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold mb-2">How would you rate the wait time?</label>
                            <div class="rating-stars d-flex">
                                @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="radio" name="wait_time_rating" id="wait_time{{ $i }}" value="{{ $i }}">
                                    <label class="form-check-label" for="wait_time{{ $i }}">
                                        @for ($j = 1; $j <= $i; $j++)
                                        <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($j = $i + 1; $j <= 5; $j++)
                                        <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="feedback" class="form-label font-weight-bold">Additional Comments</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="4" placeholder="Share your experience..."></textarea>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_anonymous" name="is_anonymous">
                            <label class="form-check-label" for="is_anonymous">Submit this review anonymously</label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Rating</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Make star ratings clickable and highlight stars
        $('.rating-stars .form-check').on('click', function() {
            $(this).find('input[type="radio"]').prop('checked', true);
            
            // Highlight the selected rating
            const parent = $(this).parent();
            const allOptions = parent.find('.form-check');
            
            allOptions.each(function() {
                $(this).removeClass('selected');
            });
            
            $(this).addClass('selected');
        });
    });
</script>
@endsection 