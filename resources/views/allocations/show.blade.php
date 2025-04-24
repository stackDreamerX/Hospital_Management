@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bed Allocation Details</h4>
                    <div class="float-end">
                        <a href="{{ route('allocations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Allocation Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Patient:</th>
                                    <td>{{ $allocation->patient->FullName ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <th>Ward:</th>
                                    <td>{{ $allocation->wardBed->ward->WardName ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bed Number:</th>
                                    <td>{{ $allocation->wardBed->BedNumber ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($allocation->DischargeDate)
                                            <span class="badge bg-secondary">Discharged</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Dates</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Allocation Date:</th>
                                    <td>{{ $allocation->AllocationDate ? date('M d, Y H:i', strtotime($allocation->AllocationDate)) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Discharge Date:</th>
                                    <td>{{ $allocation->DischargeDate ? date('M d, Y H:i', strtotime($allocation->DischargeDate)) : 'Not discharged yet' }}</td>
                                </tr>
                                <tr>
                                    <th>Allocated By:</th>
                                    <td>{{ $allocation->allocatedBy->FullName ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ date('M d, Y H:i', strtotime($allocation->created_at)) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($allocation->Notes ?? 'No notes available')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$allocation->DischargeDate)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Discharge Patient</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('allocations.discharge', $allocation) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="DischargeDate">Discharge Date</label>
                                                    <input type="datetime-local" name="DischargeDate" id="DischargeDate" 
                                                        class="form-control {{ $errors->has('DischargeDate') ? 'is-invalid' : '' }}" 
                                                        value="{{ old('DischargeDate', date('Y-m-d\TH:i')) }}" required>
                                                    @if($errors->has('DischargeDate'))
                                                        <div class="invalid-feedback">{{ $errors->first('DischargeDate') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="BedStatus">Bed Status After Discharge</label>
                                                    <select name="BedStatus" id="BedStatus" class="form-select {{ $errors->has('BedStatus') ? 'is-invalid' : '' }}" required>
                                                        <option value="maintenance">Maintenance (Cleaning)</option>
                                                        <option value="available">Available (Skip Cleaning)</option>
                                                    </select>
                                                    @if($errors->has('BedStatus'))
                                                        <div class="invalid-feedback">{{ $errors->first('BedStatus') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="DischargeNotes">Discharge Notes</label>
                                                    <textarea name="DischargeNotes" id="DischargeNotes" rows="2" 
                                                        class="form-control {{ $errors->has('DischargeNotes') ? 'is-invalid' : '' }}">{{ old('DischargeNotes') }}</textarea>
                                                    @if($errors->has('DischargeNotes'))
                                                        <div class="invalid-feedback">{{ $errors->first('DischargeNotes') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to discharge this patient?')">
                                                <i class="fas fa-door-open"></i> Discharge Patient
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Bed History</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                            <th>Updated By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allocation->wardBed->history()->orderBy('FromDate', 'desc')->take(5)->get() as $history)
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
                                            <td>{{ $history->Note ?? '-' }}</td>
                                            <td>{{ $history->updatedBy->FullName ?? 'System' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No history records found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('bed-history.for-bed', $allocation->wardBed) }}" class="btn btn-sm btn-info">
                                    View Full Bed History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        @if(!$allocation->DischargeDate)
                            <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Notes
                            </a>
                        @endif
                        <a href="{{ route('beds.show', $allocation->wardBed) }}" class="btn btn-info">
                            <i class="fas fa-bed"></i> View Bed Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 