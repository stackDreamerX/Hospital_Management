@extends('patient_layout')
@section('content')

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
                            <h2 class="mb-0">Rs. {{ number_format($totalSpent) }}</h2>
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
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Prescriptions</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search prescriptions...">
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
                            <td>Rs. {{ number_format($prescription['TotalAmount']) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $prescription['Status'] == 'Completed' ? 'success' : 'warning' 
                                }}">
                                    {{ $prescription['Status'] }}
                                </span>
                                <span class="badge bg-{{ 
                                    $prescription['PaymentStatus'] == 'Paid' ? 'success' : 'warning' 
                                }}">
                                    {{ $prescription['PaymentStatus'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewDetails({{ json_encode($prescription) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary" onclick="downloadPrescription({{ $prescription['PrescriptionID'] }})">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
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

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPrescription()">
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

function viewDetails(prescription) {
    const content = document.getElementById('detailsContent');
    
    let medicinesList = prescription.Items.map(item => `
        <tr>
            <td>${item.Name}</td>
            <td>${item.Dosage}</td>
            <td>${item.Frequency}</td>
            <td>${item.Duration}</td>
            <td>${item.Quantity}</td>
            <td>Rs. ${item.Price}</td>
        </tr>
    `).join('');

    content.innerHTML = `
        <div class="mb-3">
            <strong>Doctor:</strong> ${prescription.DoctorName}
        </div>
        <div class="mb-3">
            <strong>Date:</strong> ${prescription.Date}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> 
            <span class="badge bg-${prescription.Status == 'Completed' ? 'success' : 'warning'}">
                ${prescription.Status}
            </span>
            <span class="badge bg-${prescription.PaymentStatus == 'Paid' ? 'success' : 'warning'}">
                ${prescription.PaymentStatus}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Dosage</th>
                        <th>Frequency</th>
                        <th>Duration</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    ${medicinesList}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
                        <td><strong>Rs. ${prescription.TotalAmount}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        ${prescription.Notes ? `
            <div class="mt-3">
                <strong>Notes:</strong> ${prescription.Notes}
            </div>
        ` : ''}
    `;
    
    detailsModal.show();
}

function downloadPrescription(id) {
    // Send to server
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Prescription downloaded successfully!'
    });
}

function printPrescription() {
    window.print();
}
</script>
@endsection
