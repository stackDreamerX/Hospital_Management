

@extends('patient_layout')
@section('content')

<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Appointments</h6>
                            <h3 class="mb-0">{{ $appointmentCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-calendar-check fa-2x text-primary opacity-50"></i>
                    </div>
                    <small class="text-muted">{{ $pendingAppointments ?? 0 }} pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Treatments</h6>
                            <h3 class="mb-0">{{ $treatmentCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-procedures fa-2x text-success opacity-50"></i>
                    </div>
                    <small class="text-muted">{{ $activeTreatments ?? 0 }} active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Lab Tests</h6>
                            <h3 class="mb-0">{{ $labTestCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-flask fa-2x text-warning opacity-50"></i>
                    </div>
                    <small class="text-muted">{{ $pendingTests ?? 0 }} pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Prescriptions</h6>
                            <h3 class="mb-0">{{ $prescriptionCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-pills fa-2x text-danger opacity-50"></i>
                    </div>
                    <small class="text-muted">{{ $activePrescriptions ?? 0 }} active</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upcoming Appointments</h5>
                        <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
                        <div class="table-responsive">
                            <table class="table">
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
                                        <td>{{ $appointment['Date'] }}</td>
                                        <td>{{ $appointment['Time'] }}</td>
                                        <td>{{ $appointment['DoctorName'] }}</td>
                                        <td>
                                            <span class="badge bg-{{
                                                $appointment['Status'] == 'Approved' ? 'success' :
                                                ($appointment['Status'] == 'Pending' ? 'warning' : 'danger')
                                            }}">
                                                {{ $appointment['Status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">No upcoming appointments</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Lab Results -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Lab Results</h5>
                        <a href="{{ route('patient.lab') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($recentLabResults) && count($recentLabResults) > 0)
                        <div class="table-responsive">
                            <table class="table">
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
                                        <td>{{ $test['Date'] }}</td>
                                        <td>{{ $test['TestName'] }}</td>
                                        <td>{{ $test['Result'] ?? 'Pending' }}</td>
                                        <td>
                                            <span class="badge bg-{{
                                                $test['Status'] == 'Completed' ? 'success' : 'warning'
                                            }}">
                                                {{ $test['Status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center mb-0">No recent lab results</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Treatments -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Active Treatments</h5>
                <a href="{{ route('patient.treatments') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
        <div class="card-body">
            @if(isset($activeTreatmentsList) && count($activeTreatmentsList) > 0)
                <div class="table-responsive">
                    <table class="table">
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
                                <td>{{ $treatment['DoctorName'] }}</td>
                                <td>{{ $treatment['StartDate'] }}</td>
                                <td>{{ $treatment['Progress'] }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $treatment['Status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center mb-0">No active treatments</p>
            @endif
        </div>
    </div>
</div>

@endsection