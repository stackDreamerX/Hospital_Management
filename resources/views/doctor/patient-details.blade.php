@extends('doctor_layout');
@section('content')

<div class="container mt-4">
    <!-- Patient Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h3>{{ $patient['FullName'] }}</h3>
                <span class="badge bg-info">Last Visit: {{ $patient['LastVisit'] }}</span>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <p><strong>Age:</strong> {{ $patient['Age'] }} years</p>
                    <p><strong>Gender:</strong> {{ $patient['Gender'] }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Phone:</strong> {{ $patient['Phone'] }}</p>
                    <p><strong>Email:</strong> {{ $patient['Email'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#appointments">
                Appointments
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#lab-tests">
                Lab Tests
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#prescriptions">
                Prescriptions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#treatments">
                Treatments
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Appointments -->
        <div class="tab-pane fade show active" id="appointments">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment['Date'] }}</td>
                            <td>{{ $appointment['Type'] }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $appointment['Status'] == 'Completed' ? 'success' : 
                                    ($appointment['Status'] == 'Pending' ? 'warning' : 'info') 
                                }}">
                                    {{ $appointment['Status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No appointments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lab Tests -->
        <div class="tab-pane fade" id="lab-tests">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labTests as $test)
                        <tr>
                            <td>{{ $test['Date'] }}</td>
                            <td>{{ $test['Type'] }}</td>
                            <td>{{ $test['Result'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No lab tests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="tab-pane fade" id="prescriptions">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Medicines</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription['Date'] }}</td>
                            <td>{{ implode(', ', $prescription['Medicines']) }}</td>
                            <td>{{ $prescription['Status'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No prescriptions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Treatments -->
        <div class="tab-pane fade" id="treatments">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment['Date'] }}</td>
                            <td>{{ $treatment['Type'] }}</td>
                            <td>{{ $treatment['Status'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No treatments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection 