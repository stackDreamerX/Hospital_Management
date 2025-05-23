@extends('layout')

@section('title', 'Đơn thuốc của tôi | Medic Hospital')

@section('styles')
<style>
    .prescription-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
        border-left: 4px solid #3498db;
    }
    .prescription-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .status-pending {
        color: #f39c12;
    }
    .status-completed {
        color: #27ae60;
    }
    .status-cancelled {
        color: #e74c3c;
    }
    .empty-state {
        text-align: center;
        padding: 60px 0;
    }
    .empty-state i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    .prescription-date {
        font-weight: 500;
        font-size: 0.9rem;
    }
    .medicine-count {
        background-color: #3498db;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">Đơn thuốc của tôi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.dashboard') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Đơn thuốc</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($prescriptions->isEmpty())
                <div class="card">
                    <div class="card-body empty-state">
                        <i class="fas fa-prescription-bottle-alt"></i>
                        <h4>Chưa có đơn thuốc nào</h4>
                        <p class="text-muted">Bạn chưa có đơn thuốc nào được kê. Đơn thuốc sẽ xuất hiện tại đây sau khi bác sĩ kê cho bạn.</p>
                    </div>
                </div>
            @else
                @foreach($prescriptions as $prescription)
                    <div class="card prescription-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title">
                                        Đơn thuốc #{{ $prescription->PrescriptionID }}
                                        <span class="medicine-count">{{ $prescription->prescriptionDetail->count() }}</span>
                                    </h5>
                                    <p class="text-muted prescription-date">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ is_string($prescription->PrescriptionDate) ? $prescription->PrescriptionDate : $prescription->PrescriptionDate->format('d/m/Y') }}
                                    </p>
                                    @if($prescription->Diagnosis)
                                        <p><strong>Chẩn đoán:</strong> {{ $prescription->Diagnosis }}</p>
                                    @endif
                                    <p><strong>Bác sĩ:</strong> {{ $prescription->doctor->user->FullName }}</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <a href="{{ route('users.prescriptions.download-pdf', $prescription->PrescriptionID) }}" class="btn btn-primary me-2">
                                        <i class="fas fa-download me-1"></i> Tải PDF
                                    </a>
                                    <a href="{{ route('users.prescriptions.show', $prescription->PrescriptionID) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-eye me-1"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection