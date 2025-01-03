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
                            <h6 class="mb-0">Ongoing Treatments</h6>
                            <h2 class="mb-0">{{ $ongoingCount }}</h2>
                        </div>
                        <i class="fas fa-procedures fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Completed Treatments</h6>
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
                            <h6 class="mb-0">Total Cost</h6>
                            <h2 class="mb-0">Rs. {{ number_format($totalCost) }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatments List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Treatments</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search treatments...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>Treatment</th>
                            <th>Doctor</th>
                            <th>Status (Progress)</th>
                            <th>Cost</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment['StartDate'] }}</td>
                            <td>{{ $treatment['TreatmentName'] }}</td>
                            <td>{{ $treatment['DoctorName'] }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $treatment['Status'] == 'Completed' ? 'success' :
                                    ($treatment['Status'] == 'Scheduled' ? 'info' : 'warning')
                                }}">
                                    {{ $treatment['Status'] == 'Scheduled' ? 'Scheduled' : $treatment['Status'] }}
                                </span>
                            </td>
                            <td>Rs. {{ number_format($treatment['Cost']) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewDetails({{ json_encode($treatment) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary" onclick="downloadReport({{ $treatment['TreatmentID'] }})">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No treatments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Treatment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printDetails()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let detailsModal;

document.addEventListener('DOMContentLoaded', function() {
    detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});

function viewDetails(treatment) {
    const content = document.getElementById('detailsContent');
    
    content.innerHTML = `
        <div class="mb-3">
            <strong>Treatment:</strong> ${treatment.TreatmentName}
        </div>
        <div class="mb-3">
            <strong>Doctor:</strong> ${treatment.DoctorName}
        </div>
        <div class="mb-3">
            <strong>Period:</strong> ${treatment.StartDate} to ${treatment.EndDate}
        </div>
        <div class="mb-3">
            <strong>Status (Progress):</strong> ${treatment.Status}
        </div>
        <div class="mb-3">
            <strong>Description:</strong> ${treatment.Description}
        </div>
        <div class="mb-3">
            <strong>Cost:</strong> Rs. ${treatment.Cost}
        </div>
        ${treatment.Notes ? `
            <div class="mb-3">
                <strong>Notes:</strong> ${treatment.Notes}
            </div>
        ` : ''}
    `;
    
    detailsModal.show();
}

function downloadReport(id) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Treatment report downloaded successfully!'
    });
}

function printDetails() {
    window.print();
}
</script>
@endsection