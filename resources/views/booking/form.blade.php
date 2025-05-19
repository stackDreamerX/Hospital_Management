@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.public.profile', $doctor->DoctorID) }}">Doctor Profile</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.schedule', $doctor->DoctorID) }}">Select Time</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking Form</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Booking Form -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-list me-2 text-primary"></i> Appointment Booking Form</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(!isset($selectedSlot) || $selectedSlot === null)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Vui lòng chọn thời gian khám từ lịch của bác sĩ trước.
                        </div>
                        <div class="text-center my-4">
                            <a href="{{ route('doctor.schedule', $doctor->DoctorID) }}" class="btn btn-primary">
                                <i class="far fa-calendar-alt me-2"></i> Xem lịch khám
                            </a>
                        </div>
                    @else
                    <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->DoctorID }}">
                        <input type="hidden" name="slot_id" value="{{ $selectedSlot->id }}">

                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="far fa-calendar-check fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <strong>Selected Time:</strong><br>
                                    {{ date('l, F d, Y', strtotime($selectedSlot->date)) }} at {{ date('h:i A', strtotime($selectedSlot->time)) }}
                                    <div class="small text-muted">Slot ID: {{ $selectedSlot->id }}</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Thông tin cá nhân</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" value="{{ old('fullname', Auth::check() ? Auth::user()->FullName : '') }}" required>
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="male">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">Nữ</label>
                                    </div>
                                </div>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::check() ? Auth::user()->PhoneNumber : '') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->Email : '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mt-4 mb-3">Địa chỉ</h5>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ cụ thể</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                <select class="form-select @error('province') is-invalid @enderror" id="province" name="province" required>
                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Thông tin khám</h5>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý do khám</label>
                            <input type="text" class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" placeholder="Ví dụ: Khám tổng quát, theo dõi..." value="{{ old('reason') }}" required>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Triệu chứng</label>
                            <textarea class="form-control @error('symptoms') is-invalid @enderror" id="symptoms" name="symptoms" rows="3" placeholder="Mô tả các triệu chứng của bạn">{{ old('symptoms') }}</textarea>
                            @error('symptoms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mt-4 mb-3">Phương thức thanh toán</h5>
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_cash">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i> Thanh toán tại quầy
                                </label>
                                <div class="text-muted small ms-4">Thanh toán trực tiếp tại quầy lễ tân của bệnh viện</div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay" {{ old('payment_method') == 'vnpay' ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_vnpay">
                                    <i class="fas fa-credit-card me-2 text-primary"></i> Thanh toán qua VNPay
                                </label>
                                <div class="text-muted small ms-4">Thanh toán trực tuyến qua cổng thanh toán VNPay (thẻ ATM, Visa, MasterCard, JCB...)</div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Tôi đồng ý với các điều khoản và điều kiện
                            </label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex mt-4">
                            <a href="{{ route('doctor.schedule', $doctor->DoctorID) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle me-1"></i> Xác nhận đặt khám
                            </button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('bookingForm');

                            if (form) {
                                console.log('Booking form found');

                                // Log form data that will be submitted
                                const doctorId = document.querySelector('input[name="doctor_id"]').value;
                                const slotId = document.querySelector('input[name="slot_id"]').value;
                                console.log('Form will submit with:', {
                                    doctorId: doctorId,
                                    slotId: slotId
                                });

                                // Add submit event listener
                                form.addEventListener('submit', function(e) {
                                    console.log('Form submission attempted');

                                    // Check for issue with slot_id
                                    const slotIdField = document.querySelector('input[name="slot_id"]');
                                    const slotId = slotIdField.value;

                                    if (!slotId || slotId === '0' || slotId === 0) {
                                        console.error('ERROR: slot_id is missing or invalid:', slotId);
                                        alert('Error: Selected time slot is invalid. Please go back and choose a different time slot.');
                                        e.preventDefault();
                                        return false;
                                    }

                                    // For virtual slots, make sure they follow the correct format (slot_YYYYMMDDHHMMSS)
                                    if (typeof slotId === 'string' && slotId.startsWith('slot_')) {
                                        const dateTimeStr = slotId.substring(5); // Remove 'slot_' prefix

                                        if (dateTimeStr.length < 12) {
                                            console.error('ERROR: Virtual slot ID has invalid format:', slotId);
                                            alert('Error: Selected time slot has an invalid format. Please go back and choose a different time slot.');
                                            e.preventDefault();
                                            return false;
                                        }

                                        // Extract date components to validate
                                        const year = parseInt(dateTimeStr.substring(0, 4), 10);
                                        const month = parseInt(dateTimeStr.substring(4, 6), 10);
                                        const day = parseInt(dateTimeStr.substring(6, 8), 10);
                                        const hour = parseInt(dateTimeStr.substring(8, 10), 10);
                                        const minute = parseInt(dateTimeStr.substring(10, 12), 10);

                                        // Basic validation of date components
                                        const validDate = new Date(year, month - 1, day);
                                        if (
                                            validDate.getFullYear() !== year ||
                                            validDate.getMonth() !== month - 1 ||
                                            validDate.getDate() !== day ||
                                            hour >= 24 ||
                                            minute >= 60
                                        ) {
                                            console.error('ERROR: Virtual slot ID has invalid date/time components:', {
                                                year, month, day, hour, minute
                                            });
                                            alert('Error: Selected time slot has invalid date or time. Please go back and choose a different time slot.');
                                            e.preventDefault();
                                            return false;
                                        }
                                    }

                                    console.log('Form validation passed, submitting...');
                                });
                            } else {
                                console.error('Booking form not found!');
                            }
                        });
                    </script>
                    @endif
                </div>
            </div>
        </div>

        <!-- Doctor Info -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->user->FullName }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="mt-3 mb-0">{{ $doctor->Title }} {{ $doctor->user->FullName }}</h5>
                        <p class="text-muted">{{ $doctor->Speciality }}</p>
                    </div>

                    <div class="appointment-details">
                        <h6 class="border-bottom pb-2 mb-3">Chi tiết cuộc hẹn</h6>

                        @if(isset($selectedSlot) && $selectedSlot !== null)
                        <div class="mb-3">
                            <strong><i class="far fa-calendar me-2 text-primary"></i> Ngày khám</strong>
                            <span>{{ date('d/m/Y', strtotime($selectedSlot->date)) }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="far fa-clock me-2 text-primary"></i> Giờ khám</strong>
                            <span>{{ date('h:i A', strtotime($selectedSlot->time)) }}</span>
                        </div>
                        @else
                        <div class="mb-3 text-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> Chưa chọn thời gian khám
                        </div>
                        @endif

                        <div class="mb-3">
                            <strong><i class="fas fa-money-bill-wave me-2 text-primary"></i> Phí khám</strong>
                            <span>{{ number_format($doctor->pricing_vn ?? 300000) }} VND</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt me-2 text-primary"></i> Địa điểm</strong>
                            <span>{{ $doctor->WorkLocation ?? 'Phòng khám Bệnh viện Quốc tế' }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="border-bottom pb-2 mb-3">Lưu ý</h6>
                        <ul class="text-muted small">
                            <li>Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục</li>
                            <li>Mang theo giấy tờ tùy thân và thẻ bảo hiểm y tế (nếu có)</li>
                            <li>Mang theo hồ sơ bệnh án và kết quả xét nghiệm (nếu có)</li>
                            <li>Thanh toán có thể thực hiện tại quầy hoặc chuyển khoản</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="border-bottom pb-2 mb-3">Hỗ trợ</h6>
                    <p class="text-muted small">Nếu bạn cần hỗ trợ hoặc có thắc mắc, vui lòng liên hệ:</p>
                    <p><i class="fas fa-phone me-2 text-primary"></i> 1900-1234</p>
                    <p><i class="fas fa-envelope me-2 text-primary"></i> support@hospital.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection