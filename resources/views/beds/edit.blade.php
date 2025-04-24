@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Bed: {{ $bed->BedNumber }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('beds.update', $bed) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="WardID">Ward</label>
                            <select name="WardID" id="WardID" class="form-select @error('WardID') is-invalid @enderror" required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->WardID }}" {{ (old('WardID', $bed->WardID) == $ward->WardID) ? 'selected' : '' }}>
                                        {{ $ward->WardName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('WardID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="BedNumber">Bed Number</label>
                            <input type="text" name="BedNumber" id="BedNumber" class="form-control @error('BedNumber') is-invalid @enderror" 
                                value="{{ old('BedNumber', $bed->BedNumber) }}" required>
                            @error('BedNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Bed number must be unique within the selected ward.</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Status">Status</label>
                            <select name="Status" id="Status" class="form-select @error('Status') is-invalid @enderror" required>
                                <option value="available" {{ (old('Status', $bed->Status) == 'available') ? 'selected' : '' }}>Available</option>
                                <option value="occupied" {{ (old('Status', $bed->Status) == 'occupied') ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ (old('Status', $bed->Status) == 'maintenance') ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('Status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Changing status will update the bed history.
                                @if($bed->Status == 'occupied')
                                <strong class="text-danger">Warning: Changing status from "occupied" will not automatically discharge the current patient.</strong>
                                @endif
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Bed</button>
                            <a href="{{ route('beds.show', $bed) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 