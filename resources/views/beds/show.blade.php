@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bed Details: {{ $bed->BedNumber }}</h4>
                    <div class="float-end">
                        <a href="{{ route('beds.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Bed Number:</th>
                                    <td>{{ $bed->BedNumber }}</td>
                                </tr>
                                <tr>
                                    <th>Ward:</th>
                                    <td>{{ $bed->ward->WardName ?? 'N/A' }}</td>
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
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Current Allocation</h5>
                            @if($bed->Status == 'occupied' && $bed->currentAllocation)
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Patient:</th>
                                        <td>{{ $bed->currentAllocation->patient->user->name ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Allocated On:</th>
                                        <td>{{ $bed->currentAllocation->AllocationDate ? date('M d, Y H:i', strtotime($bed->currentAllocation->AllocationDate)) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Allocated By:</th>
                                        <td>{{ $bed->currentAllocation->allocatedBy->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Actions:</th>
                                        <td>
                                            <a href="{{ route('allocations.show', $bed->currentAllocation) }}" class="btn btn-sm btn-info">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <div class="alert alert-info">
                                    No current patient allocation.
                                    @if($bed->Status == 'available')
                                        <a href="{{ route('allocations.create') }}" class="btn btn-sm btn-primary mt-2">
                                            Assign Patient
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($bed->Status != 'occupied')
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Change Bed Status</h5>
                            <form action="{{ route('beds.change-status', $bed) }}" method="POST" class="row g-3">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    <select name="Status" class="form-select">
                                        <option value="available" {{ $bed->Status == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="maintenance" {{ $bed->Status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="Note" class="form-control" placeholder="Status change note (optional)">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-warning w-100">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Recent History</h5>
                                    <a href="{{ route('bed-history.for-bed', $bed) }}" class="btn btn-sm btn-secondary">
                                        View Full History
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Patient</th>
                                                    <th>Note</th>
                                                    <th>Updated By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($bed->history()->orderBy('FromDate', 'desc')->take(5)->get() as $history)
                                                <tr>
                                                    <td>{{ date('M d, Y H:i', strtotime($history->FromDate)) }}</td>
                                                    <td>
                                                        @if($history->Status == 'available')
                                                            <span class="badge bg-success">Available</span>
                                                        @elseif($history->Status == 'occupied')
                                                            <span class="badge bg-danger">Occupied</span>
                                                        @else
                                                            <span class="badge bg-warning">Maintenance</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($history->PatientID && $history->patient)
                                                            {{ $history->patient->user->name ?? 'Unknown' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $history->Note ?? '-' }}</td>
                                                    <td>{{ $history->updatedBy->name ?? 'System' }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No history records found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ route('beds.edit', $bed) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Bed
                        </a>
                        @if($bed->Status != 'occupied')
                            <form action="{{ route('beds.destroy', $bed) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this bed?')">
                                    <i class="fas fa-trash"></i> Delete Bed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 