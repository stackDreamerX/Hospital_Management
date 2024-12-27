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



</style>

@section('content')

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
                                    $appointment['Status'] == 'approved' ? 'success' :
                                    ($appointment['Status'] == 'pending' ? 'warning' : 'danger')
                                }}">
                                    {{ $appointment['Status'] }}
                                </span>
                            </td>
                            <td>
                                @if($appointment['Status'] == 'pending')
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-success"
                                                onclick="updateStatus({{ $appointment['AppointmentID'] }}, 'Approved')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger"
                                                onclick="updateStatus({{ $appointment['AppointmentID'] }}, 'Rejected')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn-info btn-sm"
                                            onclick="viewDetails({{ $appointment }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentDetails">
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
    Swal.fire({
        title: 'Add Notes',
        input: 'textarea',
        inputPlaceholder: 'Enter any notes (optional)',
        showCancelButton: true,
        confirmButtonText: status === 'Approved' ? 'Approve' : 'Reject',
        confirmButtonColor: status === 'Approved' ? '#28a745' : '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
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
            <strong>Patient Name:</strong> {{ $appointment->user->FullName }}
        </div>
        <div class="mb-3">
            <strong>Contact:</strong> {{$appointment->user->PhoneNumber }} {{$appointment->user->Email}}
        </div>
        <div class="mb-3">
            <strong>Reason:</strong> ${appointment.Reason}
        </div>
        <div class="mb-3">
            <strong>Symptoms:</strong> ${appointment.Symptoms}
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
