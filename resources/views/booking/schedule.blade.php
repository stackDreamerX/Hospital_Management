@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.public.profile', $doctor->DoctorID) }}">Doctor Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Book Appointment</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Doctor Info -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->user->FullName }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        <h4 class="mt-3 mb-0">{{ $doctor->Title }} {{ $doctor->user->FullName }}</h4>
                        <p class="text-muted">{{ $doctor->Speciality }}</p>

                        <div class="doctor-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($doctor->getAverageRatingAttribute() ?? 0))
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - 0.5 <= ($doctor->getAverageRatingAttribute() ?? 0))
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Appointment Fee</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Vietnamese Patients:</span>
                        <span class="fw-bold">{{ number_format($doctor->pricing_vn ?? 300000) }} VND</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Foreign Patients:</span>
                        <span class="fw-bold">{{ number_format($doctor->pricing_foreign ?? 600000) }} VND</span>
                    </div>

                    <hr>

                    <h5 class="mb-3">Payment Methods</h5>
                    <p><i class="fas fa-money-bill-wave text-success me-2"></i> Cash</p>
                    <p><i class="fas fa-credit-card text-primary me-2"></i> Bank Transfer</p>

                    <hr>

                    <div class="mb-3">
                        <a href="#" class="btn btn-link ps-0" data-bs-toggle="modal" data-bs-target="#pricingModal">
                            <i class="fas fa-info-circle me-1"></i> Price Details
                        </a>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('doctor.public.profile', $doctor->DoctorID) }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Calendar -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="far fa-calendar-check me-2 text-primary"></i> Select Appointment Date & Time</h4>
                </div>
                <div class="card-body">
                    <!-- Date Selection -->
                    <div class="date-selection mb-4">
                        <h5 class="mb-3">Select Date</h5>
                        <div class="d-flex flex-wrap date-picker">
                            @foreach($dates as $date)
                                <div class="date-option me-2 mb-2 {{ $loop->first ? 'active' : '' }}" data-date="{{ $date['date'] }}">
                                    <div class="date-info p-2">
                                        <div class="day-name">{{ $date['day_name_short'] }}</div>
                                        <div class="day-number">{{ $date['day'] }}</div>
                                        <div class="month">{{ date('M', strtotime($date['date'])) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Time Slots -->
                    <div class="time-selection">
                        <h5 class="mb-3">Select Time</h5>

                        @foreach($dates as $date)
                            <div class="time-slots-container" id="slots-{{ $date['date'] }}" {{ !$loop->first ? 'style=display:none' : '' }}>
                                @if(isset($timeSlots[$date['date']]) && count($timeSlots[$date['date']]) > 0)
                                    <div class="row">
                                        @foreach($timeSlots[$date['date']] as $slot)
                                            <div class="col-md-3 col-6 mb-3">
                                                <a
                                                    href="{{ $slot->status === 'available' ? route('doctor.booking', ['id' => $doctor->DoctorID, 'slot' => $slot->id]) : '#' }}"
                                                    class="time-slot {{ $slot->status !== 'available' ? 'disabled' : '' }}"
                                                >
                                                    {{ date('h:i A', strtotime($slot->time)) }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> No available time slots for this date.
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div id="no-slots" class="alert alert-warning {{ isset($timeSlots[$dates[0]['date']]) && count($timeSlots[$dates[0]['date']]) > 0 ? 'd-none' : '' }}">
                            <i class="fas fa-exclamation-triangle me-2"></i> No time slots available for the selected date. Please select another date.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Price Details Modal -->
<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pricingModalLabel">Appointment Fee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="fee-details">
                    <h5>Consultation Fee</h5>
                    <p class="mb-3">The following fees apply for a 30-minute consultation session:</p>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">For Vietnamese Patients</h6>
                            <h4 class="text-primary mb-0">{{ number_format($doctor->pricing_vn ?? 300000) }} VND</h4>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">For Foreign Patients</h6>
                            <h4 class="text-primary mb-0">{{ number_format($doctor->pricing_foreign ?? 600000) }} VND</h4>
                        </div>
                    </div>

                    <h5 class="mt-4">Payment Methods</h5>
                    <p>We accept the following payment methods:</p>
                    <ul>
                        <li><strong>Cash Payment:</strong> At the hospital reception</li>
                        <li><strong>Bank Transfer:</strong> Details will be provided after booking</li>
                    </ul>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Additional charges may apply for specialized procedures or tests recommended by the doctor.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .date-option {
        cursor: pointer;
        border: 1px solid #ddd;
        border-radius: 10px;
        text-align: center;
        min-width: 80px;
        transition: all 0.2s;
    }

    .date-option.active {
        background-color: #1a73e8;
        color: white;
        border-color: #1a73e8;
    }

    .date-option .day-name {
        font-weight: 500;
        font-size: 0.9rem;
    }

    .date-option .day-number {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .date-option .month {
        font-size: 0.8rem;
    }

    .time-slot {
        display: block;
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #333;
        transition: all 0.2s;
        text-decoration: none;
    }

    .time-slot:hover:not(.disabled) {
        background-color: #e8f0fe;
        border-color: #1a73e8;
        color: #1a73e8;
    }

    .time-slot.disabled {
        background-color: #f5f5f5;
        color: #999;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .doctor-rating .fas.fa-star,
    .doctor-rating .far.fa-star,
    .doctor-rating .fas.fa-star-half-alt {
        color: #ffc107;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Date selection
        $('.date-option').click(function() {
            const date = $(this).data('date');

            // Toggle active class
            $('.date-option').removeClass('active');
            $(this).addClass('active');

            // Show relevant time slots
            $('.time-slots-container').hide();
            $(`#slots-${date}`).show();

            // Check if there are slots for this date
            const hasSlots = $(`#slots-${date} .time-slot`).length > 0;

            if (hasSlots) {
                $('#no-slots').addClass('d-none');
            } else {
                $('#no-slots').removeClass('d-none');
            }
        });
    });
</script>
@endsection