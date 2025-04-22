@extends('doctor_layout');


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-4" 
                style="font-family: 'Poppins', sans-serif; 
                    font-size: 32px; 
                    color: #ffff; 
                    letter-spacing: 0.5px; 
                    font-weight: 600; 
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.05);">
                Welcome back, {{ auth()->user()->FullName ?? 'Patient' }}
            </h4>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Today's Appointments</h6>
                            <h3 class="mb-0">{{ $todayAppointments }}</h3>
                        </div>
                        <div class="icon-circle bg-primary bg-opacity-10">
                            <i class="fas fa-calendar-day fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-calendar me-1"></i> {{ date('d M Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Patients</h6>
                            <h3 class="mb-0">{{ $activePatients }}</h3>
                        </div>
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-user-check me-1"></i> Active under your care</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pending Labs</h6>
                            <h3 class="mb-0">{{ $pendingLabs }}</h3>
                        </div>
                        <div class="icon-circle bg-warning bg-opacity-10">
                            <i class="fas fa-flask fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-hourglass-half me-1"></i> Awaiting results</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Treatments</h6>
                            <h3 class="mb-0">{{ $activeTreatments }}</h3>
                        </div>
                        <div class="icon-circle bg-info bg-opacity-10">
                            <i class="fas fa-procedures fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-heartbeat me-1"></i> Ongoing treatments</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-md-8 mb-4">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt text-primary me-2"></i>Today's Schedule</h5>
                        <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <td><i class="far fa-clock me-1"></i> {{ $schedule['Time'] }}</td>
                                    <td><i class="far fa-user me-1"></i> {{ $schedule['PatientName'] }}</td>
                                    <td>{{ $schedule['Type'] }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $schedule['Status'] == 'Completed' ? 'success' :
                                            ($schedule['Status'] == 'Pending' ? 'warning' : 'info')
                                        }} rounded-pill">
                                            {{ $schedule['Status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-schedule="{{ json_encode($schedule) }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No appointments scheduled for today</p>
                                    </td>
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
            <div class="card border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history text-info me-2"></i>Recent Activities</h5>
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
            <div class="card border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-tasks text-warning me-2"></i>Pending Tasks</h5>
                </div>
                <div class="card-body">
                    @if(count($pendingTasks) > 0)
                        <div class="list-group">
                            @foreach($pendingTasks as $task)
                            <div class="list-group-item list-group-item-action border-0 mb-2 rounded">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $task['Title'] }}</h6>
                                    <small class="text-danger"><i class="fas fa-clock me-1"></i> {{ $task['DueDate'] }}</small>
                                </div>
                                <p class="mb-1">{{ $task['Description'] }}</p>
                                <small class="text-muted"><i class="fas fa-tag me-1"></i> {{ $task['Type'] }}</small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No pending tasks</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Patient Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-line text-success me-2"></i>Patient Statistics</h5>
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

    // Set up event listeners for view details buttons
    document.querySelectorAll('.btn-info').forEach(button => {
        button.addEventListener('click', function() {
            const schedule = JSON.parse(this.getAttribute('data-schedule'));
            viewDetails(schedule);
        });
    });

    // Initialize patient statistics chart
    const ctx = document.getElementById('patientStatsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Patient Visits',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#089bab',
                backgroundColor: 'rgba(8, 155, 171, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
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
