@extends('admin_layout')

@push('styles')
<style>
    .vital-sign-normal { color: #28a745; }
    .vital-sign-warning { color: #ffc107; }
    .vital-sign-danger { color: #dc3545; }
    .treatment-improved { background-color: #d1e7dd; }
    .treatment-stable { background-color: #fff3cd; }
    .treatment-worsened { background-color: #f8d7da; }
</style>
@endpush

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
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

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bed Allocation Details</h4>
                    <div class="float-end">
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#generatePdfModal">
                            <i class="fas fa-file-pdf"></i> Xuất hóa đơn
                        </button>
                        <a href="{{ route('allocations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Allocation Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Patient:</th>
                                    <td>{{ $allocation->patient->FullName ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <th>Ward:</th>
                                    <td>{{ $allocation->wardBed->ward->WardName ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bed Number:</th>
                                    <td>{{ $allocation->wardBed->BedNumber ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($allocation->DischargeDate)
                                            <span class="badge bg-secondary">Discharged</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Dates</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Allocation Date:</th>
                                    <td>{{ $allocation->AllocationDate ? date('M d, Y H:i', strtotime($allocation->AllocationDate)) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Discharge Date:</th>
                                    <td>{{ $allocation->DischargeDate ? date('M d, Y H:i', strtotime($allocation->DischargeDate)) : 'Not discharged yet' }}</td>
                                </tr>
                                <tr>
                                    <th>Allocated By:</th>
                                    <td>{{ $allocation->allocatedBy->FullName ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ date('M d, Y H:i', strtotime($allocation->created_at)) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($allocation->Notes ?? 'No notes available')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thêm phần theo dõi bệnh nhân nội trú -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Theo dõi bệnh nhân nội trú</h5>
                                    @if(!$allocation->DischargeDate)
                                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addVitalSignsModal">
                                        <i class="fas fa-plus"></i> Thêm chỉ số mới
                                    </button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Ngày</th>
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
                                                @forelse($patientMonitorings ?? [] as $monitoring)
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
                                                        <button type="button" class="btn btn-sm btn-info view-details"
                                                                data-id="{{ $monitoring->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Chưa có dữ liệu theo dõi</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thêm phần quản lý đơn thuốc -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Quản lý đơn thuốc</h5>
                                    @if(!$allocation->DischargeDate)
                                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addMedicationModal">
                                        <i class="fas fa-pills"></i> Kê đơn thuốc
                                    </button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
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
                                                @forelse($medications ?? [] as $medication)
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
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Chưa có đơn thuốc nào</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$allocation->DischargeDate)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Discharge Patient</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('allocations.discharge', $allocation) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="DischargeDate">Discharge Date</label>
                                                    <input type="datetime-local" name="DischargeDate" id="DischargeDate"
                                                        class="form-control {{ $errors->has('DischargeDate') ? 'is-invalid' : '' }}"
                                                        value="{{ old('DischargeDate', date('Y-m-d\TH:i')) }}" required>
                                                    @if($errors->has('DischargeDate'))
                                                        <div class="invalid-feedback">{{ $errors->first('DischargeDate') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="BedStatus">Bed Status After Discharge</label>
                                                    <select name="BedStatus" id="BedStatus" class="form-select {{ $errors->has('BedStatus') ? 'is-invalid' : '' }}" required>
                                                        <option value="maintenance">Maintenance (Cleaning)</option>
                                                        <option value="available">Available (Skip Cleaning)</option>
                                                    </select>
                                                    @if($errors->has('BedStatus'))
                                                        <div class="invalid-feedback">{{ $errors->first('BedStatus') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="DischargeNotes">Discharge Notes</label>
                                                    <textarea name="DischargeNotes" id="DischargeNotes" rows="2"
                                                        class="form-control {{ $errors->has('DischargeNotes') ? 'is-invalid' : '' }}">{{ old('DischargeNotes') }}</textarea>
                                                    @if($errors->has('DischargeNotes'))
                                                        <div class="invalid-feedback">{{ $errors->first('DischargeNotes') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to discharge this patient?')">
                                                <i class="fas fa-door-open"></i> Discharge Patient
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Bed History</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                            <th>Updated By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allocation->wardBed->history()->orderBy('FromDate', 'desc')->take(5)->get() as $history)
                                        <tr>
                                            <td>{{ date('M d, Y H:i', strtotime($history->FromDate)) }}</td>
                                            <td>
                                                @if($history->Status == 'available')
                                                    <span class="badge bg-success">Available</span>
                                                @elseif($history->Status == 'occupied')
                                                    <span class="badge bg-danger">Occupied</span>
                                                @else
                                                    <span class="badge bg-warning">Maintenance</span>
                                                @endif
                                            </td>
                                            <td>{{ $history->Note ?? '-' }}</td>
                                            <td>{{ $history->updatedBy->FullName ?? 'System' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No history records found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('bed-history.for-bed', $allocation->wardBed) }}" class="btn btn-sm btn-info">
                                    View Full Bed History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        @if(!$allocation->DischargeDate)
                            <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Notes
                            </a>
                        @endif
                        <a href="{{ route('beds.show', $allocation->wardBed) }}" class="btn btn-info">
                            <i class="fas fa-bed"></i> View Bed Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm chỉ số theo dõi -->
<div class="modal fade" id="addVitalSignsModal" tabindex="-1" aria-labelledby="addVitalSignsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVitalSignsModalLabel">Thêm chỉ số theo dõi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('patient-monitoring.store', ['allocation' => $allocation->AllocationID]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="blood_pressure" class="form-label">Huyết áp (mmHg)</label>
                                <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="heart_rate" class="form-label">Nhịp tim (bpm)</label>
                                <input type="number" class="form-control" id="heart_rate" name="heart_rate" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="temperature" class="form-label">Nhiệt độ (°C)</label>
                                <input type="number" step="0.1" class="form-control" id="temperature" name="temperature" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="spo2" class="form-label">SpO2 (%)</label>
                                <input type="number" class="form-control" id="spo2" name="spo2" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="treatment_outcome" class="form-label">Đánh giá kết quả</label>
                                <select class="form-select" id="treatment_outcome" name="treatment_outcome" required>
                                    <option value="improved">Cải thiện</option>
                                    <option value="stable" selected>Ổn định</option>
                                    <option value="worsened">Xấu đi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label">Bác sĩ theo dõi</label>
                                <select class="form-select" id="doctor_id" name="doctor_id" required>
                                    @if($doctors->isEmpty())
                                        <option value="">Không có bác sĩ nào</option>
                                    @else
                                        @foreach($doctors as $doctor)
                                            @if($loop->first)
                                                <option value="{{ $doctor->UserID }}" selected>{{ $doctor->FullName }}</option>
                                            @else
                                                <option value="{{ $doctor->UserID }}">{{ $doctor->FullName }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal thêm đơn thuốc -->
<div class="modal fade" id="addMedicationModal" tabindex="-1" aria-labelledby="addMedicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMedicationModalLabel">Kê đơn thuốc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('medications.store', ['allocation' => $allocation->AllocationID]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medication_name" class="form-label">Tên thuốc</label>
                                <input type="text" class="form-control" id="medication_name" name="medication_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dosage" class="form-label">Liều lượng</label>
                                <input type="text" class="form-control" id="dosage" name="dosage" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="frequency" class="form-label">Thời gian dùng</label>
                                <input type="text" class="form-control" id="frequency" name="frequency" placeholder="VD: 3 lần/ngày sau bữa ăn" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label">Bác sĩ kê đơn</label>
                                <select class="form-select" id="doctor_id" name="doctor_id" required>
                                    @if($doctors->isEmpty())
                                        <option value="">Không có bác sĩ nào</option>
                                    @else
                                        @foreach($doctors as $doctor)
                                            @if($loop->first)
                                                <option value="{{ $doctor->UserID }}" selected>{{ $doctor->FullName }}</option>
                                            @else
                                                <option value="{{ $doctor->UserID }}">{{ $doctor->FullName }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc (không bắt buộc)</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Hướng dẫn sử dụng</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chi tiết theo dõi -->
<div class="modal fade" id="viewMonitoringDetailsModal" tabindex="-1" aria-labelledby="viewMonitoringDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMonitoringDetailsModalLabel">Chi tiết theo dõi bệnh nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="monitoringDetailsContent">
                <!-- Sẽ được điền bởi JavaScript -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
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
                <button type="submit" form="pdfGenerationForm" class="btn btn-primary">Xuất PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Định nghĩa hàm JavaScript để styling (khác với hàm PHP cùng tên)
    function getVitalSignClassJS(value, normalMin, normalMax, warningMin, warningMax) {
        if (value >= normalMin && value <= normalMax) {
            return 'vital-sign-normal';
        } else if (value >= warningMin && value <= warningMax) {
            return 'vital-sign-warning';
        } else {
            return 'vital-sign-danger';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý xem chi tiết theo dõi
        const viewButtons = document.querySelectorAll('.view-details');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const monitoringId = this.getAttribute('data-id');
                const modal = new bootstrap.Modal(document.getElementById('viewMonitoringDetailsModal'));

                // Giả lập tải dữ liệu
                setTimeout(() => {
                    fetch(`{{ url('patient-monitoring') }}/${monitoringId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        // Hiển thị dữ liệu chi tiết
                        document.getElementById('monitoringDetailsContent').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Thông tin cơ bản</h6>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Ngày ghi nhận
                                            <span>${new Date(data.recorded_at).toLocaleString('vi-VN')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Bác sĩ
                                            <span>${data.doctor.FullName}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Chỉ số sức khỏe</h6>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Huyết áp
                                            <span class="${getVitalSignClassJS(data.blood_pressure, 90, 140, 60, 160)}">${data.blood_pressure} mmHg</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Nhịp tim
                                            <span class="${getVitalSignClassJS(data.heart_rate, 60, 100, 50, 120)}">${data.heart_rate} bpm</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Nhiệt độ
                                            <span class="${getVitalSignClassJS(data.temperature, 36.1, 37.5, 35.5, 38.5)}">${data.temperature}°C</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            SpO2
                                            <span class="${getVitalSignClassJS(data.spo2, 95, 100, 90, 100)}">${data.spo2}%</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6>Đánh giá</h6>
                                    <div class="card">
                                        <div class="card-body treatment-${data.treatment_outcome}">
                                            <p>${data.treatment_outcome === 'improved' ? 'Cải thiện' :
                                                (data.treatment_outcome === 'stable' ? 'Ổn định' : 'Xấu đi')}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Ghi chú</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <p>${data.notes || 'Không có ghi chú'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    })
                    .catch(error => {
                        document.getElementById('monitoringDetailsContent').innerHTML = `
                            <div class="alert alert-danger">
                                Không thể tải dữ liệu. Vui lòng thử lại sau.
                            </div>
                        `;
                    });
                }, 500);

                modal.show();
            });
        });
    });
</script>
@endpush

@php
// Hàm hỗ trợ để tô màu chỉ số
function getVitalSignClass($value, $normalMin, $normalMax, $warningMin, $warningMax) {
    if ($value >= $normalMin && $value <= $normalMax) {
        return 'vital-sign-normal';
    } elseif ($value >= $warningMin && $value <= $warningMax) {
        return 'vital-sign-warning';
    } else {
        return 'vital-sign-danger';
    }
}
@endphp