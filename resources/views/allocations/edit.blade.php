@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Allocation Notes</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Allocation Information</h5>
                            <table class="table table-bordered mb-4">
                                <tr>
                                    <th width="30%">Patient:</th>
                                    <td>{{ $allocation->patient->user->name ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <th>Bed Number:</th>
                                    <td>{{ $allocation->wardBed->BedNumber ?? 'N/A' }} ({{ $allocation->wardBed->ward->WardName ?? 'Unknown Ward' }})</td>
                                </tr>
                                <tr>
                                    <th>Allocation Date:</th>
                                    <td>{{ $allocation->AllocationDate ? date('M d, Y H:i', strtotime($allocation->AllocationDate)) : 'N/A' }}</td>
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
                    </div>

                    <form action="{{ route('allocations.update', $allocation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="Notes">Allocation Notes</label>
                            <textarea name="Notes" id="Notes" rows="6" 
                                class="form-control @error('Notes') is-invalid @enderror">{{ old('Notes', $allocation->Notes) }}</textarea>
                            <small class="text-muted">Update the notes for this bed allocation</small>
                            @error('Notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Notes</button>
                            <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 