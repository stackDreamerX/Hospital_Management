@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Bed</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('beds.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="WardID">Ward</label>
                            <select name="WardID" id="WardID" class="form-select @error('WardID') is-invalid @enderror" required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->WardID }}" {{ old('WardID') == $ward->WardID ? 'selected' : '' }}>
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
                                value="{{ old('BedNumber') }}" required placeholder="Enter bed number (e.g. B-101)">
                            @error('BedNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Bed number must be unique within the selected ward.</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Status">Status</label>
                            <select name="Status" id="Status" class="form-select @error('Status') is-invalid @enderror" required>
                                <option value="available" {{ old('Status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="maintenance" {{ old('Status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('Status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Bed</button>
                            <a href="{{ route('beds.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 