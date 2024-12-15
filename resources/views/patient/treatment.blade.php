@extends('patient_layout')
@section('content')

<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Paid Amount</h6>
                            <h2 class="mb-0">Rs. {{ number_format($paidAmount) }}</h2>
                        </div>
                        <i class="fas fa-credit-card fa-2x opacity-50"></i>
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
                            <th>Status</th>
                            <th>Progress</th>
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
                                    ($treatment['Status'] == 'Ongoing' ? 'warning' : 'info') 
                                }}">
                                    {{ $treatment['Status'] }}
                                </span>
                            </td>
                            <td>{{ Str::limit($treatment['Progress'], 30) }}</td>
                            <td>
                                Rs. {{ number_format($treatment['Cost']) }}
                                <span class="badge bg-{{ 
                                    $treatment['PaymentStatus'] == 'Paid' ? 'success' : 'warning' 
                                }}">
                                    {{ $treatment['PaymentStatus'] }}
                                </span>
                            </td>
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
                            <td colspan="7" class="text-center">No treatments found</td>
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
            <strong>Status:</strong> 
            <span class="badge bg-${treatment.Status == 'Completed' ? 'success' : 'warning'}">
                ${treatment.Status}
            </span>
        </div>
        <div class="mb-3">
            <strong>Description:</strong> ${treatment.Description}
        </div>
        <div class="mb-3">
            <strong>Progress:</strong> ${treatment.Progress}
        </div>
        <div class="mb-3">
            <strong>Cost:</strong> Rs. ${treatment.Cost}
            <span class="badge bg-${treatment.PaymentStatus == 'Paid' ? 'success' : 'warning'}">
                ${treatment.PaymentStatus}
            </span>
        </div>
        <div class="mb-3">
            <strong>Lab Tests:</strong> ${treatment.LabTests.join(', ')}
        </div>
        <div class="mb-3">
            <strong>Medications:</strong> ${treatment.Medications.join(', ')}
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
    // Send to server
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
