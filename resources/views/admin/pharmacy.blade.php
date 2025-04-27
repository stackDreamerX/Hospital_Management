@extends('admin_layout')




@section('admin_content')

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

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Pharmacy Management</h2>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Medicines</h6>
                    <h2>{{ count($medicines) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Prescriptions</h6>
                    <h2>{{ $totalPrescriptions }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Low Stock Items</h6>
                    <h2>{{ $lowStockCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Today's Prescriptions</h6>
                    <h2>{{ $todayPrescriptions }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Prescriptions</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchPrescriptionInput" class="form-control" placeholder="Search prescriptions...">
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
                            <th>ID</th>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Total Items</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->PrescriptionID }}</td>
                            <td>{{ $prescription->PrescriptionDate }}</td>
                            <td>{{ $prescription->user->FullName }}</td>
                            <td>{{ $prescription->doctor->user->FullName }}</td>
                            <td>{{ $prescription->prescriptionDetail->count() }}</td>
                            <td>{{ number_format($prescription->TotalPrice) }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $prescription->Status === 'Completed' ? 'success' :
                                    ($prescription->Status === 'Pending' ? 'warning' : 'danger')
                                }}">
                                    Completed
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="viewPrescriptionDetails({{ $prescription->PrescriptionID }})">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="printPrescription({{ $prescription->PrescriptionID }})">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No prescriptions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Details Modal -->
<div class="modal fade" id="prescriptionDetailsModal" tabindex="-1" aria-labelledby="prescriptionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prescriptionDetailsModalLabel">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="prescriptionDetailsContent">
                    <!-- Prescription details will be dynamically loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPrescriptionDetails()">
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
    function viewPrescriptionDetails(id) {
        const url = `{{ route('admin.prescription.show', ['id' => '__id__']) }}`.replace('__id__', id);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const detailsContent = document.getElementById('prescriptionDetailsContent');
                detailsContent.innerHTML = `
                    <p><strong>Date:</strong> ${data.PrescriptionDate}</p>
                    <p><strong>Patient:</strong> ${data.PatientName}</p>
                    <p><strong>Doctor:</strong> ${data.DoctorName}</p>
                    <p><strong>Total Price:</strong> ${data.TotalPrice}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-${data.Status === 'Completed' ? 'success' : 'warning'}">
                            ${data.Status} 
                        </span>
                    </p>
                    <p><strong>Items:</strong></p>
                    <ul>
                        ${data.Items.map(item => `
                            <li>${item.MedicineName} - ${item.Dosage}, ${item.Quantity} units @ ${item.Price}</li>
                        `).join('')}
                    </ul>
                `;

                const prescriptionDetailsModal = new bootstrap.Modal(document.getElementById('prescriptionDetailsModal'));
                prescriptionDetailsModal.show();
            })
            .catch(error => {
                console.error('Error fetching prescription details:', error);
                Swal.fire('Error', 'Failed to load prescription details', 'error');
            });
    }

    function printPrescriptionDetails() {
        const content = document.getElementById('prescriptionDetailsContent').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Prescription Details</title></head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endsection


