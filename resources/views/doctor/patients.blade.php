@extends('doctor_layout');
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>My Patients</h3>
        <div class="input-group" style="width: 300px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Search patients...">
            <button class="btn btn-outline-secondary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <div class="row">
        @forelse($patients as $patient)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $patient['FullName'] }}</h5>
                        <span class="badge bg-info">Last Visit: {{ $patient['LastVisit'] }}</span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Age:</small>
                            <div>{{ $patient['Age'] }} years</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Gender:</small>
                            <div>{{ $patient['Gender'] }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Contact:</small>
                        <div>{{ $patient['Phone'] }}</div>
                        <div>{{ $patient['Email'] }}</div>
                    </div>

                    <div class="row text-center">
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ count($patient['Appointments']) }}</div>
                                <small class="text-muted">Appointments</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ count($patient['LabTests']) }}</div>
                                <small class="text-muted">Lab Tests</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ count($patient['Prescriptions']) }}</div>
                                <small class="text-muted">Prescriptions</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('doctor.patients.show', ['id' => $patient['PatientID']]) }}" 
                           class="btn btn-primary btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No patients found.</div>
        </div>
        @endforelse
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const patientCards = document.querySelectorAll('.col-md-6');

    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        patientCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});
</script>
@endsection 