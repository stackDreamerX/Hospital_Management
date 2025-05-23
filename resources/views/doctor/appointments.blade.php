@extends('doctor_layout');

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


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
  margin: 1.75rem auto;
  pointer-events: auto;
  max-width: 500px;
}

.modal-dialog.modal-lg {
  max-width: 800px;
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  background-color: #fff;
  border: none;
  border-radius: 0.5rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1rem;
  border-bottom: 1px solid #dee2e6;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
}

/* Enhanced Modal Styling */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 1.5rem;
    border-bottom: none;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
}

.modal-header .btn-close {
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    opacity: 1;
    padding: 0.6rem;
    margin: -0.5rem -0.5rem -0.5rem auto;
    transition: all 0.3s ease;
}

.modal-header .btn-close:hover {
    background-color: rgba(255, 255, 255, 0.8);
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
}

.modal-footer .btn {
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.modal-footer .btn-secondary {
    background-color: #f8f9fa;
    color: var(--text-color);
    border: 1px solid #ddd;
}

.modal-footer .btn-secondary:hover {
    background-color: #e9ecef;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

/* Appointment details styling */
#appointmentDetails p {
    margin-bottom: 1rem;
    padding: 0.8rem 1rem;
    background-color: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

#appointmentDetails strong {
    color: var(--primary-dark);
    font-weight: 600;
    min-width: 100px;
    display: inline-block;
}

/* Animation for modal */
.modal.fade .modal-dialog {
    transform: scale(0.95) translateY(-20px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Enhance the view button */
.view-details-btn {
    border-radius: 8px;
    padding: 0.5rem 0.8rem;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #2bb0ed 0%, #3f8cff 100%);
    border: none;
    box-shadow: 0 4px 10px rgba(63, 140, 255, 0.2);
}

.view-details-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(63, 140, 255, 0.3);
}

.view-details-btn i {
    margin-right: 5px;
}
</style>

@section('content')

@if(session('error'))
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif


<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Appointments</h5>
                    <h2 class="mb-0">{{ $pendingCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Today's Appointments</h5>
                    <h2 class="mb-0">{{ $todayCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">My Appointments</h5>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <!-- Search Input -->
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i> <!-- Icon Search -->
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search appointments...">
                    </div>

                    <!-- Reload Button -->
                    <button class="btn btn-outline-secondary ms-3" id="reloadButton">
                        <i class="fas fa-sync"></i> Reload
                    </button>
                </div>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>Contact</th>
                            <th>Reason</th>
                            <th>DoctorNotes</th> <!-- Hiển thị DoctorNotes -->
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr class="{{ $appointment->AppointmentDate === date('Y-m-d') ? 'table-warning' : '' }}">
                            <td>{{ $appointment['AppointmentDate'] }}</td>
                            <td>{{ $appointment['AppointmentTime'] }}</td>
                            <td>{{ $appointment->user->FullName ?? 'No name provided' }}</td> <!-- Patient Name -->
                            <td>
                                <div>{{ $appointment->user->PhoneNumber ?? 'No phone number' }}</div>
                                <small class="text-muted">{{ $appointment->user->Email ?? 'No email provided' }}</small>
                            </td>
                            <td>{{ $appointment['Reason'] }}</td>
                            <td>{{ $appointment['DoctorNotes'] ?? 'No notes provided' }}</td> <!-- DoctorNotes -->
                            <td>
                                <span class="badge bg-{{
                                    strtolower($appointment['Status']) == 'approved' ? 'success' :
                                    (strtolower($appointment['Status']) == 'pending' ? 'warning' :
                                     (strtolower($appointment['Status']) == 'completed' ? 'info' : 'danger'))
                                }}">
                                    {{ $appointment['Status'] }}
                                </span>
                            </td>
                            <td>
                                @if($appointment['Status'] == 'pending')
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-success approve-btn"
                                                data-id="{{ $appointment['AppointmentID'] }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger reject-btn"
                                                data-id="{{ $appointment['AppointmentID'] }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                @elseif(strtolower($appointment['Status']) == 'approved')
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info btn-sm view-details-btn"
                                                data-appointment='{{ json_encode(value: $appointment) }}'>
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="btn btn-success btn-sm complete-btn"
                                                data-id="{{ $appointment['AppointmentID'] }}">
                                            <i class="fas fa-check-double"></i> Complete
                                        </button>
                                    </div>
                                @else
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info btn-sm view-details-btn"
                                                data-appointment='{{ json_encode(value: $appointment) }}'>
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        @if(strtolower($appointment['Status']) == 'completed')
                                            <a href="{{ route('doctor.pharmacy') }}?patient_id={{ $appointment['UserID'] }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-pills"></i> Kê đơn thuốc
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No appointments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2 text-primary"></i>Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentDetails" class="p-2">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const reloadButton = document.getElementById('reloadButton');
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');

    // Add event listeners for buttons
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            updateStatus(this.getAttribute('data-id'), 'approved');
        });
    });

    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            updateStatus(this.getAttribute('data-id'), 'rejected');
        });
    });

    // Add event listener for complete button
    document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            updateStatus(this.getAttribute('data-id'), 'completed');
        });
    });

    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            viewDetails(JSON.parse(this.getAttribute('data-appointment')));
        });
    });

    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });


    reloadButton.addEventListener('click', function() {
        window.location.reload();
    });
});

function updateStatus(appointmentId, status) {
    let title, confirmText, confirmColor;

    if (status === 'completed') {
        title = 'Complete Appointment';
        confirmText = 'Complete';
        confirmColor = '#28a745';
    } else {
        title = 'Add Notes';
        confirmText = status === 'approved' ? 'Approve' : 'Reject';
        confirmColor = status === 'approved' ? '#28a745' : '#dc3545';
    }

    Swal.fire({
        title: title,
        input: 'textarea',
        inputPlaceholder: 'Enter any notes (optional)',
        showCancelButton: true,
        confirmButtonText: confirmText,
        confirmButtonColor: confirmColor
    }).then((result) => {
        if (result.isConfirmed) {
            // Show processing modal
            Swal.fire({
                title: status === 'approved' ? 'Approving...' : (status === 'rejected' ? 'Rejecting...' : 'Completing...'),
                html: 'Please wait while we process your request and send email notification...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const notes = result.value || '';
            const url = `{{ route('doctor.appointments.updateStatus', ['id' => '__id__']) }}`.replace('__id__', appointmentId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    status,
                    notes: notes
                }),
            })
            .then(response => response.json())
            .then(result => {
                if (result.message) {
                    Swal.fire('Thành công', result.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Lỗi', 'Không thể cập nhật trạng thái.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi', 'Đã xảy ra lỗi khi cập nhật trạng thái.', 'error');
            });
        }
    });
}

function viewDetails(appointment) {
    const modalContent = `
        <div class="mb-3">
            <strong>Date:</strong> ${appointment.AppointmentDate}
        </div>
        <div class="mb-3">
            <strong>Time:</strong> ${appointment.AppointmentTime}
        </div>
        <div class="mb-3">
            <strong>Patient Name:</strong> ${appointment.user ? appointment.user.FullName : 'No name provided'}
        </div>
        <div class="mb-3">
            <strong>Contact:</strong> ${appointment.user ? (appointment.user.PhoneNumber || 'No phone') : 'No contact'} ${appointment.user ? (appointment.user.Email || '') : ''}
        </div>
        <div class="mb-3">
            <strong>Reason:</strong> ${appointment.Reason}
        </div>
        <div class="mb-3">
            <strong>Symptoms:</strong> ${appointment.Symptoms || 'No symptoms provided'}
        </div>
        <div class="mb-3">
            <strong>Notes:</strong> ${appointment.Notes || 'No additional notes'}
        </div>
        <div class="mb-3">
            <strong>Doctor Notes:</strong> ${appointment.DoctorNotes || 'No notes provided'}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> ${appointment.Status}
        </div>
    `;

    document.getElementById('appointmentDetails').innerHTML = modalContent;
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}


function rejectAppointment(appointmentId) {
    Swal.fire({
        title: 'Reject Appointment',
        input: 'textarea',
        inputPlaceholder: 'Enter reason for rejection...',
        showCancelButton: true,
        confirmButtonText: 'Reject',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateStatus(appointmentId, 'Rejected', result.value);
        }
    });
}
</script>
@endsection



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
