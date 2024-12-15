@extends('patient_layout')
@section('content')

<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending Tests</h6>
                            <h2 class="mb-0">{{ $pendingTests }}</h2>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Completed Tests</h6>
                            <h2 class="mb-0">{{ $completedTests }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Tests List -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Lab Tests</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search tests...">
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
                            <th>Date</th>
                            <th>Time</th>
                            <th>Test Type</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labTests as $test)
                        <tr>
                            <td>{{ $test['LaboratoryDate'] }}</td>
                            <td>{{ $test['LaboratoryTime'] }}</td>
                            <td>{{ $test['LaboratoryTypeName'] }}</td>
                            <td>{{ $test['DoctorName'] }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $test['Status'] == 'Completed' ? 'success' : 
                                    ($test['Status'] == 'Pending' ? 'warning' : 'info') 
                                }}">
                                    {{ $test['Status'] }}
                                </span>
                            </td>
                            <td>
                                {{ number_format($test['TotalPrice']) }}
                                <span class="badge bg-{{ $test['PaymentStatus'] == 'Paid' ? 'success' : 'warning' }}">
                                    {{ $test['PaymentStatus'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewDetails({{ json_encode($test) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($test['Status'] == 'Completed' && $test['Report'])
                                        <button class="btn btn-primary" 
                                                onclick="downloadReport({{ $test['LaboratoryID'] }})">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No lab tests found</td>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lab Test Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

function viewDetails(test) {
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="mb-3">
            <strong>Test Type:</strong> ${test.LaboratoryTypeName}
        </div>
        <div class="mb-3">
            <strong>Doctor:</strong> ${test.DoctorName}
        </div>
        <div class="mb-3">
            <strong>Date & Time:</strong> ${test.LaboratoryDate} ${test.LaboratoryTime}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> ${test.Status}
        </div>
        <div class="mb-3">
            <strong>Price:</strong> ${test.TotalPrice}
            <span class="badge bg-${test.PaymentStatus == 'Paid' ? 'success' : 'warning'}">
                ${test.PaymentStatus}
            </span>
        </div>
        ${test.Result ? `
            <div class="mb-3">
                <strong>Result:</strong> ${test.Result}
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
        text: 'Report downloaded successfully!'
    });
}
</script>
@endsection
