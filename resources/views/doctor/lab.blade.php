@extends('doctor_layout');
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Lab Management</h3>
        <button class="btn btn-primary" onclick="generateReport()">
            <i class="fas fa-file-pdf"></i> Generate Report
        </button>
    </div>

    <!-- Assign New Lab Test -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Assign New Lab Test</h5>
        </div>
        <div class="card-body">
            <form id="labAssignForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lab Test Type</label>
                        <select class="form-select" id="lab_type" required>
                            <option value="">Select Lab Test</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type['LaboratoryTypeID'] }}" 
                                        data-price="{{ $type['Price'] }}">
                                    {{ $type['LaboratoryTypeName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-select" id="patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['PatientID'] }}">
                                    {{ $patient['FullName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="lab_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" id="lab_time" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Price (RS)</label>
                        <input type="text" class="form-control" id="price" readonly>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Assign Lab Test
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assigned Lab Tests -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assigned Lab Tests</h5>
                <input type="text" class="form-control w-auto" 
                       placeholder="Search..." id="searchInput">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Test Type</th>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignedLabs as $lab)
                        <tr>
                            <td>{{ $lab['LaboratoryID'] }}</td>
                            <td>{{ $lab['LaboratoryTypeName'] }}</td>
                            <td>{{ $lab['PatientName'] }}</td>
                            <td>{{ $lab['LaboratoryDate'] }}</td>
                            <td>{{ $lab['LaboratoryTime'] }}</td>
                            <td>{{ number_format($lab['TotalPrice']) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $lab['Status'] == 'Completed' ? 'success' : 
                                    ($lab['Status'] == 'Pending' ? 'warning' : 'info') 
                                }}">
                                    {{ $lab['Status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($lab['Status'] == 'Pending')
                                        <button class="btn btn-outline-primary" 
                                                onclick="editLab({{ $lab['LaboratoryID'] }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    @if($lab['Status'] == 'Completed')
                                        <button class="btn btn-outline-info" 
                                                onclick="viewResults({{ $lab['LaboratoryID'] }})">
                                            <i class="fas fa-file-medical"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No lab tests assigned yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update price when lab type changes
    document.getElementById('lab_type').addEventListener('change', function() {
        const price = this.options[this.selectedIndex].dataset.price || '';
        document.getElementById('price').value = price;
    });

    // Form submission
    document.getElementById('labAssignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            lab_type: document.getElementById('lab_type').value,
            patient_id: document.getElementById('patient_id').value,
            date: document.getElementById('lab_date').value,
            time: document.getElementById('lab_time').value,
        };

        // Send to server
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Lab test assigned successfully!'
        }).then(() => {
            this.reset();
            window.location.reload();
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});

function editLab(id) {
    // Edit lab test logic
}

function viewResults(id) {
    // View lab results logic
}

function generateReport() {
    // Generate report logic
}
</script>
@endsection
