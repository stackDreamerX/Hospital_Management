@extends('doctor_layout')

@push('styles')
<link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')

<style>
    body {
        background-color: #f8f9fa; 
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        padding: 15px;
        margin-bottom: 20px;
    }

    .nav-tabs > li > a {
        color: #495057; 
    }

    .nav-tabs > li.active > a, 
    .nav-tabs > li.active > a:focus, 
    .nav-tabs > li.active > a:hover {
        background-color: #337ab7; 
        color: #fff; 
        border-color: #ddd #ddd transparent;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 3px;
    }

    .table-responsive {
        margin-top: 15px;
        overflow-x: auto;
    }

    .tab-pane {
        padding-top: 20px;
    }

    .text-center {
        color: #6c757d;
    }

    .btn {
        font-size: 12px;
        padding: 5px 10px;
    }

    /* Tùy chỉnh trạng thái */
    .label-success {
        background-color: #5cb85c;
    }

    .label-warning {
        background-color: #f0ad4e;
    }

    .label-danger {
        background-color: #d9534f;
    }
</style>

<div class="container">
    <!-- button back -->
    <div class="mb-3">
        <button class="btn btn-primary" onclick="goBack()">
            <i class="fa fa-arrow-left"></i> Back
        </button>
    </div>

    <!-- Patient Info -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="pull-left">{{ $patient->FullName }}</h3>
                    <span class="label label-info pull-right">Last Visit: {{ $appointments->first()->AppointmentDate ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <p><strong>Age:</strong> {{ $patient->age ?? 'Unknown' }} years</p>
                    <p><strong>Gender:</strong> {{ ucfirst($patient->gender) ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p><strong>Phone:</strong> {{ $patient->PhoneNumber }}</p>
                    <p><strong>Email:</strong> {{ $patient->Email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#appointments">Appointments</a></li>
        <li><a data-toggle="tab" href="#treatments">Treatments</a></li>
        <li><a data-toggle="tab" href="#prescriptions">Prescriptions</a></li>
        <li><a data-toggle="tab" href="#lab-tests">Lab Tests</a></li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Appointments -->
        <div id="appointments" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->AppointmentDate }}</td>
                            <td>{{ $appointment->AppointmentTime }}</td>
                            <td>{{ $appointment->Reason }}</td>
                            <td>
                                <span class="label label-{{
                                    $appointment->Status == 'Completed' ? 'success' :
                                    ($appointment->Status == 'Pending' ? 'warning' : 'danger')
                                }}">
                                    {{ ucfirst($appointment->Status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No appointments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Treatments -->
        <div id="treatments" class="tab-pane fade">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment->TreatmentDate }}</td>
                            <td>{{ $treatment->TreatmentTypeID }}</td>
                            <td>{{ $treatment->TotalPrice }}</td>
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

        <!-- Prescriptions -->
        <div id="prescriptions" class="tab-pane fade">
            <div class="table-responsive">
                <table class="table table-bordered">
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
                            <td>{{ $prescription->PrescriptionDate }}</td>
                            <td>
                            @if($prescription->prescriptionDetails && $prescription->prescriptionDetails->isNotEmpty())
                                @foreach($prescription->prescriptionDetails as $detail)
                                    {{ $detail->medicine->MedicineName ?? 'N/A' }} (x{{ $detail->Quantity ?? '0' }})<br>
                                @endforeach
                            @else
                                <p>No prescription details available.</p>
                            @endif
                            </td>
                            <td>{{ $prescription->Status }}</td>
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

        <!-- Lab Tests -->
        <div id="lab-tests" class="tab-pane fade">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Result</th>
                            <th>Price</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labTests as $labTest)
                        <tr>
                            <td>{{ $labTest->created_at->format('Y-m-d') }}</td>
                            <td>{{ $labTest->laboratoryType->LaboratoryTypeName ?? 'N/A' }}</td>
                            <td>{{ $labTest->laboratoryResults->Result ?? 'No result available' }}</td>
                            <td>{{ $labTest->laboratoryType->price ?? 'N/A' }}</td>
                            <td>{{ $labTest->laboratoryType->description ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No lab tests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.nav-tabs a:first').tab('show');
    });


    function goBack() {
        if (document.referrer) {
            window.history.back();
        } else {
            window.location.href = '{{ route('doctor.patients') }}'; 
        }
    }

</script>


@endsection

@section('script')

@endsection

@push('scripts')
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@endpush
