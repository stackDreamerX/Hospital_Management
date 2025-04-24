@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">History for Bed: {{ $bed->BedNumber }} ({{ $bed->ward->WardName ?? 'Unknown Ward' }})</h4>
                    <div class="float-end">
                        <a href="{{ route('beds.show', $bed) }}" class="btn btn-primary">
                            <i class="fas fa-bed"></i> View Bed Details
                        </a>
                        <a href="{{ route('bed-history.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> All History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h5>Bed Information</h5>
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="120">Bed Number:</th>
                                        <td>{{ $bed->BedNumber }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ward:</th>
                                        <td>{{ $bed->ward->WardName ?? 'Unknown Ward' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Current Status:</th>
                                        <td>
                                            @if($bed->Status == 'available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($bed->Status == 'occupied')
                                                <span class="badge bg-danger">Occupied</span>
                                            @else
                                                <span class="badge bg-warning">Maintenance</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($bed->Status == 'occupied' && $bed->currentAllocation)
                                    <tr>
                                        <th>Current Patient:</th>
                                        <td>{{ $bed->currentAllocation->patient->user->name ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Allocated Since:</th>
                                        <td>{{ date('M d, Y H:i', strtotime($bed->currentAllocation->AllocationDate)) }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-secondary">
                                <h5>Status History Summary</h5>
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white p-2">
                                            <h3>{{ $history->where('Status', 'available')->count() }}</h3>
                                            <p class="mb-0">Available</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-danger text-white p-2">
                                            <h3>{{ $history->where('Status', 'occupied')->count() }}</h3>
                                            <p class="mb-0">Occupied</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-warning text-dark p-2">
                                            <h3>{{ $history->where('Status', 'maintenance')->count() }}</h3>
                                            <p class="mb-0">Maintenance</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h6>Total Status Changes: {{ $history->count() }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Complete History Timeline</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Patient</th>
                                    <th>Note</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Duration</th>
                                    <th>Updated By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $record)
                                <tr>
                                    <td>{{ date('M d, Y', strtotime($record->FromDate)) }}</td>
                                    <td>
                                        @if($record->Status == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($record->Status == 'occupied')
                                            <span class="badge bg-danger">Occupied</span>
                                        @else
                                            <span class="badge bg-warning">Maintenance</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->PatientID && $record->patient)
                                            {{ $record->patient->user->name ?? 'Unknown' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $record->Note ?? '-' }}</td>
                                    <td>{{ date('M d, H:i', strtotime($record->FromDate)) }}</td>
                                    <td>
                                        @if($record->ToDate)
                                            {{ date('M d, H:i', strtotime($record->ToDate)) }}
                                        @else
                                            <span class="badge bg-secondary">Current</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $from = new DateTime($record->FromDate);
                                            $to = $record->ToDate ? new DateTime($record->ToDate) : new DateTime();
                                            $interval = $from->diff($to);
                                            
                                            if ($interval->days > 0) {
                                                echo $interval->format('%a days, %h hrs');
                                            } else {
                                                echo $interval->format('%h hrs, %i mins');
                                            }
                                        @endphp
                                    </td>
                                    <td>{{ $record->updatedBy->name ?? 'System' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No history records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $history->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 