@extends('patient_layout')


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')

<style>
     modal {
    display: none; /* Ẩn modal ban đầu */
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050; /* Bootstrap 5 modal z-index */
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5); /* Overlay mờ */
    }

    .modal.fade {
    opacity: 0; /* Modal mờ khi chưa được hiển thị */
    transition: opacity 0.15s linear;
    }

    .modal.show {
    display: block; /* Hiển thị modal */
    opacity: 1;
    }

    .modal-dialog {
    position: relative;
    margin: 1.75rem auto; /* Center modal vertically */
    pointer-events: auto;
    max-width: 500px; /* Độ rộng mặc định */
    }

    .modal-dialog.modal-lg {
    max-width: 800px; /* Độ rộng modal lớn */
    }

    .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    border: none;
    border-radius: 0.5rem; /* Bo góc */
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Đổ bóng */
    }

    .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6; /* Border dưới */
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    }

    .modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    }

    .btn-close {
    background: none;
    border: none;
    -webkit-appearance: none;
    }

    .modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
    }

    .modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    }

</style>


<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending Prescriptions</h6>
                            <h2 class="mb-0">{{ $pendingCount }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Completed Prescriptions</h6>
                            <h2 class="mb-0">{{ $completedCount }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Spent</h6>
                            <h2 class="mb-0">{{ number_format($totalSpent) }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">My Prescriptions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Medicines</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription['Date'] }}</td>
                            <td>{{ $prescription['DoctorName'] }}</td>
                            <td>
                                @foreach($prescription['Items'] as $item)
                                    <div>{{ $item['Name'] }} - {{ $item['Dosage'] }}</div>
                                @endforeach
                            </td>
                            <td>{{ number_format($prescription['TotalAmount']) }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $prescription['Status'] == 'Completed' ? 'success' : 'warning'
                                }}">{{ $prescription['Status'] }}</span>
                                <span class="badge bg-{{
                                    $prescription['PaymentStatus'] == 'Paid' ? 'success' : 'warning'
                                }}">{{ $prescription['PaymentStatus'] }}</span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="viewDetails({{ json_encode($prescription) }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No prescriptions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent"></div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
let detailsModal;

document.addEventListener('DOMContentLoaded', function() {
    detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
});

function viewDetails(prescription) {
    const content = document.getElementById('detailsContent');
    const items = prescription.Items.map(item => `
        <li>${item.Name} - ${item.Dosage} x ${item.Quantity}</li>
    `).join('');
    content.innerHTML = `
        <h5>Date: ${prescription.Date}</h5>
        <h5>Doctor: ${prescription.DoctorName}</h5>
        <ul>${items}</ul>
        <h5>Total: ${prescription.TotalAmount}</h5>
    `;
    detailsModal.show();
}
</script>
@endsection



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush