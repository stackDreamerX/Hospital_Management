@extends('doctor_layout');


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')


<style>
    .modal {
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }
    
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal-dialog {
        z-index: 1060;
        margin: 30px auto;
    }

    .modal.fade .modal-dialog {
        transform: translate(0, -25%);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translate(0, 0);
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    }

    /* Đảm bảo modal hiển thị trên cùng */
    .modal.show {
        display: block !important;
        padding-right: 17px;
    }

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
</style>
<div class="container mt-4">
    <!-- Low Stock Alert -->
    @if($lowStockMedicines->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Low Stock Alert!</h5>
        <p>The following medicines are running low or out of stock:</p>
        <ul class="mb-0">
            @foreach($lowStockMedicines as $medicine)
            <li>
                {{ $medicine->MedicineName }} ({{ $medicine->Stock }} remaining)
                <button class="btn btn-sm btn-warning ms-2"
                        onclick="reportLowStock({{ $medicine->MedicineID }}, '{{ $medicine->MedicineName }}')">
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
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-select" id="patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->UserID }}">{{ $patient->FullName }}</option>
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
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->MedicineID }}"
                                                data-price="{{ $medicine->UnitPrice }}"
                                                data-stock="{{ $medicine->Stock }}">
                                            {{ $medicine->MedicineName }} ({{ $medicine->Stock }} in stock)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Dosage</label>
                                <input type="text" class="form-control dosage-input" placeholder="e.g., 500mg" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Frequency</label>
                                <input type="text" class="form-control frequency-input" placeholder="e.g., 3x daily" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control duration-input" placeholder="e.g., 5 days" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control quantity-input" min="1" max="{{ $medicine->Stock }}" required>
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
                            <td>{{ $prescription->PrescriptionDate }}</td>
                            <td>{{ $prescription->user->FullName }}</td>
                            <td>
                                @foreach($prescription->prescriptionDetail as $item)
                                    <div>{{ $item->medicine->MedicineName }} - {{ $item->Dosage }}</div>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $prescription->Status == 'Completed' ? 'success' :
                                    ($prescription->Status == 'Pending' ? 'warning' : 'danger')
                                }}">
                                    Completed
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewPrescription({{ $prescription->PrescriptionID }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($prescription->Status == 'Pending')
                                    <button class="btn btn-danger"
                                            onclick="cancelPrescription({{ $prescription->PrescriptionID }})">
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
                <!-- Details will be dynamically loaded here -->
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let prescriptionModal;

  document.addEventListener('DOMContentLoaded', function () {
    const prescriptionModal = new bootstrap.Modal(document.getElementById('prescriptionModal'));

    // Form submission
    document.getElementById('prescriptionForm').addEventListener('submit', function (e) {
        e.preventDefault();
        createPrescription();
    });
});

// Add a new medicine row
function addMedicine() {
    const template = document.querySelector('.medicine-item').cloneNode(true);
    template.querySelector('.medicine-select').value = '';
    template.querySelectorAll('input').forEach(input => input.value = '');
    document.getElementById('medicineList').appendChild(template);
}

// Remove a medicine row
function removeMedicine(button) {
    const medicines = document.querySelectorAll('.medicine-item');
    if (medicines.length > 1) {
        button.closest('.medicine-item').remove();
    }
}

// Create a new prescription
function createPrescription() {
    const createPrescriptionUrl = "{{ route('doctor.pharmacy.create') }}";

    const medicines = [];
    document.querySelectorAll('.medicine-item').forEach(item => {
        medicines.push({
            id: item.querySelector('.medicine-select').value,
            dosage: item.querySelectorAll('input')[0].value,
            frequency: item.querySelectorAll('input')[1].value,
            duration: item.querySelectorAll('input')[2].value,
            quantity: item.querySelectorAll('input')[3].value,
        });
    });

    const data = {
        patient_id: document.getElementById('patient_id').value,
        medicines: medicines,
        notes: document.getElementById('notes').value,
    };

    fetch(createPrescriptionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                Swal.fire('Success', result.message, 'success').then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error creating prescription:', error);
            Swal.fire('Error', 'Failed to create prescription', 'error');
        });
}

// View prescription details
function viewPrescription(id) {
    const url = `{{ route('doctor.pharmacy.show', ['id' => '__id__']) }}`.replace('__id__', id);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const details = document.getElementById('prescriptionDetails');

            // Kiểm tra xem data.medicines có tồn tại và là một mảng
            const medicinesList =
                Array.isArray(data.medicines) && data.medicines.length > 0
                    ? data.medicines
                          .map(
                              med =>
                                  `<li>${med.name} - ${med.dosage}, ${med.frequency} for ${med.duration} (${med.quantity} units)</li>`
                          )
                          .join('')
                    : '<li>No medicines</li>';

            details.innerHTML = `
                <p><strong>Date:</strong> ${data.date || 'N/A'}</p>
                <p><strong>Patient:</strong> ${data.patient_name || 'N/A'}</p>
                <p><strong>Medicines:</strong></p>
                <ul>
                    ${medicinesList}
                </ul>
                <p><strong>Notes:</strong> ${data.notes || 'N/A'}</p>
                <p><strong>Status:</strong> ${data.status || 'N/A'}</p>
            `;

            prescriptionModal.show();
        })
        .catch(error => {
            console.error('Error fetching prescription details:', error);
            Swal.fire('Error', 'Failed to load prescription details', 'error');
        });
}


// Cancel a prescription
function cancelPrescription(id) {
    Swal.fire({
        title: 'Cancel Prescription',
        text: 'Are you sure you want to cancel this prescription?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it',
    }).then(result => {
        if (result.isConfirmed) {
            const url = `/doctor/pharmacy/cancel/${id}`;
            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status: 'Cancelled' }),
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Cancelled!', data.message, 'success').then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error cancelling prescription:', error);
                    Swal.fire('Error', 'Failed to cancel prescription', 'error');
                });
        }
    });
}

// Report low stock
function reportLowStock(medicineId, medicineName) {
    Swal.fire({
        title: 'Report Low Stock',
        text: `Report ${medicineName} as low stock to admin?`,
        input: 'textarea',
        inputPlaceholder: 'Add any notes (optional)',
        showCancelButton: true,
        confirmButtonText: 'Send Report',
    }).then(result => {
        if (result.isConfirmed) {
            const url = `/doctor/pharmacy/report-low-stock`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ medicine_id: medicineId, notes: result.value }),
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Reported!', data.message, 'success');
                })
                .catch(error => {
                    console.error('Error reporting low stock:', error);
                    Swal.fire('Error', 'Failed to report low stock', 'error');
                });
        }
    });
}

</script>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
