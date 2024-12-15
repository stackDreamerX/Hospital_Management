@extends('doctor_layout');
@section('content')

<div class="container mt-4">
    <!-- Low Stock Alert -->
    @if($lowStockMedicines->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Low Stock Alert!</h5>
        <p>The following medicines are running low or out of stock:</p>
        <ul class="mb-0">
            @foreach($lowStockMedicines as $medicine)
            <li>
                {{ $medicine['Name'] }} ({{ $medicine['Stock'] }} remaining)
                <button class="btn btn-sm btn-warning ms-2" 
                        onclick="reportLowStock({{ $medicine['MedicineID'] }}, '{{ $medicine['Name'] }}')">
                    <i class="fas fa-bell"></i> Report
                </button>
            </li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Create New Prescription -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Create New Prescription</h5>
        </div>
        <div class="card-body">
            <form id="prescriptionForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-select" id="patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['PatientID'] }}">{{ $patient['FullName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Medicine List -->
                <div id="medicineList">
                    <div class="medicine-item border rounded p-3 mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Medicine</label>
                                <select class="form-select medicine-select" required>
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines->where('Stock', '>', 0) as $medicine)
                                        <option value="{{ $medicine['MedicineID'] }}" 
                                                data-price="{{ $medicine['Price'] }}"
                                                data-stock="{{ $medicine['Stock'] }}">
                                            {{ $medicine['Name'] }} ({{ $medicine['Type'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Dosage</label>
                                <input type="text" class="form-control" placeholder="e.g., 500mg" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Frequency</label>
                                <input type="text" class="form-control" placeholder="e.g., 3x daily" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" placeholder="e.g., 5 days" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control quantity-input" min="1" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-danger btn-sm remove-medicine" 
                                    onclick="removeMedicine(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary mb-3" onclick="addMedicine()">
                    <i class="fas fa-plus"></i> Add Medicine
                </button>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" rows="3" 
                              placeholder="Additional instructions or notes"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Prescription
                </button>
            </form>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Recent Prescriptions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Medicines</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription['Date'] }}</td>
                            <td>{{ $prescription['PatientName'] }}</td>
                            <td>
                                @foreach($prescription['Items'] as $item)
                                    <div>{{ $item['Name'] }} - {{ $item['Dosage'] }}</div>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $prescription['Status'] == 'Completed' ? 'success' : 
                                    ($prescription['Status'] == 'Pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ $prescription['Status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewPrescription({{ $prescription['PrescriptionID'] }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($prescription['Status'] == 'Pending')
                                    <button class="btn btn-danger" 
                                            onclick="cancelPrescription({{ $prescription['PrescriptionID'] }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No prescriptions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Details Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="prescriptionDetails">
                <!-- Details will be loaded here -->
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
let prescriptionModal;

document.addEventListener('DOMContentLoaded', function() {
    prescriptionModal = new bootstrap.Modal(document.getElementById('prescriptionModal'));

    // Form submission
    document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createPrescription();
    });
});

function addMedicine() {
    const template = document.querySelector('.medicine-item').cloneNode(true);
    template.querySelector('.medicine-select').value = '';
    template.querySelectorAll('input').forEach(input => input.value = '');
    document.getElementById('medicineList').appendChild(template);
}

function removeMedicine(button) {
    if (document.querySelectorAll('.medicine-item').length > 1) {
        button.closest('.medicine-item').remove();
    }
}

function createPrescription() {
    // Collect form data
    const medicines = [];
    document.querySelectorAll('.medicine-item').forEach(item => {
        medicines.push({
            id: item.querySelector('.medicine-select').value,
            dosage: item.querySelectorAll('input')[0].value,
            frequency: item.querySelectorAll('input')[1].value,
            duration: item.querySelectorAll('input')[2].value,
            quantity: item.querySelectorAll('input')[3].value
        });
    });

    // Send to server
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Prescription created successfully!'
    }).then(() => {
        window.location.reload();
    });
}

function viewPrescription(id) {
    prescriptionModal.show();
}

function printPrescription() {
    window.print();
}

function reportLowStock(medicineId, medicineName) {
    Swal.fire({
        title: 'Report Low Stock',
        text: `Report ${medicineName} as low stock to admin?`,
        input: 'textarea',
        inputPlaceholder: 'Add any notes (optional)',
        showCancelButton: true,
        confirmButtonText: 'Send Report',
        showLoaderOnConfirm: true,
        preConfirm: (notes) => {
            return fetch(`/doctor/pharmacy/report-low-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ medicine_id: medicineId, notes: notes })
            })
            .then(response => response.json())
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`)
            })
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Reported!', 'Admin has been notified.', 'success');
        }
    });
}

function cancelPrescription(id) {
    Swal.fire({
        title: 'Cancel Prescription',
        text: 'Are you sure you want to cancel this prescription?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/doctor/pharmacy/prescriptions/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: 'Cancelled' })
            })
            .then(() => {
                Swal.fire('Cancelled!', 'Prescription has been cancelled.', 'success')
                .then(() => window.location.reload());
            });
        }
    });
}
</script>
@endsection 