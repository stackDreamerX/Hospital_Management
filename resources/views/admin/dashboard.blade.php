@extends('admin_layout');
@section('admin_content')

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .status-online {
            color: green;
        }
        .status-offline {
            color: red;
        }
        .chart-placeholder {
            background: #f5f5f5;
            border: 1px dashed #ddd;
            height: 200px;
            position: relative;
            width: 100%;      
            height: 300px;     
            overflow: hidden;  
        }

        .chart-placeholder img {
            object-fit: cover; 
            width: 100%;
            height: 100%;
        }

        .rotate-text {
            transform: rotate(-90deg);
            white-space: nowrap; 
            transform-origin: left bottom;
        }

        input::placeholder,
    select::placeholder {
        color: #6c757d !important; 
        opacity: 1 !important;
    }

   
    select option:first-child {
        color: #6c757d;
    }

  
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: inherit !important;
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        transition: background-color 5000s ease-in-out 0s;
    }

   
    input[type="password"]:placeholder-shown {
        font-family: inherit !important;
    }

    
    input[type="password"]:not(:placeholder-shown) {
        font-family: password !important;
    }

    </style>

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