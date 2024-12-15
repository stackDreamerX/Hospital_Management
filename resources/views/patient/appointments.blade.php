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
                            <h6 class="mb-0">Pending Appointments</h6>
                            <h2 class="mb-0">{{ $pendingCount }}</h2>
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
                            <h6 class="mb-0">Approved Appointments</h6>
                            <h2 class="mb-0">{{ $approvedCount }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Appointment -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Request New Appointment</h5>
        </div>
        <div class="card-body">
            <form id="appointmentForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Preferred Date</label>
                        <input type="date" class="form-control" id="appointment_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Preferred Time</label>
                        <input type="time" class="form-control" id="appointment_time" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason for Visit</label>
                    <input type="text" class="form-control" id="reason" 
                           placeholder="e.g., Regular checkup, Follow-up, etc." required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Symptoms</label>
                    <textarea class="form-control" id="symptoms" rows="2" 
                              placeholder="Describe your symptoms" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" rows="2" 
                              placeholder="Any additional information"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Request Appointment
                </button>
            </form>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Appointments</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search appointments...">
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
                            <th>Doctor</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment['AppointmentDate'] }}</td>
                            <td>{{ $appointment['AppointmentTime'] }}</td>
                            <td>{{ $appointment['DoctorName'] ?? 'Not assigned yet' }}</td>
                            <td>{{ $appointment['Reason'] }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $appointment['Status'] == 'Approved' ? 'success' : 
                                    ($appointment['Status'] == 'Pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ $appointment['Status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" 
                                            onclick="viewDetails({{ json_encode($appointment) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($appointment['Status'] == 'Pending')
                                        <button class="btn btn-primary" 
                                                onclick="editAppointment({{ json_encode($appointment) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger" 
                                                onclick="cancelAppointment({{ $appointment['AppointmentID'] }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No appointments found</td>
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
                <h5 class="modal-title">Appointment Details</h5>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" id="edit_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" id="edit_reason" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Symptoms</label>
                        <textarea class="form-control" id="edit_symptoms" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateAppointment()">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let detailsModal, editModal;

document.addEventListener('DOMContentLoaded', function() {
    detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
    editModal = new bootstrap.Modal(document.getElementById('editModal'));

    // Form submission
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createAppointment();
    });

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

function createAppointment() {
    const data = {
        appointment_date: document.getElementById('appointment_date').value,
        appointment_time: document.getElementById('appointment_time').value,
        reason: document.getElementById('reason').value,
        symptoms: document.getElementById('symptoms').value,
        notes: document.getElementById('notes').value
    };

    // Send to server
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Appointment request submitted successfully!'
    }).then(() => {
        document.getElementById('appointmentForm').reset();
        window.location.reload();
    });
}

function viewDetails(appointment) {
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="mb-3">
            <strong>Date & Time:</strong> ${appointment.AppointmentDate} ${appointment.AppointmentTime}
        </div>
        <div class="mb-3">
            <strong>Doctor:</strong> ${appointment.DoctorName || 'Not assigned yet'}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> ${appointment.Status}
        </div>
        <div class="mb-3">
            <strong>Reason:</strong> ${appointment.Reason}
        </div>
        <div class="mb-3">
            <strong>Symptoms:</strong> ${appointment.Symptoms}
        </div>
        ${appointment.Notes ? `
            <div class="mb-3">
                <strong>Notes:</strong> ${appointment.Notes}
            </div>
        ` : ''}
        ${appointment.AdminNotes ? `
            <div class="mb-3">
                <strong>Admin Notes:</strong> ${appointment.AdminNotes}
            </div>
        ` : ''}
    `;
    detailsModal.show();
}

function editAppointment(appointment) {
    document.getElementById('edit_id').value = appointment.AppointmentID;
    document.getElementById('edit_date').value = appointment.AppointmentDate;
    document.getElementById('edit_time').value = appointment.AppointmentTime;
    document.getElementById('edit_reason').value = appointment.Reason;
    document.getElementById('edit_symptoms').value = appointment.Symptoms;
    document.getElementById('edit_notes').value = appointment.Notes || '';
    
    editModal.show();
}

function updateAppointment() {
    const id = document.getElementById('edit_id').value;
    const data = {
        appointment_date: document.getElementById('edit_date').value,
        appointment_time: document.getElementById('edit_time').value,
        reason: document.getElementById('edit_reason').value,
        symptoms: document.getElementById('edit_symptoms').value,
        notes: document.getElementById('edit_notes').value
    };

    // Send to server
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Appointment updated successfully!'
    }).then(() => {
        editModal.hide();
        window.location.reload();
    });
}

function cancelAppointment(id) {
    Swal.fire({
        title: 'Cancel Appointment',
        text: 'Are you sure you want to cancel this appointment?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send to server
            Swal.fire('Cancelled!', 'Your appointment has been cancelled.', 'success')
            .then(() => window.location.reload());
        }
    });
}
</script>
@endsection 