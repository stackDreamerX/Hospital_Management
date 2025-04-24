@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Allocate Bed to Patient</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('allocations.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="PatientID">Patient</label>
                            <select name="PatientID" id="PatientID" class="form-select @error('PatientID') is-invalid @enderror" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient['PatientID'] }}" {{ old('PatientID') == $patient['PatientID'] ? 'selected' : '' }}>
                                        {{ $patient['FullName'] ?? 'Unknown' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('PatientID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="WardID">Ward</label>
                            <select id="WardID" class="form-select" onchange="loadAvailableBeds()">
                                <option value="">All Wards</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->WardID }}">
                                        {{ $ward->WardName }} ({{ $ward->availableBeds()->count() }} available)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="WardBedID">Bed</label>
                            <select name="WardBedID" id="WardBedID" class="form-select @error('WardBedID') is-invalid @enderror" required>
                                <option value="">Select Bed</option>
                                <!-- Will be populated via AJAX -->
                            </select>
                            @error('WardBedID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="AllocationDate">Allocation Date</label>
                            <input type="datetime-local" name="AllocationDate" id="AllocationDate" 
                                class="form-control @error('AllocationDate') is-invalid @enderror" 
                                value="{{ old('AllocationDate', date('Y-m-d\TH:i')) }}" required>
                            @error('AllocationDate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Notes">Notes</label>
                            <textarea name="Notes" id="Notes" rows="3" 
                                class="form-control @error('Notes') is-invalid @enderror">{{ old('Notes') }}</textarea>
                            @error('Notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Allocate Bed</button>
                            <a href="{{ route('allocations.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function loadAvailableBeds() {
        const wardId = document.getElementById('WardID').value;
        const bedDropdown = document.getElementById('WardBedID');
        
        // Clear current options
        while (bedDropdown.options.length > 1) {
            bedDropdown.remove(1);
        }
        
        // Make AJAX request to get available beds
        fetch(`{{ route('beds.available') }}?ward_id=${wardId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            data.forEach(bed => {
                const option = document.createElement('option');
                option.value = bed.WardBedID;
                option.textContent = `${bed.BedNumber} - ${bed.ward.WardName || 'Unknown Ward'}`;
                bedDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading beds:', error));
    }
    
    // Load beds on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAvailableBeds();
    });
</script>
@endpush 