@extends('admin_layout');

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


@section('admin_content')

    

    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Doctors</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($doctors ?? []) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-md fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Patients</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($patients ?? []) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Today's Appointments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayAppointments ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Available Beds</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBeds ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bed fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Recent Appointments -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Appointments</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAppointments ?? [] as $appointment)
                                    <tr>
                                        <td>{{ $appointment['PatientName'] }}</td>
                                        <td>{{ $appointment['DoctorName'] }}</td>
                                        <td>{{ $appointment['AppointmentDate'] }}</td>
                                        <td>{{ $appointment['AppointmentTime'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment['Status'] == 'pending' ? 'warning' : 
                                                ($appointment['Status'] == 'approved' ? 'success' : 
                                                ($appointment['Status'] == 'completed' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($appointment['Status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No recent appointments</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ward Status -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ward Status</h6>
                    </div>
                    <div class="card-body">
                        @forelse($wards ?? [] as $ward)
                        <div class="mb-3">
                            <h4 class="small font-weight-bold">
                                {{ $ward['WardName'] }} 
                                <span class="float-end">{{ floor(($ward['CurrentOccupancy'] / $ward['Capacity']) * 100) }}%</span>
                            </h4>
                            <div class="progress">
                                <div class="progress-bar bg-{{ 
                                    $ward['CurrentOccupancy'] >= $ward['Capacity'] ? 'danger' : 
                                    ($ward['CurrentOccupancy'] >= $ward['Capacity'] * 0.8 ? 'warning' : 'success') 
                                }}" 
                                role="progressbar" 
                                style="width: {{ ($ward['CurrentOccupancy'] / $ward['Capacity']) * 100 }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center">No ward information available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
