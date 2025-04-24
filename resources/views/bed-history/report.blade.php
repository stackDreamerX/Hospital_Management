@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bed Utilization Report</h4>
                    <div class="float-end">
                        <a href="{{ route('bed-history.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> View Bed History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('bed-history.report') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control" 
                                        value="{{ $fromDate }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control" 
                                        value="{{ $toDate }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="ward_id" class="form-label">Ward</label>
                                    <select name="ward_id" id="ward_id" class="form-select">
                                        <option value="">All Wards</option>
                                        @foreach(App\Models\Ward::orderBy('WardName')->get() as $ward)
                                            <option value="{{ $ward->WardID }}" {{ $wardId == $ward->WardID ? 'selected' : '' }}>
                                                {{ $ward->WardName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Report Summary ({{ $fromDate }} to {{ $toDate }})</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Status Distribution</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Count</th>
                                                            <th>Percentage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $total = $byStatus->sum(); @endphp
                                                        <tr>
                                                            <td><span class="badge bg-success">Available</span></td>
                                                            <td>{{ $byStatus['available'] ?? 0 }}</td>
                                                            <td>{{ $total > 0 ? round((($byStatus['available'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="badge bg-danger">Occupied</span></td>
                                                            <td>{{ $byStatus['occupied'] ?? 0 }}</td>
                                                            <td>{{ $total > 0 ? round((($byStatus['occupied'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="badge bg-warning">Maintenance</span></td>
                                                            <td>{{ $byStatus['maintenance'] ?? 0 }}</td>
                                                            <td>{{ $total > 0 ? round((($byStatus['maintenance'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
                                                        </tr>
                                                        <tr class="table-secondary">
                                                            <th>Total</th>
                                                            <th>{{ $total }}</th>
                                                            <th>100%</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Ward Distribution</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Ward</th>
                                                            <th>Count</th>
                                                            <th>Percentage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $totalByWard = $byWard->sum(); @endphp
                                                        @foreach($byWard as $ward => $count)
                                                        <tr>
                                                            <td>{{ $ward }}</td>
                                                            <td>{{ $count }}</td>
                                                            <td>{{ $totalByWard > 0 ? round(($count / $totalByWard) * 100, 1) : 0 }}%</td>
                                                        </tr>
                                                        @endforeach
                                                        <tr class="table-secondary">
                                                            <th>Total</th>
                                                            <th>{{ $totalByWard }}</th>
                                                            <th>100%</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Occupancy Metrics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="alert alert-info">
                                                <h5>Occupancy Rate</h5>
                                                <h2>{{ $total > 0 ? round((($byStatus['occupied'] ?? 0) / $total) * 100, 1) : 0 }}%</h2>
                                                <small>Percentage of status changes that were occupied</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-warning">
                                                <h5>Avg Occupation Time</h5>
                                                <h2>{{ round($avgOccupationTime, 1) }} days</h2>
                                                <small>Average time beds were occupied</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-secondary">
                                                <h5>Maintenance Rate</h5>
                                                <h2>{{ $total > 0 ? round((($byStatus['maintenance'] ?? 0) / $total) * 100, 1) : 0 }}%</h2>
                                                <small>Percentage of status changes that were maintenance</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Recent History Records</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Ward</th>
                                            <th>Bed</th>
                                            <th>Status</th>
                                            <th>Patient</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentHistory as $record)
                                        <tr>
                                            <td>{{ date('M d, Y', strtotime($record->FromDate)) }}</td>
                                            <td>{{ $record->wardBed->ward->WardName ?? 'N/A' }}</td>
                                            <td>{{ $record->wardBed->BedNumber ?? 'N/A' }}</td>
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
                                                    {{ $record->patient->FullName ?? 'Unknown' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
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
                                {{ $recentHistory->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 