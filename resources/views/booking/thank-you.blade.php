@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="success-checkmark">
                            <div class="check-icon">
                                <span class="icon-line line-tip"></span>
                                <span class="icon-line line-long"></span>
                                <div class="icon-circle"></div>
                                <div class="icon-fix"></div>
                            </div>
                        </div>
                    </div>

                    <h2 class="mb-3">Đặt lịch khám thành công!</h2>
                    <p class="text-muted mb-4">Cảm ơn bạn đã đặt lịch khám bệnh với chúng tôi. Chúng tôi đã ghi nhận thông tin và sẽ gửi xác nhận qua email cho bạn.</p>

                    <div class="appointment-details bg-light p-4 rounded mb-4 text-start">
                        <h5 class="border-bottom pb-2 mb-3">Chi tiết cuộc hẹn</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="far fa-calendar me-2 text-primary"></i> Ngày khám:</strong>
                                <p>{{ date('d/m/Y', strtotime($appointment->AppointmentDate)) }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong><i class="far fa-clock me-2 text-primary"></i> Giờ khám:</strong>
                                <p>{{ date('h:i A', strtotime($appointment->AppointmentTime)) }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-user-md me-2 text-primary"></i> Bác sĩ:</strong>
                                <p>{{ $appointment->doctor->Title }} {{ $appointment->doctor->user->FullName }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-stethoscope me-2 text-primary"></i> Chuyên khoa:</strong>
                                <p>{{ $appointment->doctor->Speciality }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-map-marker-alt me-2 text-primary"></i> Địa điểm:</strong>
                                <p>{{ $appointment->doctor->WorkLocation ?? 'Phòng khám Bệnh viện Quốc tế' }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-money-bill-wave me-2 text-primary"></i> Phí khám:</strong>
                                <p>{{ number_format($appointment->amount ?? $appointment->doctor->pricing_vn ?? 300000) }} VND</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-credit-card me-2 text-primary"></i> Phương thức thanh toán:</strong>
                                <p>
                                    @if($appointment->payment_method == 'cash')
                                        <span><i class="fas fa-money-bill-wave me-1 text-success"></i> Thanh toán tại quầy</span>
                                    @elseif($appointment->payment_method == 'vnpay')
                                        <span><i class="fas fa-credit-card me-1 text-primary"></i> VNPay</span>
                                    @elseif($appointment->payment_method == 'zalopay')
                                        <span><i class="fas fa-wallet me-1 text-info"></i> ZaloPay</span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-check-circle me-2 text-primary"></i> Trạng thái thanh toán:</strong>
                                <p>
                                    @if($appointment->payment_status == 'paid')
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i> Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Chưa thanh toán</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <strong><i class="fas fa-info-circle me-2 text-primary"></i> Lý do khám:</strong>
                                <p>{{ $appointment->Reason }}</p>
                            </div>
                        </div>
                    </div>

                    @if($appointment->payment_method == 'cash' && $appointment->payment_status == 'pending')
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="alert-heading">Thanh toán tại quầy</h5>
                                <p class="mb-0">Vui lòng thanh toán phí khám tại quầy lễ tân của bệnh viện trước khi khám.</p>
                            </div>
                        </div>
                    </div>
                    @elseif(($appointment->payment_method == 'vnpay' || $appointment->payment_method == 'zalopay') && $appointment->payment_status == 'paid')
                    <div class="alert alert-success mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="alert-heading">Thanh toán thành công</h5>
                                <p class="mb-0">Thanh toán của bạn qua {{ $appointment->payment_method == 'vnpay' ? 'VNPay' : 'ZaloPay' }} đã được xác nhận. Vui lòng đến đúng giờ hẹn.</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-exclamation-circle me-2"></i> Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục. Mang theo giấy tờ tùy thân và thẻ bảo hiểm y tế (nếu có).
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-center mt-4">
                        @if(Auth::check())
                            <a href="{{ route('users.appointments') }}" class="btn btn-primary">
                                <i class="fas fa-list-alt me-1"></i> Xem lịch hẹn của tôi
                            </a>
                        @endif
                        <a href="{{ route('users') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-1"></i> Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }
    .success-checkmark .check-icon {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4CAF50;
    }
    .success-checkmark .check-icon::before {
        top: 3px;
        left: -2px;
        width: 30px;
        transform-origin: 100% 50%;
        border-radius: 100px 0 0 100px;
    }
    .success-checkmark .check-icon::after {
        top: 0;
        left: 30px;
        width: 60px;
        transform-origin: 0 50%;
        border-radius: 0 100px 100px 0;
        animation: rotate-circle 4.25s ease-in;
    }
    .success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
        content: '';
        height: 100px;
        position: absolute;
        background: #FFFFFF;
        transform: rotate(-45deg);
    }
    .success-checkmark .check-icon .icon-line {
        height: 5px;
        background-color: #4CAF50;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }
    .success-checkmark .check-icon .icon-line.line-tip {
        top: 46px;
        left: 14px;
        width: 25px;
        transform: rotate(45deg);
        animation: icon-line-tip 0.75s;
    }
    .success-checkmark .check-icon .icon-line.line-long {
        top: 38px;
        right: 8px;
        width: 47px;
        transform: rotate(-45deg);
        animation: icon-line-long 0.75s;
    }
    .success-checkmark .check-icon .icon-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(76, 175, 80, .5);
    }
    .success-checkmark .check-icon .icon-fix {
        top: 8px;
        width: 5px;
        left: 26px;
        z-index: 1;
        height: 85px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #FFFFFF;
    }
    @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }
        5% {
            transform: rotate(-45deg);
        }
        12% {
            transform: rotate(-405deg);
        }
        100% {
            transform: rotate(-405deg);
        }
    }
    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }
        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }
        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
    }
    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }
        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
    }
</style>
@endsection
