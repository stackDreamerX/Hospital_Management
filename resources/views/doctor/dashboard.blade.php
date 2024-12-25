@extends('doctor_layout');


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


@section('content')

<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Today's Appointments</h6>
                            <h2 class="mb-0">{{ $todayAppointments }}</h2>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Active Patients</h6>
                            <h2 class="mb-0">{{ $activePatients }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending Labs</h6>
                            <h2 class="mb-0">{{ $pendingLabs }}</h2>
                        </div>
                        <i class="fas fa-flask fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Active Treatments</h6>
                            <h2 class="mb-0">{{ $activeTreatments }}</h2>
                        </div>
                        <i class="fas fa-procedures fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Today's Schedule</h5>
                        <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todaySchedule as $schedule)
                                <tr>
                                    <td>{{ $schedule['Time'] }}</td>
                                    <td>{{ $schedule['PatientName'] }}</td>
                                    <td>{{ $schedule['Type'] }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $schedule['Status'] == 'Completed' ? 'success' :
                                            ($schedule['Status'] == 'Pending' ? 'warning' : 'info')
                                        }}">
                                            {{ $schedule['Status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewDetails({{ json_encode($schedule) }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No appointments scheduled for today</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-{{ $activity['Type'] == 'appointment' ? 'primary' :
                                                         ($activity['Type'] == 'lab' ? 'warning' :
                                                         ($activity['Type'] == 'treatment' ? 'info' : 'success')) }}">
                                <i class="fas fa-{{ $activity['Type'] == 'appointment' ? 'calendar' :
                                                  ($activity['Type'] == 'lab' ? 'flask' :
                                                  ($activity['Type'] == 'treatment' ? 'procedures' : 'prescription')) }}">
                                </i>
                            </div>
                            <div class="timeline-content">
                                <p class="mb-0">{{ $activity['Description'] }}</p>
                                <small class="text-muted">{{ $activity['Time'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Tasks -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Pending Tasks</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($pendingTasks as $task)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $task['Title'] }}</h6>
                                <small class="text-danger">{{ $task['DueDate'] }}</small>
                            </div>
                            <p class="mb-1">{{ $task['Description'] }}</p>
                            <small class="text-muted">{{ $task['Type'] }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Patient Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="patientStatsChart"></canvas>
                </div>
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

@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let detailsModal;

document.addEventListener('DOMContentLoaded', function() {
    detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));

    // Initialize patient statistics chart
    const ctx = document.getElementById('patientStatsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Patient Visits',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

function viewDetails(schedule) {
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="mb-3">
            <strong>Patient:</strong> ${schedule.PatientName}
        </div>
        <div class="mb-3">
            <strong>Time:</strong> ${schedule.Time}
        </div>
        <div class="mb-3">
            <strong>Type:</strong> ${schedule.Type}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> ${schedule.Status}
        </div>
        <div class="mb-3">
            <strong>Notes:</strong> ${schedule.Notes || 'No notes available'}
        </div>
    `;
    detailsModal.show();
}
</script>
@endsection



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
