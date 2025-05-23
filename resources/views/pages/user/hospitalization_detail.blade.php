@extends('layout')

@section('title', 'Chi tiết nhập viện - Medic Hospital')

@section('styles')
<style>
    .vital-sign-normal { color: #28a745; }
    .vital-sign-warning { color: #ffc107; }
    .vital-sign-danger { color: #dc3545; }
    .treatment-improved { background-color: #d1e7dd; }
    .treatment-stable { background-color: #fff3cd; }
    .treatment-worsened { background-color: #f8d7da; }

    .status-badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }

    .info-box {
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 0.25rem;
        padding: 15px;
        height: 100%;
    }

    .info-box-title {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .info-box-value {
        font-size: 1.1rem;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Chi tiết nhập viện</h1>
            <p class="text-muted">Thông tin chi tiết về đợt điều trị nội trú</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('users.hospitalizations') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin tổng quan</h5>
                <span class="status-badge badge {{ $allocation->DischargeDate ? 'bg-secondary' : 'bg-success' }}">
                    {{ $allocation->DischargeDate ? 'Đã xuất viện' : 'Đang điều trị' }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="fas fa-hospital-alt me-1"></i> Phòng
                        </div>
                        <div class="info-box-value">
                            {{ $allocation->wardBed->ward->WardName ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="fas fa-bed me-1"></i> Giường số
                        </div>
                        <div class="info-box-value">
                            {{ $allocation->wardBed->BedNumber ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="fas fa-calendar-alt me-1"></i> Ngày nhập viện
                        </div>
                        <div class="info-box-value">
                            {{ $allocation->AllocationDate ? date('d/m/Y', strtotime($allocation->AllocationDate)) : 'N/A' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="fas fa-calendar-check me-1"></i> Ngày xuất viện
                        </div>
                        <div class="info-box-value">
                            {{ $allocation->DischargeDate ? date('d/m/Y', strtotime($allocation->DischargeDate)) : 'Đang điều trị' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Ghi chú</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $allocation->Notes ?? 'Không có ghi chú' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Theo dõi chỉ số sức khỏe -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Theo dõi chỉ số sức khỏe</h5>
        </div>
        <div class="card-body">
            @if($patientMonitorings->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                    <h5>Chưa có dữ liệu theo dõi</h5>
                    <p class="text-muted">Chưa có thông tin theo dõi sức khỏe cho đợt điều trị này</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Huyết áp</th>
                                <th>Nhịp tim</th>
                                <th>Nhiệt độ</th>
                                <th>SpO2</th>
                                <th>Đánh giá</th>
                                <th>Bác sĩ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patientMonitorings as $monitoring)
                            <tr class="treatment-{{ $monitoring->treatment_outcome }}">
                                <td>{{ date('d/m/Y H:i', strtotime($monitoring->recorded_at)) }}</td>
                                <td class="{{ getVitalSignClass($monitoring->blood_pressure, 90, 140, 60, 160) }}">
                                    {{ $monitoring->blood_pressure }} mmHg
                                </td>
                                <td class="{{ getVitalSignClass($monitoring->heart_rate, 60, 100, 50, 120) }}">
                                    {{ $monitoring->heart_rate }} bpm
                                </td>
                                <td class="{{ getVitalSignClass($monitoring->temperature, 36.1, 37.5, 35.5, 38.5) }}">
                                    {{ $monitoring->temperature }}°C
                                </td>
                                <td class="{{ getVitalSignClass($monitoring->spo2, 95, 100, 90, 100) }}">
                                    {{ $monitoring->spo2 }}%
                                </td>
                                <td>
                                    @if($monitoring->treatment_outcome == 'improved')
                                        <span class="badge bg-success">Cải thiện</span>
                                    @elseif($monitoring->treatment_outcome == 'stable')
                                        <span class="badge bg-warning text-dark">Ổn định</span>
                                    @else
                                        <span class="badge bg-danger">Xấu đi</span>
                                    @endif
                                </td>
                                <td>{{ $monitoring->doctor->FullName ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary view-details"
                                            data-id="{{ $monitoring->id }}"
                                            data-bs-toggle="modal" data-bs-target="#viewMonitoringDetailsModal"
                                            data-monitoring="{{ json_encode($monitoring) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Đơn thuốc -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Danh sách thuốc đã kê</h5>
        </div>
        <div class="card-body">
            @if($medications->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-prescription-bottle-alt fa-3x text-muted mb-3"></i>
                    <h5>Chưa có đơn thuốc</h5>
                    <p class="text-muted">Chưa có thuốc nào được kê trong đợt điều trị này</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Thuốc</th>
                                <th>Liều lượng</th>
                                <th>Thời gian dùng</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Bác sĩ kê đơn</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medications as $medication)
                            <tr>
                                <td>{{ $medication->medication_name }}</td>
                                <td>{{ $medication->dosage }}</td>
                                <td>{{ $medication->frequency }}</td>
                                <td>{{ date('d/m/Y', strtotime($medication->start_date)) }}</td>
                                <td>{{ $medication->end_date ? date('d/m/Y', strtotime($medication->end_date)) : 'Chưa kết thúc' }}</td>
                                <td>{{ $medication->doctor->FullName ?? 'N/A' }}</td>
                                <td>
                                    @if(!$medication->end_date || strtotime($medication->end_date) >= strtotime('today'))
                                        <span class="badge bg-success">Đang dùng</span>
                                    @else
                                        <span class="badge bg-secondary">Đã kết thúc</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    @if($allocation->DischargeDate)
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Bạn có thể xem danh sách đơn thuốc đầy đủ trong mục <a href="{{ route('users.prescriptions') }}" class="alert-link">Đơn thuốc của tôi</a>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Hóa đơn viện phí -->
    @if($allocation->DischargeDate)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Hóa đơn viện phí</h5>
        </div>
        <div class="card-body">
            <div class="text-center py-3">
                <i class="fas fa-file-invoice-dollar fa-3x text-success mb-3"></i>
                <h5>Xuất hóa đơn</h5>
                <p>Bạn có thể tải xuống hóa đơn viện phí cho đợt điều trị này</p>
                <button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#generatePdfModal">
                    <i class="fas fa-download me-2"></i> Tải xuống hóa đơn
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal chi tiết theo dõi -->
<div class="modal fade" id="viewMonitoringDetailsModal" tabindex="-1" aria-labelledby="viewMonitoringDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMonitoringDetailsModalLabel">Chi tiết theo dõi sức khỏe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="monitoringDetailsContent">
                <!-- Sẽ được điền bởi JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xuất hóa đơn PDF -->
<div class="modal fade" id="generatePdfModal" tabindex="-1" aria-labelledby="generatePdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePdfModalLabel">Xuất hóa đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pdfGenerationForm" action="{{ route('allocations.generate-pdf', $allocation) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="insurance_discount" class="form-label">Giảm giá bảo hiểm (%)</label>
                        <input type="number" class="form-control" id="insurance_discount" name="insurance_discount"
                            min="0" max="100" value="0">
                        <div class="form-text">Nhập phần trăm giảm giá từ bảo hiểm y tế (nếu có)</div>
                    </div>
                    <div class="mb-3">
                        <label for="additional_notes" class="form-label">Ghi chú hóa đơn</label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" form="pdfGenerationForm" class="btn btn-success">Xuất PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý hiển thị chi tiết
        const viewButtons = document.querySelectorAll('.view-details');

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const monitoringData = JSON.parse(this.getAttribute('data-monitoring'));
                const monitoringDetailsContent = document.getElementById('monitoringDetailsContent');

                // Function để xác định class cho chỉ số sức khỏe
                function getVitalSignClassJS(value, normalMin, normalMax, warningMin, warningMax) {
                    // Chuyển đổi giá trị huyết áp từ format "120/80" thành số
                    if (typeof value === 'string' && value.includes('/')) {
                        const systolic = parseInt(value.split('/')[0]);
                        if (systolic >= normalMin && systolic <= normalMax) {
                            return 'vital-sign-normal';
                        } else if (systolic >= warningMin && systolic <= warningMax) {
                            return 'vital-sign-warning';
                        } else {
                            return 'vital-sign-danger';
                        }
                    }

                    // Cho các chỉ số khác
                    if (value >= normalMin && value <= normalMax) {
                        return 'vital-sign-normal';
                    } else if (value >= warningMin && value <= warningMax) {
                        return 'vital-sign-warning';
                    } else {
                        return 'vital-sign-danger';
                    }
                }

                // Định dạng kết quả đánh giá
                function formatOutcome(outcome) {
                    if (outcome === 'improved') return 'Cải thiện';
                    if (outcome === 'stable') return 'Ổn định';
                    if (outcome === 'worsened') return 'Xấu đi';
                    return 'Không xác định';
                }

                // Hiển thị dữ liệu vào modal
                monitoringDetailsContent.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Thông tin cơ bản</h6>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Ngày ghi nhận
                                    <span>${new Date(monitoringData.recorded_at).toLocaleString('vi-VN')}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Bác sĩ
                                    <span>${monitoringData.doctor ? monitoringData.doctor.FullName : 'N/A'}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Chỉ số sức khỏe</h6>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Huyết áp
                                    <span class="${getVitalSignClassJS(monitoringData.blood_pressure, 90, 140, 60, 160)}">${monitoringData.blood_pressure} mmHg</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Nhịp tim
                                    <span class="${getVitalSignClassJS(monitoringData.heart_rate, 60, 100, 50, 120)}">${monitoringData.heart_rate} bpm</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Nhiệt độ
                                    <span class="${getVitalSignClassJS(monitoringData.temperature, 36.1, 37.5, 35.5, 38.5)}">${monitoringData.temperature}°C</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    SpO2
                                    <span class="${getVitalSignClassJS(monitoringData.spo2, 95, 100, 90, 100)}">${monitoringData.spo2}%</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6>Đánh giá</h6>
                            <div class="card">
                                <div class="card-body treatment-${monitoringData.treatment_outcome}">
                                    <p>${formatOutcome(monitoringData.treatment_outcome)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Ghi chú</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p>${monitoringData.notes || 'Không có ghi chú'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        });

        // Handle PDF generation success
        const pdfForm = document.getElementById('pdfGenerationForm');
        if (pdfForm) {
            pdfForm.addEventListener('submit', function(e) {
                // We can show a loading indicator or success message if needed
                document.querySelector('#generatePdfModal button[type="submit"]').innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang tạo...';
                document.querySelector('#generatePdfModal button[type="submit"]').disabled = true;

                // After PDF is downloaded, reset the button and close modal with a delay
                setTimeout(() => {
                    // Show success message using SweetAlert
                    const modal = bootstrap.Modal.getInstance(document.getElementById('generatePdfModal'));
                    modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Hóa đơn PDF đã được tải xuống',
                        timer: 3000,
                        showConfirmButton: false
                    });

                    // Reset button state
                    document.querySelector('#generatePdfModal button[type="submit"]').innerHTML = 'Xuất PDF';
                    document.querySelector('#generatePdfModal button[type="submit"]').disabled = false;
                }, 1500);
            });
        }
    });
</script>
@endsection

@php
// Hàm hỗ trợ để tô màu chỉ số
function getVitalSignClass($value, $normalMin, $normalMax, $warningMin, $warningMax) {
    if (is_string($value) && strpos($value, '/') !== false) {
        // Xử lý huyết áp (120/80)
        $systolic = (int) explode('/', $value)[0];
        if ($systolic >= $normalMin && $systolic <= $normalMax) {
            return 'vital-sign-normal';
        } elseif ($systolic >= $warningMin && $systolic <= $warningMax) {
            return 'vital-sign-warning';
        } else {
            return 'vital-sign-danger';
        }
    }

    if ($value >= $normalMin && $value <= $normalMax) {
        return 'vital-sign-normal';
    } elseif ($value >= $warningMin && $value <= $warningMax) {
        return 'vital-sign-warning';
    } else {
        return 'vital-sign-danger';
    }
}
@endphp