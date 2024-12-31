@extends('doctor_layout');

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')


<style>
     modal {
  display: none; /* Ẩn modal ban đầu */
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050; /* Bootstrap 5 modal z-index */
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.5); /* Overlay mờ */
}

.modal.fade {
  opacity: 0; /* Modal mờ khi chưa được hiển thị */
  transition: opacity 0.15s linear;
}

.modal.show {
  display: block; /* Hiển thị modal */
  opacity: 1;
}

.modal-dialog {
  position: relative;
  margin: 1.75rem auto; /* Center modal vertically */
  pointer-events: auto;
  max-width: 500px; /* Độ rộng mặc định */
}

.modal-dialog.modal-lg {
  max-width: 800px; /* Độ rộng modal lớn */
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  background-color: #fff;
  border: none;
  border-radius: 0.5rem; /* Bo góc */
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Đổ bóng */
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1rem;
  border-bottom: 1px solid #dee2e6; /* Border dưới */
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
}

.btn-close {
  background: none;
  border: none;
  -webkit-appearance: none;
}

.modal-body {
  position: relative;
  flex: 1 1 auto;
  padding: 1rem;
}

.modal-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 1rem;
  border-top: 1px solid #dee2e6;
}



</style>

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

    <div class="row" id="patientList">
        @forelse($patients as $patient)
        <div class="col-md-6 mb-4 patient-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $patient->FullName }}</h5>
                        <span class="badge bg-info">Last Visit: {{ $patient->appointments->last()?->AppointmentDate ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Age:</small>
                            <div>{{ $patient->age ?? 'N/A' }} years</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Gender:</small>
                            <div>{{ $patient->gender ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Contact:</small>
                        <div>{{ $patient->PhoneNumber }}</div>
                        <div>{{ $patient->Email }}</div>
                    </div>

                    <div class="row text-center">
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ $patient->appointment_count }}</div>
                                <small class="text-muted">Appointments</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ $patient->lab_test_count }}</div>
                                <small class="text-muted">Lab Tests</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ $patient->prescription_count }}</div>
                                <small class="text-muted">Prescriptions</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0">{{ $patient->treatment_count }}</div>
                                <small class="text-muted">Treatments</small>
                            </div>
                        </div>
                    </div>


                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('doctor.patients.show', ['id' => $patient->UserID]) }}"
                           class="btn btn-primary btn-sm">
                            View Details
                        </a>
                        <a href="{{ route('doctor.lab.create', ['id' => $patient->UserID]) }}"
                           class="btn btn-secondary btn-sm">
                            New Lab Assignment
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
    const patientCards = document.querySelectorAll('.patient-card');

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


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
