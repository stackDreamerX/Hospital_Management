@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bed History</h4>
                    <div class="float-end">
                        <a href="{{ route('bed-history.report') }}" class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i> Utilization Report
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('bed-history.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select name="ward_id" class="form-select">
                                        <option value="">All Wards</option>
                                        @foreach(App\Models\Ward::orderBy('WardName')->get() as $ward)
                                            <option value="{{ $ward->WardID }}" {{ $wardId == $ward->WardID ? 'selected' : '' }}>
                                                {{ $ward->WardName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ $status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ $status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('bed-history.index') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Ward</th>
                                    <th>Bed</th>
                                    <th>Status</th>
                                    <th>Patient</th>
                                    <th>Note</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $record)
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
                                    <td>{{ Str::limit($record->Note, 30) ?? '-' }}</td>
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
                                    <td>
                                        <a href="{{ route('bed-history.show', $record) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('beds.show', $record->wardBed) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-bed"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No history records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $history->appends(request()->query())->links() }}
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Status Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alert alert-success">
                                        <h4>Available</h4>
                                        <h2>{{ $history->where('Status', 'available')->count() }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-danger">
                                        <h4>Occupied</h4>
                                        <h2>{{ $history->where('Status', 'occupied')->count() }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-warning">
                                        <h4>Maintenance</h4>
                                        <h2>{{ $history->where('Status', 'maintenance')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 