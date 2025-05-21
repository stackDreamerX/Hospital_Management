@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Doctor Information Section -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->user->FullName ?? 'Doctor' }}" class="rounded-circle me-4" style="width: 150px; height: 150px; object-fit: cover;">
                        <div>
                            <h1 class="mb-1">{{ $doctor->Title }} {{ $doctor->user->FullName ?? 'Doctor' }}</h1>
                            <h4 class="text-muted mb-2">{{ $doctor->Speciality }}</h4>

                            <div class="doctor-rating mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($doctor->avgRating))
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $doctor->avgRating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-1">{{ number_format($doctor->avgRating, 1) }} ({{ $doctor->ratingCount }} reviews)</span>
                            </div>

                            <a href="{{ route('doctor.schedule', $doctor->DoctorID) }}" class="btn btn-primary btn-lg"><i class="far fa-calendar-check me-1"></i> Book Appointment</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-phone-alt me-2 text-primary"></i> Contact Information</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Email:</strong> {{ $doctor->user->Email ?? 'Not available' }}</li>
                                <li class="mb-2"><strong>Phone:</strong> {{ $doctor->user->PhoneNumber ?? 'Not available' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Practice Location</h5>
                            <p>{{ $doctor->WorkLocation ?? 'Information not available' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education & Experience -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-md me-2 text-primary"></i> Professional Information</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Education</h5>
                        <ul>
                            <li>Medical degree from renowned university</li>
                            <li>Specialized training in {{ $doctor->Speciality }}</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5>Experience</h5>
                        <p>Over 10 years of experience in treating patients with various {{ $doctor->Speciality }} conditions. Specializes in both preventive care and advanced treatments.</p>
                    </div>

                    <div>
                        <h5>Languages</h5>
                        <p>English, Vietnamese</p>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i> Patient Reviews</h4>
                </div>
                <div class="card-body">
                    @if($recentReviews->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">No reviews available for this doctor yet.</p>
                        </div>
                    @else
                        <div id="reviews-container">
                            @foreach($recentReviews as $review)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h5 class="mb-0">
                                                @if($review->is_anonymous)
                                                    Anonymous Patient
                                                @elseif($review->user)
                                                    {{ $review->user->FullName ?? 'Anonymous User' }}
                                                @else
                                                    Anonymous User
                                                @endif
                                            </h5>
                                            <small class="text-muted">{{ $review->created_at->format('F d, Y') }}</small>
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
                                    <p>{{ $review->feedback }}</p>
                                </div>
                            @endforeach
                        </div>

                        @if($doctor->ratingCount > count($recentReviews))
                            <div class="text-center mt-3" id="load-more-container">
                                <button id="load-more-reviews" class="btn btn-outline-primary" data-page="1" data-doctor="{{ $doctor->DoctorID }}">
                                    <i class="fas fa-sync me-1"></i> Load More Reviews
                                </button>
                                <div id="loading-spinner" class="spinner-border text-primary d-none" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="text-center mt-3">
                        @auth
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                                <i class="fas fa-pen me-1"></i> Write a Review
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-1"></i> Login to Write a Review
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Available Hours -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="far fa-clock me-2 text-primary"></i> Available Appointments</h4>
                </div>
                <div class="card-body">
                    <div class="appointment-booking">
                        <!-- Date Selection Dropdown -->
                        <div class="date-selection mb-4">
                            <h5 class="mb-3">Chọn ngày khám:</h5>
                            <div class="btn-group w-100">
                                <button class="btn btn-primary dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="dateDropdownButton">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    <span id="selected-date-text">Chọn ngày</span>
                                </button>
                                <ul class="dropdown-menu w-100" id="date-dropdown-menu">
                                    @foreach($dates as $date)
                                        @php
                                            // Format the date as "Thứ X - DD/MM"
                                            $dayNames = [
                                                'Monday' => 'Thứ 2',
                                                'Tuesday' => 'Thứ 3',
                                                'Wednesday' => 'Thứ 4',
                                                'Thursday' => 'Thứ 5',
                                                'Friday' => 'Thứ 6',
                                                'Saturday' => 'Thứ 7',
                                                'Sunday' => 'Chủ nhật'
                                            ];
                                            $dayName = $dayNames[$date['day_name']] ?? $date['day_name'];
                                            $formattedDate = $dayName . ' - ' . $date['day'] . '/' . $date['month'];
                                        @endphp
                                        <li>
                                            <a class="dropdown-item date-option" href="#"
                                               data-date="{{ $date['date'] }}"
                                               data-formatted="{{ $formattedDate }}">
                                                <i class="far fa-calendar-day me-2"></i> {{ $formattedDate }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="time-selection mb-4">
                            <h5 class="mb-3">
                                <i class="far fa-clock me-2"></i> LỊCH KHÁM
                            </h5>

                            @if(!isset($hasSchedule) || $hasSchedule === false)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Bác sĩ chưa thiết lập lịch khám. Vui lòng liên hệ trực tiếp với bệnh viện để đặt lịch.
                                </div>
                            @else


                                @foreach($dates as $date)
                                    <div class="time-slots-container" id="slots-{{ $date['date'] }}" style="display: none;">
                                        <div class="time-slot-date-header mb-2">
                                            <small class="text-muted">Slots for: {{ $date['date'] }} ({{ $date['day_name'] }})</small>
                                        </div>

                                        @if(isset($timeSlots[$date['date']]) && count($timeSlots[$date['date']]) > 0)
                                            <div class="time-slots-wrapper">
                                            <div class="row g-3 time-slots-grid">
                                                @foreach($timeSlots[$date['date']] as $slot)
                                                    @php
                                                        // Check if slot is virtual (has string ID)
                                                        $isVirtual = is_string($slot->id) && (strpos($slot->id, 'schedule_') === 0 || strpos($slot->id, 'slot_') === 0);

                                                        // Get the slot ID
                                                        $slotId = $slot->id;

                                                        // Extra validation to ensure slot ID is not 0
                                                        if (empty($slotId) || $slotId === 0 || $slotId === '0') {
                                                            \Illuminate\Support\Facades\Log::warning("Invalid slot ID detected", [
                                                                'slot' => $slot,
                                                                'date' => $date['date']
                                                            ]);
                                                            // Force use of a valid ID format based on date and time
                                                            $startTime = \Carbon\Carbon::parse($slot->time);
                                                            $slotId = 'slot_' . str_replace('-', '', $date['date']) . $startTime->format('His');
                                                        }

                                                        // Generate booking route
                                                        $bookingRoute = route('doctor.booking', [
                                                            'id' => $doctor->DoctorID,
                                                            'slot' => $slotId
                                                        ]);

                                                        // Debug log to check what's being passed
                                                        \Illuminate\Support\Facades\Log::info("Creating booking route in doctor_profile view", [
                                                            'doctor_id' => $doctor->DoctorID,
                                                            'slot_id' => $slotId,
                                                            'is_virtual' => $isVirtual,
                                                            'route' => $bookingRoute
                                                        ]);

                                                        // Calculate end time (30 min after start)
                                                        $startTime = \Carbon\Carbon::parse($slot->time);
                                                        $endTime = $startTime->copy()->addMinutes(30);
                                                    @endphp
                                                    <div class="col-md-3 col-6">
                                                        <a href="{{ $slot->status === 'available' ? $bookingRoute : '#' }}"
                                                           class="time-slot-box {{ $slot->status !== 'available' ? 'disabled' : '' }}"
                                                           data-slot-id="{{ $slotId }}"
                                                           onclick="if(!this.classList.contains('disabled')) { showDebug('Clicked on slot with ID: {{ $slotId }}'); window.location.href='{{ $bookingRoute }}'; return false; }">
                                                            {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                                </div>
                                            </div>

                                            <div class="text-center mt-4">
                                                <p class="text-muted">Chọn <i class="fas fa-hand-pointer"></i> và đặt (Phí đặt lịch 0đ)</p>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i> Không có lịch khám cho ngày này.
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                <div id="no-slots" class="alert alert-warning d-none">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Hiện tại không có lịch khám nào. Vui lòng liên hệ trực tiếp với bệnh viện để đặt lịch.
                                </div>
                            @endif
                        </div>

                        <!-- Clinic information -->
                        <div class="clinic-info mb-4">
                            <h6 class="text-muted mb-3">ĐỊA CHỈ KHÁM</h6>
                            <h5 class="text-info mb-2">Phòng Khám Đa Khoa MSC Clinic</h5>
                            <p class="mb-3">{{ $doctor->WorkLocation ?? 'TT 20-21-22 Số 204 Nguyễn Tuân, phường Nhân Chính, quận Thanh Xuân, TP Hà Nội' }}</p>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">GIÁ KHÁM:</span>
                                <span class="fw-bold">{{ number_format($doctor->pricing_vn ?? 500000) }}đ <a href="#" class="text-info small">Xem chi tiết</a></span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">LOẠI BẢO HIỂM ÁP DỤNG.</span>
                                <a href="#" class="text-info small">Xem chi tiết</a>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('doctor.schedule', $doctor->DoctorID) }}" class="btn btn-primary w-100">
                                <i class="far fa-calendar-check me-1"></i> View Full Schedule
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Offered -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-stethoscope me-2 text-primary"></i> Services</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Consultations
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Diagnoses & Treatment Plans
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Follow-up Visits
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Specialized {{ $doctor->Speciality }} Care
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i> Preventive Health Guidance
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Insurance Accepted -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-shield-alt me-2 text-primary"></i> Insurance Accepted</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> Medicare
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> Blue Cross
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> Aetna
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check text-success me-2"></i> UnitedHealthcare
                        </li>
                    </ul>
                </div>
            </div>
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
    }

    /* Time slots grid styles - Enhanced */
    .time-slots-grid {
        margin-top: 15px;
    }

    /* New container style for better organization */
    .time-slots-container {
        padding: 15px;
        background-color: #f9fbff;
        border-radius: 10px;
        border: 1px solid #e6eeff;
        margin-bottom: 15px;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* New time slots wrapper */
    .time-slots-wrapper {
        padding: 5px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.03);
    }

    /* Enhanced time slot box */
    .time-slot-box {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 14px 6px;
        background-color: #f0f7ff;
        color: #0d6efd;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s;
        height: 100%;
        border: 2px solid #d0e3ff;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 3px 6px rgba(13,110,253,0.08);
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .time-slot-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: #0d6efd;
        opacity: 0.6;
    }

    .time-slot-box:hover {
        background-color: #0d6efd;
        color: white;
        transform: translateY(-3px);
        border-color: #0b5ed7;
        box-shadow: 0 6px 12px rgba(13,110,253,0.25);
    }

    .time-slot-box.disabled {
        background-color: #f8f9fa;
        color: #adb5bd;
        cursor: not-allowed;
        opacity: 0.7;
        border-color: #e9ecef;
    }

    .time-slot-box.disabled::before {
        background-color: #adb5bd;
        opacity: 0.3;
    }

    .time-slot-box.disabled:hover {
        transform: none;
        box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    /* Selected state */
    .time-slot-box.selected {
        background-color: #0d6efd;
        color: white;
        border-color: #0b5ed7;
        box-shadow: 0 5px 10px rgba(13,110,253,0.3);
    }

    .time-slot-box.selected::before {
        background-color: #ffffff;
    }

    /* Date selection styles */
    .date-selection .btn-group {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }

    .dropdown-menu {
        border-color: #e9ecef;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        max-height: 300px;
        overflow-y: auto;
    }

    .dropdown-item {
        padding: 10px 15px;
        border-radius: 4px;
        margin: 2px 5px;
    }

    .dropdown-item.active,
    .dropdown-item:active,
    .dropdown-item:hover {
        background-color: #e9f0ff;
        color: #0d6efd;
    }

    /* Clinic info styles */
    .clinic-info {
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    /* Time slot date header */
    .time-slot-date-header {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #0d6efd;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    /* Loading indicator styles */
    .spinner-border {
        width: 2rem;
        height: 2rem;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Rating form styles */
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
</style>
@endsection

@section('scripts')
<script>
    // Hiển thị console log ngay khi file được load
    console.log("Doctor profile script loading...");

    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM loaded - Initializing doctor profile script");

        // Đảm bảo dropdown hoạt động đúng với Bootstrap 5
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Phần chọn ngày
        const dateOptions = document.querySelectorAll('.date-option');
        const selectedDateText = document.getElementById('selected-date-text');
        const dateDropdownButton = document.getElementById('dateDropdownButton');
        console.log("Found date options:", dateOptions.length);

        // Debug - hiển thị các ngày có sẵn
        dateOptions.forEach((option, index) => {
            console.log(`Option ${index}: ${option.getAttribute('data-date')} - ${option.getAttribute('data-formatted')}`);
        });

        // Handle time slot selection
        function setupTimeSlotSelection() {
            const availableTimeSlots = document.querySelectorAll('.time-slot-box:not(.disabled)');

            availableTimeSlots.forEach(slot => {
                slot.addEventListener('click', function(e) {
                    // Only for available slots
                    if (!this.classList.contains('disabled')) {
                        // Remove selected class from all slots
                        availableTimeSlots.forEach(s => s.classList.remove('selected'));

                        // Add selected class to this slot
                        this.classList.add('selected');

                        // We don't prevent default here to allow navigation to booking page
                    }
                });
            });
        }

        // Hàm hiển thị time slots cho ngày đã chọn
        function showTimeSlots(dateString) {
            console.log("Showing time slots for date:", dateString);

            // Hiển thị loading spinner
            const timeSelectionContainer = document.querySelector('.time-selection');
            if (timeSelectionContainer) {
                const loadingSpinner = document.createElement('div');
                loadingSpinner.id = 'loading-spinner';
                loadingSpinner.className = 'text-center my-4';
                loadingSpinner.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><div class="mt-2">Đang tải lịch khám...</div>';

                // Xóa spinner cũ nếu có
                const oldSpinner = document.getElementById('loading-spinner');
                if (oldSpinner) oldSpinner.remove();

                // Thêm spinner mới
                timeSelectionContainer.appendChild(loadingSpinner);
            }

            try {
                // Ẩn tất cả các container time slots
                document.querySelectorAll('.time-slots-container').forEach(container => {
                    container.style.display = 'none';
                });

                // Hiển thị container cho ngày đã chọn
                const selectedContainer = document.getElementById('slots-' + dateString);
                console.log("Looking for container with ID:", 'slots-' + dateString);

                // Xóa loading spinner
                const spinner = document.getElementById('loading-spinner');
                if (spinner) spinner.remove();

                if (selectedContainer) {
                    console.log("Found container, showing time slots");
                    selectedContainer.style.display = 'block';

                    // Hiện thông báo nếu không có time slots
                    const hasSlots = selectedContainer.querySelectorAll('.time-slot-box').length > 0;
                    const noSlotsMessage = document.getElementById('no-slots');
                    if (noSlotsMessage) {
                        noSlotsMessage.classList.toggle('d-none', hasSlots);
                    }

                    // Setup event listeners for time slot selection
                    setupTimeSlotSelection();
                } else {
                    console.error("Container for date not found:", dateString);
                    // Hiển thị các container có sẵn để debug
                    const containers = document.querySelectorAll('.time-slots-container');
                    console.log("Available containers:", containers.length);
                    containers.forEach(c => console.log(" - " + c.id));

                    // Hiển thị thông báo lỗi
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'alert alert-danger';
                    errorMessage.innerHTML = `<i class="fas fa-exclamation-circle"></i> Không tìm thấy lịch khám cho ngày ${dateString}`;
                    timeSelectionContainer.appendChild(errorMessage);
                }
            } catch (error) {
                console.error("Error showing time slots:", error);

                // Xóa loading spinner
                const spinner = document.getElementById('loading-spinner');
                if (spinner) spinner.remove();

                // Hiển thị thông báo lỗi
                const timeSelectionContainer = document.querySelector('.time-selection');
                if (timeSelectionContainer) {
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'alert alert-danger';
                    errorMessage.innerHTML = `<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra: ${error.message}`;
                    timeSelectionContainer.appendChild(errorMessage);
                }
            }
        }

        // Xử lý sự kiện click cho các date option
        dateOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();

                // Lấy thông tin ngày
                const formattedDate = this.getAttribute('data-formatted');
                const dateString = this.getAttribute('data-date');
                console.log(`Selected: ${dateString} (${formattedDate})`);

                // Cập nhật text trong dropdown button
                if (selectedDateText) {
                    selectedDateText.textContent = formattedDate;
                }

                // Hiển thị time slots
                showTimeSlots(dateString);
            });
        });

        // Hiển thị ngày đầu tiên khi trang load xong
        if (dateOptions.length > 0) {
            console.log("Selecting first date option");
            const firstOption = dateOptions[0];
            const firstDate = firstOption.getAttribute('data-date');
            const firstFormattedDate = firstOption.getAttribute('data-formatted');

            // Cập nhật text trong dropdown
            if (selectedDateText) {
                selectedDateText.textContent = firstFormattedDate;
            }

            // Delay chút để đảm bảo DOM đã sẵn sàng
            setTimeout(() => {
                showTimeSlots(firstDate);
            }, 200);
        } else {
            console.warn("No date options found");
        }

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

        // AJAX form submission for reviews
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = reviewForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;

                // Change button to loading state
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
                submitBtn.disabled = true;

                // Submit form via AJAX
                fetch(reviewForm.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Thank you!',
                            text: 'Your review has been submitted successfully.',
                            icon: 'success',
                            confirmButtonText: 'Close'
                        }).then(() => {
                            // Reload the page to show the updated reviews
                            window.location.reload();
                        });

                        // Update the average rating display
                        updateRatingDisplay(data.avgRating, data.ratingCount);

                        // Add the new review to the reviews section
                        if (data.review) {
                            addNewReviewToPage(data.review);
                        }

                        // Reset form and close modal
                        reviewForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById('writeReviewModal')).hide();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'An error occurred while submitting your review.',
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while submitting your review.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });
        }

        // Function to update the doctor rating display
        function updateRatingDisplay(avgRating, ratingCount) {
            const ratingContainer = document.querySelector('.doctor-rating');
            if (!ratingContainer) return;

            // Clear existing stars
            ratingContainer.innerHTML = '';

            // Add new stars based on updated average
            for (let i = 1; i <= 5; i++) {
                const starIcon = document.createElement('i');

                if (i <= Math.floor(avgRating)) {
                    starIcon.className = 'fas fa-star text-warning';
                } else if (i - 0.5 <= avgRating) {
                    starIcon.className = 'fas fa-star-half-alt text-warning';
                } else {
                    starIcon.className = 'far fa-star text-warning';
                }

                ratingContainer.appendChild(starIcon);
            }

            // Add rating text
            const ratingText = document.createElement('span');
            ratingText.className = 'ms-1';
            ratingText.textContent = `${Number(avgRating).toFixed(1)} (${ratingCount} reviews)`;
            ratingContainer.appendChild(ratingText);
        }

        // Function to add a new review to the page
        function addNewReviewToPage(review) {
            const reviewsContainer = document.querySelector('.card-body .border-bottom:last-child')?.parentNode;
            if (!reviewsContainer) {
                console.error('Could not find reviews container');
                return;
            }

            // Check if "No reviews available" message exists and remove it
            const noReviewsMsg = reviewsContainer.querySelector('.text-center.py-4');
            if (noReviewsMsg) {
                noReviewsMsg.remove();
            }

            // Create new review element
            const reviewDiv = document.createElement('div');
            reviewDiv.className = 'border-bottom pb-3 mb-3';
            reviewDiv.id = 'new-review-added';

            // Format review date
            const reviewDate = new Date(review.created_at);
            const formattedDate = reviewDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

            // Get user name (anonymous or real)
            let userName = 'Anonymous User';
            if (review.is_anonymous) {
                userName = 'Anonymous Patient';
            } else if (review.user && review.user.FullName) {
                userName = review.user.FullName;
            } else if (review.user_name) {
                userName = review.user_name;
            }

            // Create review HTML
            reviewDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="mb-0">${userName}</h5>
                        <small class="text-muted">${formattedDate}</small>
                    </div>
                    <div class="doctor-rating">
                        ${getStarRatingHTML(review.doctor_rating)}
                    </div>
                </div>
                <p>${review.feedback}</p>
            `;

            // Insert at the beginning of the container
            if (reviewsContainer.firstChild) {
                reviewsContainer.insertBefore(reviewDiv, reviewsContainer.firstChild);
            } else {
                reviewsContainer.appendChild(reviewDiv);
            }

            // Update "View All Reviews" link if it doesn't exist
            if (!reviewsContainer.querySelector('a.btn.btn-outline-primary')) {
                const linkDiv = document.createElement('div');
                linkDiv.className = 'text-center mt-3';
                linkDiv.innerHTML = `<a href="/doctor/public/ratings/${review.doctor_id}" class="btn btn-outline-primary">View All Reviews</a>`;
                reviewsContainer.appendChild(linkDiv);
            }
        }

        // Helper function to generate star rating HTML
        function getStarRatingHTML(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star text-warning"></i>';
                } else {
                    stars += '<i class="far fa-star text-warning"></i>';
                }
            }
            return stars;
        }

        // Immediately initialize Load More button
        console.log("DEBUG: Checking for Load More button");
        const loadMoreBtn = document.getElementById('load-more-reviews');
        if (loadMoreBtn) {
            console.log("DEBUG: Found Load More button, doctor ID:", loadMoreBtn.getAttribute('data-doctor'));
            console.log("DEBUG: Current page:", loadMoreBtn.getAttribute('data-page'));

            // Add click handler for the Load More button
            loadMoreBtn.addEventListener('click', function() {
                const doctorId = this.getAttribute('data-doctor');
                const page = parseInt(this.getAttribute('data-page'));
                const nextPage = page + 1;
                const loadingSpinner = document.getElementById('loading-spinner');

                console.log(`CLICK DETECTED: Loading more reviews for doctor ${doctorId}, page ${nextPage}`);

                // Show loading spinner
                loadMoreBtn.classList.add('d-none');

                // Create loading spinner if it doesn't exist
                let spinner = loadingSpinner;
                if (!spinner) {
                    console.log('DEBUG: Creating loading spinner as it does not exist');
                    spinner = document.createElement('div');
                    spinner.id = 'loading-spinner';
                    spinner.className = 'spinner-border text-primary';
                    spinner.setAttribute('role', 'status');
                    spinner.innerHTML = '<span class="visually-hidden">Loading...</span>';

                    // Insert spinner after load more button
                    const loadMoreContainer = document.getElementById('load-more-container');
                    if (loadMoreContainer) {
                        loadMoreContainer.appendChild(spinner);
                    }
                }

                // Show spinner
                spinner.classList.remove('d-none');

                // Make AJAX request to get more reviews
                const apiUrl = `/api/doctor/${doctorId}/reviews?page=${nextPage}`;
                console.log(`DEBUG: Fetching from URL: ${apiUrl}`);
                console.log(`DEBUG: Full URL: ${window.location.origin}${apiUrl}`);

                // Use the full URL with domain name
                fetch(`${window.location.origin}${apiUrl}`)
                    .then(response => {
                        console.log('DEBUG: Response status:', response.status);
                        console.log('DEBUG: Response headers:', Object.fromEntries([...response.headers]));
                        return response.json();
                    })
                    .then(data => {
                        console.log('DEBUG: Data received:', data);

                        if (data.success) {
                            console.log(`DEBUG: Success! Received ${data.reviews.length} reviews`);

                            // Check if we actually got any reviews
                            if (data.reviews.length === 0) {
                                console.log('DEBUG: No reviews received despite success response');

                                // Show message to user if appropriate
                                Swal.fire({
                                    icon: 'info',
                                    title: 'No More Reviews',
                                    text: 'There are no more reviews to load.'
                                });

                                // Remove the Load More button
                                document.getElementById('load-more-container').remove();
                                return;
                            }

                            // Append new reviews
                            const reviewsContainer = document.getElementById('reviews-container');

                            data.reviews.forEach(review => {
                                // Create review element
                                const reviewDiv = document.createElement('div');
                                reviewDiv.className = 'border-bottom pb-3 mb-3';

                                // Format date
                                const reviewDate = new Date(review.created_at);
                                const formattedDate = reviewDate.toLocaleDateString('en-US', {
                                    year: 'numeric', month: 'long', day: 'numeric'
                                });

                                // Determine user name
                                let userName = 'Anonymous User';
                                if (review.is_anonymous) {
                                    userName = 'Anonymous Patient';
                                } else if (review.user && review.user.FullName) {
                                    userName = review.user.FullName;
                                } else if (review.user_name) {
                                    userName = review.user_name;
                                }

                                console.log(`DEBUG: Processing review by ${userName}`);

                                // Create star rating HTML
                                let starsHtml = '';
                                for (let i = 1; i <= 5; i++) {
                                    if (i <= review.doctor_rating) {
                                        starsHtml += '<i class="fas fa-star text-warning"></i>';
                                    } else {
                                        starsHtml += '<i class="far fa-star text-warning"></i>';
                                    }
                                }

                                // Set inner HTML
                                reviewDiv.innerHTML = `
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h5 class="mb-0">${userName}</h5>
                                            <small class="text-muted">${formattedDate}</small>
                                        </div>
                                        <div class="doctor-rating">
                                            ${starsHtml}
                                        </div>
                                    </div>
                                    <p>${review.feedback}</p>
                                `;

                                reviewsContainer.appendChild(reviewDiv);
                            });

                            // Update page number in button
                            loadMoreBtn.setAttribute('data-page', nextPage);

                            // Show/hide Load More button based on if there are more reviews
                            if (data.has_more) {
                                loadMoreBtn.classList.remove('d-none');
                                console.log('DEBUG: More reviews available, showing Load More button');
                            } else {
                                // No more reviews to load
                                console.log('DEBUG: No more reviews available, removing Load More button');
                                document.getElementById('load-more-container').remove();
                            }
                        } else {
                            // Show error
                            console.error('Error loading more reviews:', data.message);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to load more reviews'
                            });
                            loadMoreBtn.classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('DEBUG: Error loading reviews:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while loading more reviews'
                        });
                        loadMoreBtn.classList.remove('d-none');
                    })
                                            .finally(() => {
                            // Hide spinner
                            const spinnerElement = document.getElementById('loading-spinner');
                            if (spinnerElement) {
                                spinnerElement.classList.add('d-none');
                            }
                        });
            });
        } else {
            console.warn("DEBUG: Load More button not found!");
            console.log("DEBUG: All buttons on page:", document.querySelectorAll('button').length);
        }
    });

    // Additional initialization outside the DOMContentLoaded event
    window.addEventListener('load', function() {
        console.log('DEBUG: Window fully loaded, adding extra handlers');

        // Add click handler for Write a Review button
        const writeReviewBtn = document.querySelector('button[data-bs-toggle="modal"][data-bs-target="#writeReviewModal"]');
        if (writeReviewBtn) {
            console.log('DEBUG: Found Write Review button, adding click handler');
            writeReviewBtn.addEventListener('click', function() {
                console.log('DEBUG: Opening review modal');
            });
        }
    });
</script>

<!-- Write Review Modal -->
<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="writeReviewModalLabel">
                    <i class="fas fa-star me-2"></i> Rate Dr. {{ $doctor->user->FullName ?? 'this doctor' }}
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