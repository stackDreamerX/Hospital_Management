@extends('doctor_layout');
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
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>Contact</th>
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
                            <td>{{ $appointment['PatientName'] }}</td>
                            <td>
                                <div>{{ $appointment['PatientPhone'] }}</div>
                                <small class="text-muted">{{ $appointment['PatientEmail'] }}</small>
                            </td>
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
                                @if($appointment['Status'] == 'Pending')
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
                                            onclick="viewDetails({{ $appointment['AppointmentID'] }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No appointments found</td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');

    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
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
            // Send to server
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `Appointment ${status.toLowerCase()} successfully!`
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

function viewDetails(appointmentId) {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    
    // Simulate loading appointment details
    document.getElementById('appointmentDetails').innerHTML = `
        <div class="text-center">
            <p><strong>Loading appointment details...</strong></p>
        </div>
    `;
    
    modal.show();
}
</script>
@endsection