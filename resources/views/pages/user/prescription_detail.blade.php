@extends('layout')

@section('title', 'Chi tiết đơn thuốc | Medic Hospital')

@section('styles')
<style>
    .prescription-header {
        background-color: #f8f9fa;
        border-left: 4px solid #3498db;
        padding: 20px;
        margin-bottom: 20px;
    }
    .prescription-id {
        color: #3498db;
        font-weight: bold;
    }
    .medicine-table {
        margin-top: 20px;
    }
    .doctor-info {
        border-top: 1px solid #eee;
        margin-top: 30px;
        padding-top: 20px;
    }
    .vital-sign {
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .vital-sign .icon {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    .vital-sign .value {
        font-weight: bold;
        font-size: 1.2rem;
    }
    .bg-blood-pressure {
        background-color: #ffeaa7;
    }
    .bg-heart-rate {
        background-color: #fab1a0;
    }
    .bg-temperature {
        background-color: #a29bfe;
        color: white;
    }
    .bg-spo2 {
        background-color: #81ecec;
    }
    .print-btn {
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.dashboard') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.prescriptions') }}">Đơn thuốc của tôi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn thuốc</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="prescription-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Đơn thuốc <span class="prescription-id">#{{ $prescription->PrescriptionID }}</span></h3>
                            <div>
                                <a href="{{ route('users.prescriptions.download-pdf', $prescription->PrescriptionID) }}" class="btn btn-primary me-2">
                                    <i class="fas fa-download me-1"></i> Tải PDF
                                </a>
                                <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> In đơn thuốc
                                </a>
                            </div>
                        </div>
                        <p class="mb-1"><strong>Ngày kê đơn:</strong> {{ is_string($prescription->PrescriptionDate) ? $prescription->PrescriptionDate : $prescription->PrescriptionDate->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Bác sĩ:</strong> {{ $doctor->FullName }}</p>
                    </div>

                    @if($prescription->Diagnosis || $prescription->TestResults)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-clipboard-list me-2"></i> Thông tin khám bệnh</h5>
                        </div>
                        <div class="card-body">
                            @if($prescription->Diagnosis)
                                <p><strong>Chẩn đoán:</strong> {{ $prescription->Diagnosis }}</p>
                            @endif
                            @if($prescription->TestResults)
                                <p><strong>Kết quả xét nghiệm:</strong> {{ $prescription->TestResults }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($prescription->BloodPressure || $prescription->HeartRate || $prescription->Temperature || $prescription->SpO2)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="fas fa-heartbeat me-2"></i> Chỉ số sinh hiệu</h5>
                        </div>
                        @if($prescription->BloodPressure)
                        <div class="col-md-3 col-sm-6">
                            <div class="vital-sign bg-blood-pressure">
                                <div class="d-flex align-items-center">
                                    <div class="icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div>
                                        <div class="label">Huyết áp</div>
                                        <div class="value">{{ $prescription->BloodPressure }} mmHg</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($prescription->HeartRate)
                        <div class="col-md-3 col-sm-6">
                            <div class="vital-sign bg-heart-rate">
                                <div class="d-flex align-items-center">
                                    <div class="icon">
                                        <i class="fas fa-heartbeat"></i>
                                    </div>
                                    <div>
                                        <div class="label">Nhịp tim</div>
                                        <div class="value">{{ $prescription->HeartRate }} BPM</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($prescription->Temperature)
                        <div class="col-md-3 col-sm-6">
                            <div class="vital-sign bg-temperature">
                                <div class="d-flex align-items-center">
                                    <div class="icon">
                                        <i class="fas fa-thermometer-half"></i>
                                    </div>
                                    <div>
                                        <div class="label">Nhiệt độ</div>
                                        <div class="value">{{ $prescription->Temperature }} °C</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($prescription->SpO2)
                        <div class="col-md-3 col-sm-6">
                            <div class="vital-sign bg-spo2">
                                <div class="d-flex align-items-center">
                                    <div class="icon">
                                        <i class="fas fa-lungs"></i>
                                    </div>
                                    <div>
                                        <div class="label">SpO2</div>
                                        <div class="value">{{ $prescription->SpO2 }} %</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-prescription-bottle me-2"></i> Danh sách thuốc</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped medicine-table">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên thuốc</th>
                                            <th>Liều dùng</th>
                                            <th>Tần suất</th>
                                            <th>Thời gian</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescription->prescriptionDetail as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->medicine->MedicineName }}</td>
                                            <td>{{ $detail->Dosage }}</td>
                                            <td>{{ $detail->Frequency }}</td>
                                            <td>{{ $detail->Duration }}</td>
                                            <td>{{ $detail->Quantity }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($prescription->Instructions)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5><i class="fas fa-comment-medical me-2"></i> Lời dặn của bác sĩ</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $prescription->Instructions }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection