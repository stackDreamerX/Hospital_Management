@extends('patient_layout')
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
                            <h6 class="text-muted mb-1">Appointments</h6>
                            <h3 class="mb-0">{{ $appointmentCount ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-primary bg-opacity-10">
                            <i class="fas fa-calendar-check fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-clock me-1"></i> {{ $pendingAppointments ?? 0 }} pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Treatments</h6>
                            <h3 class="mb-0">{{ $treatmentCount ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="fas fa-procedures fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-heartbeat me-1"></i> {{ $activeTreatments ?? 0 }} active</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Lab Tests</h6>
                            <h3 class="mb-0">{{ $labTestCount ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-warning bg-opacity-10">
                            <i class="fas fa-flask fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-hourglass-half me-1"></i> {{ $pendingTests ?? 0 }} pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Prescriptions</h6>
                            <h3 class="mb-0">{{ $prescriptionCount ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-danger bg-opacity-10">
                            <i class="fas fa-pills fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted"><i class="fas fa-prescription me-1"></i> {{ $activePrescriptions ?? 0 }} active</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments and Lab Results -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt text-primary me-2"></i>Upcoming Appointments</h5>
                        <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td><i class="far fa-calendar me-1"></i> {{ $appointment['Date'] }}</td>
                                        <td><i class="far fa-clock me-1"></i> {{ $appointment['Time'] }}</td>
                                        <td><i class="fas fa-user-md me-1"></i> {{ $appointment['DoctorName'] }}</td>
                                        <td>
                                            <span class="badge bg-{{
                                                $appointment['Status'] == 'Approved' ? 'success' :
                                                ($appointment['Status'] == 'Pending' ? 'warning' : 'danger')
                                            }} rounded-pill">
                                                {{ $appointment['Status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No upcoming appointments</p>
                            <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Schedule New Appointment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Lab Results -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-flask text-warning me-2"></i>Recent Lab Results</h5>
                        <a href="{{ route('patient.lab') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($recentLabResults) && count($recentLabResults) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Test</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLabResults as $test)
                                    <tr>
                                        <td><i class="far fa-calendar me-1"></i> {{ $test['Date'] }}</td>
                                        <td>{{ $test['TestName'] }}</td>
                                        <td>{{ $test['Result'] ?? 'Pending' }}</td>
                                        <td>
                                            <span class="badge bg-{{
                                                $test['Status'] == 'Completed' ? 'success' : 'warning'
                                            }} rounded-pill">
                                                {{ $test['Status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-vial fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent lab results</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Treatments -->
    <div class="card border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-procedures text-success me-2"></i>Active Treatments</h5>
                <a href="{{ route('patient.treatments') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
        <div class="card-body">
            @if(isset($activeTreatmentsList) && count($activeTreatmentsList) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Treatment</th>
                                <th>Doctor</th>
                                <th>Start Date</th>
                                <th>Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeTreatmentsList as $treatment)
                            <tr>
                                <td>{{ $treatment['Name'] }}</td>
                                <td><i class="fas fa-user-md me-1"></i> {{ $treatment['DoctorName'] }}</td>
                                <td><i class="far fa-calendar-plus me-1"></i> {{ $treatment['StartDate'] }}</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                            style="width: {{ (int)str_replace('%', '', $treatment['Progress']) }}%" 
                                            aria-valuenow="{{ (int)str_replace('%', '', $treatment['Progress']) }}" 
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">{{ $treatment['Progress'] }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning rounded-pill">{{ $treatment['Status'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No active treatments</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection