@extends('layout')

@section('title', 'Lịch sử nhập viện - Medic Hospital')

@section('styles')
<style>
    .hospitalization-card {
        transition: all 0.3s ease;
        border-left: 5px solid #3498db;
        margin-bottom: 15px;
    }

    .hospitalization-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        transform: translateY(-2px);
        background-color: #f8f9fa;
        border-color: #2980b9;
    }

    .hospitalization-card.active {
        border-left-color: #2ecc71;
    }

    .hospitalization-card.active:hover {
        border-left-color: #27ae60;
    }

    .hospitalization-card.discharged {
        border-left-color: #95a5a6;
    }

    .hospitalization-card.discharged:hover {
        border-left-color: #7f8c8d;
    }

    .status-badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }

    .duration-tag {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2c3e50;
        padding: 0.25em 0.5em;
        border-radius: 4px;
        background-color: rgba(52, 152, 219, 0.1);
        display: inline-block;
    }

    .hospitalization-card:hover .text-muted {
        color: #495057 !important;
    }

    .hospitalization-card:hover .duration-tag {
        background-color: rgba(52, 152, 219, 0.2);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Lịch sử nhập viện</h1>
            <p class="text-muted">Lịch sử điều trị nội trú của bạn tại Medic Hospital</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('users.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Danh sách lần nhập viện</h5>
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <div class="badge bg-success me-2">Đang điều trị</div>
                        <div class="badge bg-secondary">Đã xuất viện</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($hospitalizations->isEmpty())
                <div class="p-4 text-center">
                    <div class="py-5">
                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                        <h4>Không có lịch sử nhập viện</h4>
                        <p class="text-muted">Bạn chưa có lần điều trị nội trú nào tại Medic Hospital</p>
                    </div>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($hospitalizations as $hospitalization)
                        <a href="{{ route('users.hospitalizations.show', $hospitalization->AllocationID) }}"
                           class="list-group-item list-group-item-action p-3 hospitalization-card {{ $hospitalization->DischargeDate ? 'discharged' : 'active' }}">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h5 class="mb-1">Phòng {{ $hospitalization->wardBed->ward->WardName ?? 'N/A' }}</h5>
                                    <p class="mb-0 text-muted">Giường số: {{ $hospitalization->wardBed->BedNumber ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="small mb-1">
                                        <i class="far fa-calendar-alt me-1"></i> Ngày nhập viện
                                    </div>
                                    <div class="fw-bold">{{ $hospitalization->AllocationDate ? date('d/m/Y', strtotime($hospitalization->AllocationDate)) : 'N/A' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small mb-1">
                                        <i class="far fa-calendar-check me-1"></i> Ngày xuất viện
                                    </div>
                                    <div class="fw-bold">
                                        {{ $hospitalization->DischargeDate ? date('d/m/Y', strtotime($hospitalization->DischargeDate)) : 'Đang điều trị' }}
                                    </div>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    <span class="status-badge badge {{ $hospitalization->DischargeDate ? 'bg-secondary' : 'bg-success' }}">
                                        {{ $hospitalization->DischargeDate ? 'Đã xuất viện' : 'Đang điều trị' }}
                                    </span>

                                    @php
                                        $startDate = new DateTime($hospitalization->AllocationDate);
                                        $endDate = $hospitalization->DischargeDate
                                            ? new DateTime($hospitalization->DischargeDate)
                                            : new DateTime();
                                        $duration = $startDate->diff($endDate);
                                        $days = $duration->days + 1; // Include both start and end day
                                    @endphp

                                    <div class="duration-tag mt-2">
                                        <i class="far fa-clock me-1"></i> {{ $days }} ngày
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Thông tin hữu ích</h5>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h6>Chăm sóc sau xuất viện</h6>
                            <p class="text-muted mb-0">Vui lòng tuân thủ các hướng dẫn và đơn thuốc của bác sĩ sau khi xuất viện để đảm bảo quá trình hồi phục tốt nhất.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-file-medical text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h6>Hồ sơ bệnh án</h6>
                            <p class="text-muted mb-0">Bạn có thể yêu cầu bản sao hồ sơ y tế của mình tại quầy tiếp tân hoặc liên hệ với bộ phận hồ sơ bệnh án.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection