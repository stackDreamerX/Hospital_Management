@extends('doctor_layout');
@section('content')

<div class="container mt-4">
    <!-- Create New Treatment -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Assign New Treatment</h5>
        </div>
        <div class="card-body">
            <form id="treatmentForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-select" id="patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['PatientID'] }}">{{ $patient['FullName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Treatment Type</label>
                        <select class="form-select" id="treatment_type" required>
                            <option value="">Select Type</option>
                            @foreach($treatmentTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="2" required
                                  placeholder="Detailed description of the treatment"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="treatment_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" class="form-control" id="duration" 
                               placeholder="e.g., 30 minutes" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" rows="2"
                                  placeholder="Additional notes or instructions"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Assign Treatment
                </button>
            </form>
        </div>
    </div>

    <!-- Treatment List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Treatment List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment['TreatmentDate'] }}</td>
                            <td>{{ $treatment['PatientName'] }}</td>
                            <td>{{ $treatment['Type'] }}</td>
                            <td>{{ $treatment['Duration'] }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $treatment['Status'] == 'Completed' ? 'success' : 
                                    ($treatment['Status'] == 'Scheduled' ? 'info' : 'danger') 
                                }}">
                                    {{ $treatment['Status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewTreatment({{ json_encode($treatment) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($treatment['Status'] == 'Scheduled')
                                        <button class="btn btn-success" 
                                                onclick="completeTreatment({{ $treatment['TreatmentID'] }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-danger" 
                                                onclick="cancelTreatment({{ $treatment['TreatmentID'] }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No treatments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Treatment Details Modal -->
<div class="modal fade" id="treatmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Treatment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="treatmentDetails">
                    <!-- Details will be loaded here -->
                </div>
                <div id="progressForm" class="mt-3 d-none">
                    <hr>
                    <h6>Update Progress</h6>
                    <textarea class="form-control" id="progress" rows="3"
                              placeholder="Enter treatment progress and observations"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success d-none" id="saveProgressBtn"
                        onclick="saveProgress()">
                    Save Progress
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let treatmentModal;
let currentTreatment;

document.addEventListener('DOMContentLoaded', function() {
    treatmentModal = new bootstrap.Modal(document.getElementById('treatmentModal'));

    document.getElementById('treatmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createTreatment();
    });
});

function createTreatment() {
    const data = {
        patient_id: document.getElementById('patient_id').value,
        type: document.getElementById('treatment_type').value,
        description: document.getElementById('description').value,
        treatment_date: document.getElementById('treatment_date').value,
        duration: document.getElementById('duration').value,
        notes: document.getElementById('notes').value
    };

    // Send to server
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Treatment assigned successfully!'
    }).then(() => {
        window.location.reload();
    });
}

function viewTreatment(treatment) {
    currentTreatment = treatment;
    const details = document.getElementById('treatmentDetails');
    
    details.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <p><strong>Patient:</strong> ${treatment.PatientName}</p>
                <p><strong>Type:</strong> ${treatment.Type}</p>
                <p><strong>Date:</strong> ${treatment.TreatmentDate}</p>
                <p><strong>Duration:</strong> ${treatment.Duration}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> ${treatment.Status}</p>
                <p><strong>Description:</strong> ${treatment.Description}</p>
                <p><strong>Notes:</strong> ${treatment.Notes || 'N/A'}</p>
                <p><strong>Progress:</strong> ${treatment.Progress || 'N/A'}</p>
            </div>
        </div>
    `;

    // Show progress form for scheduled treatments
    const progressForm = document.getElementById('progressForm');
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    if (treatment.Status === 'Scheduled') {
        progressForm.classList.remove('d-none');
        saveProgressBtn.classList.remove('d-none');
    } else {
        progressForm.classList.add('d-none');
        saveProgressBtn.classList.add('d-none');
    }

    treatmentModal.show();
}

function completeTreatment(id) {
    Swal.fire({
        title: 'Complete Treatment',
        text: 'Please enter the treatment progress:',
        input: 'textarea',
        inputPlaceholder: 'Enter progress notes...',
        showCancelButton: true,
        confirmButtonText: 'Complete',
        showLoaderOnConfirm: true,
        preConfirm: (progress) => {
            if (!progress) {
                Swal.showValidationMessage('Progress notes are required')
            }
            return progress;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send to server
            Swal.fire('Completed!', 'Treatment has been marked as completed.', 'success')
            .then(() => window.location.reload());
        }
    });
}

function cancelTreatment(id) {
    Swal.fire({
        title: 'Cancel Treatment',
        text: 'Are you sure you want to cancel this treatment?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send to server
            Swal.fire('Cancelled!', 'Treatment has been cancelled.', 'success')
            .then(() => window.location.reload());
        }
    });
}

function saveProgress() {
    const progress = document.getElementById('progress').value;
    if (!progress) {
        Swal.fire('Error', 'Please enter progress notes', 'error');
        return;
    }

    // Send to server
    Swal.fire('Saved!', 'Progress has been updated.', 'success')
    .then(() => {
        treatmentModal.hide();
        window.location.reload();
    });
}
</script>
@endsection
