@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">History Record Details</h4>
                    <div class="float-end">
                        <a href="{{ route('bed-history.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Bed Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Bed Number:</th>
                                    <td>{{ $history->wardBed->BedNumber ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ward:</th>
                                    <td>{{ $history->wardBed->ward->WardName ?? 'Unknown Ward' }}</td>
                                </tr>
                                <tr>
                                    <th>Current Status:</th>
                                    <td>
                                        @if($history->wardBed->Status == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($history->wardBed->Status == 'occupied')
                                            <span class="badge bg-danger">Occupied</span>
                                        @else
                                            <span class="badge bg-warning">Maintenance</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Record Status</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Status:</th>
                                    <td>
                                        @if($history->Status == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($history->Status == 'occupied')
                                            <span class="badge bg-danger">Occupied</span>
                                        @else
                                            <span class="badge bg-warning">Maintenance</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Patient:</th>
                                    <td>
                                        @if($history->PatientID && $history->patient)
                                            {{ $history->patient->FullName ?? 'Unknown' }}
                                        @else
                                            None (Bed Unoccupied)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Updated By:</th>
                                    <td>{{ $history->updatedBy->FullName ?? 'System' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Period Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="15%">From Date:</th>
                                    <td width="35%">{{ date('M d, Y H:i:s', strtotime($history->FromDate)) }}</td>
                                    <th width="15%">To Date:</th>
                                    <td width="35%">
                                        @if($history->ToDate)
                                            {{ date('M d, Y H:i:s', strtotime($history->ToDate)) }}
                                        @else
                                            <span class="badge bg-secondary">Current</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Duration:</th>
                                    <td colspan="3">
                                        @php
                                            $from = new DateTime($history->FromDate);
                                            $to = $history->ToDate ? new DateTime($history->ToDate) : new DateTime();
                                            $interval = $from->diff($to);
                                            
                                            if ($interval->days > 0) {
                                                echo $interval->format('%a days, %h hours, %i minutes');
                                            } else {
                                                echo $interval->format('%h hours, %i minutes');
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($history->Note ?? 'No notes available for this record.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Record Timeline</h5>
                            <div class="alert alert-info">
                                <p><strong>Record Created:</strong> {{ date('M d, Y H:i:s', strtotime($history->created_at)) }}</p>
                                <p><strong>Record Updated:</strong> {{ date('M d, Y H:i:s', strtotime($history->updated_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ route('beds.show', $history->wardBed) }}" class="btn btn-primary">
                            <i class="fas fa-bed"></i> View Bed Details
                        </a>
                        <a href="{{ route('bed-history.for-bed', $history->wardBed) }}" class="btn btn-info">
                            <i class="fas fa-history"></i> View Bed History
                        </a>
                        @if($history->PatientID && $history->patient)
                            @if($allocation = $history->patient->bedAllocations()->whereNull('DischargeDate')->first())
                                <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-success">
                                    <i class="fas fa-user"></i> View Current Allocation
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 