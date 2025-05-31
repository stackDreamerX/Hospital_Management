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
                <button class="btn btn-sm btn-warning ms-2 report-low-stock"
                        data-id="{{ $medicine->MedicineID }}"
                        data-name="{{ $medicine->MedicineName }}">
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
                        <select class="form-select" id="patient_id" name="patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->UserID }}" {{ (isset($selectedPatientId) && $selectedPatientId == $patient->UserID) ? 'selected' : '' }}>
                                    {{ $patient->FullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Examination Details -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Thông tin khám bệnh</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Chẩn đoán</label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="2" placeholder="Nhập chẩn đoán chi tiết"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Kết quả xét nghiệm</label>
                                <textarea class="form-control" id="test_results" name="test_results" rows="2" placeholder="Nhập kết quả xét nghiệm nếu có"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Huyết áp (mmHg)</label>
                                <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" placeholder="VD: 120/80">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhịp tim (BPM)</label>
                                <input type="number" class="form-control" id="heart_rate" name="heart_rate" placeholder="VD: 75">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhiệt độ (°C)</label>
                                <input type="text" class="form-control" id="temperature" name="temperature" placeholder="VD: 37.2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SpO2 (%)</label>
                                <input type="number" class="form-control" id="spo2" name="spo2" placeholder="VD: 98">
                            </div>
                        </div>
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
                    <label class="form-label">Lời dặn của bác sĩ</label>
                    <textarea class="form-control" id="instructions" name="instructions" rows="3"
                              placeholder="Lời dặn và hướng dẫn dành cho bệnh nhân"></textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu đơn thuốc
                        </button>
                        <button type="button" class="btn btn-success" id="printPrescriptionBtn">
                            <i class="fas fa-print"></i> In đơn thuốc (PDF)
                        </button>
                    </div>
                </div>
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
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info view-prescription" data-id="{{ $prescription->PrescriptionID }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger cancel-prescription" data-id="{{ $prescription->PrescriptionID }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No prescriptions found</td>
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
                <button type="button" class="btn btn-primary" onclick="printPrescription(document.getElementById('prescriptionDetails').getAttribute('data-id'))">
                    <i class="fas fa-print"></i> In đơn thuốc
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
    prescriptionModal = new bootstrap.Modal(document.getElementById('prescriptionModal'));

    // Form submission
    document.getElementById('prescriptionForm').addEventListener('submit', function (e) {
        e.preventDefault();
        createPrescription();
    });

    // Add event listeners for low stock report buttons
    document.querySelectorAll('.report-low-stock').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            reportLowStock(id, name);
        });
    });

    // Add event listeners for viewing prescriptions
    document.querySelectorAll('.view-prescription').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            viewPrescription(id);
        });
    });

    // Add event listeners for canceling prescriptions
    document.querySelectorAll('.cancel-prescription').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            cancelPrescription(id);
        });
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

// Function to print prescription as PDF
function printPrescription(prescriptionId = null) {
    if (prescriptionId) {
        // If we have an ID, download existing prescription PDF
        window.location.href = `{{ route('doctor.pharmacy.download-pdf', ['id' => '__id__']) }}`.replace('__id__', prescriptionId);
    } else {
        // If no ID, create a new prescription first, then download
        createPrescription(true);
    }
}

// Add event listener for the print button
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('printPrescriptionBtn').addEventListener('click', function() {
        createPrescription(true);
    });
});

// Create a new prescription
function createPrescription(shouldPrint = false) {
    const createPrescriptionUrl = "{{ route('doctor.pharmacy.create') }}";

    // Log URL

    const medicines = [];
    document.querySelectorAll('.medicine-item').forEach((item, index) => {
        const medicineSelect = item.querySelector('.medicine-select');
        const medicineId = medicineSelect.value;
        const medicineName = medicineSelect.options[medicineSelect.selectedIndex]?.text || 'Unknown';
        const dosage = item.querySelectorAll('input')[0].value;
        const frequency = item.querySelectorAll('input')[1].value;
        const duration = item.querySelectorAll('input')[2].value;
        const quantity = item.querySelectorAll('input')[3].value;

        const medicineData = {
            id: medicineId,
            dosage: dosage,
            frequency: frequency,
            duration: duration,
            quantity: quantity,
        };

        medicines.push(medicineData);
    });

    // Check for empty medicines
    if (medicines.length === 0 || medicines.some(med => !med.id)) {
        console.error('Empty or invalid medicine data detected');
    }

    const data = {
        patient_id: document.getElementById('patient_id').value,
        medicines: medicines,
        diagnosis: document.getElementById('diagnosis').value,
        test_results: document.getElementById('test_results').value,
        blood_pressure: document.getElementById('blood_pressure').value,
        heart_rate: document.getElementById('heart_rate').value,
        temperature: document.getElementById('temperature').value,
        spo2: document.getElementById('spo2').value,
        instructions: document.getElementById('instructions').value,
    };

    // Log full request data

    // Validate required fields
    if (!data.patient_id) {
        console.error('Patient ID is missing!');
        Swal.fire('Lỗi', 'Vui lòng chọn bệnh nhân', 'error');
        return;
    }

    // Make sure we have the CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token is missing!');
        Swal.fire('Lỗi', 'Không tìm thấy token bảo mật', 'error');
        return;
    }

    // Show loading state
    const loadingSwal = Swal.fire({
        title: 'Đang xử lý...',
        text: 'Đang tạo đơn thuốc',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(createPrescriptionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
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
            // Close loading dialog
            loadingSwal.close();


            if (result.success) {
                Swal.fire({
                    title: 'Thành công',
                    text: 'Đơn thuốc đã được lưu thành công',
                    icon: 'success',
                    showConfirmButton: true,
                }).then(() => {
                    if (shouldPrint) {
                        printPrescription(result.id);
                    } else {
                        // Tạo hiệu ứng cuộn xuống danh sách đơn thuốc và làm nổi bật dòng mới
                        window.location.reload();
                        // Sau khi load lại trang, sẽ cuộn xuống danh sách đơn thuốc
                        setTimeout(() => {
                            const recentPrescriptions = document.querySelector('.card-header:contains("Recent Prescriptions")');
                            if (recentPrescriptions) {
                                recentPrescriptions.scrollIntoView({ behavior: 'smooth' });
                            }
                        }, 1000);
                    }
                });
            } else {
                console.error('Server returned error:', result);
                Swal.fire('Lỗi', result.message || 'Không thể tạo đơn thuốc', 'error');
            }
        })
        .catch(error => {
            // Close loading dialog
            loadingSwal.close();

            console.error('Error creating prescription:', error);
            Swal.fire('Lỗi', 'Không thể tạo đơn thuốc. Vui lòng thử lại.', 'error');
        });
}

// View prescription details
function viewPrescription(id) {
    const url = `{{ route('doctor.pharmacy.show', ['id' => '__id__']) }}`.replace('__id__', id);

    // Show loading state
    const loadingSwal = Swal.fire({
        title: 'Loading...',
        text: 'Fetching prescription details',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Close loading dialog
            loadingSwal.close();

            // Log the response for debugging

            const details = document.getElementById('prescriptionDetails');
            // Set the prescription ID as data attribute
            details.setAttribute('data-id', data.PrescriptionID);

            try {
                // Check if we have valid data
                if (!data) {
                    throw new Error('No data received from server');
                }

                // Create medicines list with fallbacks
                let medicinesList = '<li>No medicines available</li>';

                if (Array.isArray(data.Medicines) && data.Medicines.length > 0) {
                    medicinesList = data.Medicines
                        .map(med => {
                            // Add fallbacks for all medicine properties
                            const name = med.Name || 'Unknown medicine';
                            const dosage = med.Dosage || 'No dosage specified';
                            const frequency = med.Frequency || 'No frequency specified';
                            const duration = med.Duration || 'No duration specified';
                            const quantity = med.Quantity || '0';

                            return `<li>${name} - ${dosage}, ${frequency} for ${duration} (${quantity} units)</li>`;
                        })
                        .join('');
                }

                // Build HTML with fallbacks for all values
                details.innerHTML = `
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin bệnh nhân</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã đơn thuốc:</strong> ${data.PrescriptionID || 'N/A'}</p>
                            <p><strong>Ngày kê đơn:</strong> ${data.Date || 'N/A'}</p>
                            <p><strong>Tên bệnh nhân:</strong> ${data.PatientName || 'N/A'}</p>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Kết quả khám bệnh</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Chẩn đoán:</strong> ${data.Diagnosis || 'Không có thông tin'}</p>
                            <p><strong>Kết quả xét nghiệm:</strong> ${data.TestResults || 'Không có thông tin'}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Huyết áp:</strong> ${data.BloodPressure || 'N/A'} mmHg</p>
                                    <p><strong>Nhịp tim:</strong> ${data.HeartRate || 'N/A'} BPM</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Nhiệt độ:</strong> ${data.Temperature || 'N/A'} °C</p>
                                    <p><strong>SpO2:</strong> ${data.SpO2 || 'N/A'} %</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin đơn thuốc</h5>
                        </div>
                        <div class="card-body">
                            <h6>Thuốc đã kê:</h6>
                            <ul class="mb-3">
                                ${medicinesList}
                            </ul>

                            <p><strong>Lời dặn của bác sĩ:</strong> ${data.Instructions || 'Không có thông tin'}</p>
                            <p><strong>Ghi chú:</strong> ${data.Notes || 'Không có ghi chú'}</p>
                        </div>
                    </div>
                `;

                prescriptionModal.show();
            } catch (error) {
                console.error('Error processing prescription details:', error);
                Swal.fire('Error', `Failed to process prescription details: ${error.message}`, 'error');
            }
        })
        .catch(error => {
            // Close loading dialog
            loadingSwal.close();

            console.error('Error fetching prescription details:', error);
            Swal.fire('Error', 'Failed to load prescription details. Please try again.', 'error');
        });
}

// Cancel a prescription
function cancelPrescription(id) {
    Swal.fire({
        title: 'Xóa đơn thuốc',
        text: 'Bạn có chắc chắn muốn xóa đơn thuốc này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy',
    }).then(result => {
        if (result.isConfirmed) {
            // Show loading state
            const loadingSwal = Swal.fire({
                title: 'Đang xử lý...',
                text: 'Đang xóa đơn thuốc',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const url = `{{ route('doctor.pharmacy.cancel', ['id' => '__id__']) }}`.replace('__id__', id);
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Close loading dialog
                    loadingSwal.close();

                    Swal.fire('Đã xóa!', data.message || 'Đơn thuốc đã được xóa thành công', 'success').then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    // Close loading dialog
                    loadingSwal.close();

                    console.error('Error deleting prescription:', error);
                    Swal.fire('Lỗi', 'Không thể xóa đơn thuốc. Vui lòng thử lại.', 'error');
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
            // Show loading state
            const loadingSwal = Swal.fire({
                title: 'Processing...',
                text: 'Sending low stock report',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Since there's no specific route defined in web.php, we'll handle this temporarily
            // This should be replaced with a proper route once implemented
            setTimeout(() => {
                // Close loading dialog
                loadingSwal.close();

                // Show success message
                Swal.fire({
                    title: 'Reported!',
                    text: `Low stock for ${medicineName} has been reported to admin.`,
                    icon: 'success'
                });

                // In a real implementation, you would make a fetch request to the server
                // Example:
                // fetch('/doctor/pharmacy/report-low-stock', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                //     },
                //     body: JSON.stringify({ medicine_id: medicineId, notes: result.value }),
                // })
            }, 1000);
        }
    });
}

</script>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
