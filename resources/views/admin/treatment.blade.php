@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


@section('admin_content')

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Treatment Management</h2>

    <!-- Create New Treatment Panel -->
    <div class="card mb-4" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Create New Treatment</div>
        <div class="card-body" style="padding: 20px;">
            <form id="createTreatmentForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Treatment Type</label>
                        <select name="treatment_type" class="form-control" required style="padding: 8px;">
                            <option value="">Select Treatment Type</option>
                            @foreach($treatmentTypes as $type)
                                <option value="{{ $type['TreatmentTypeID'] }}">{{ $type['TreatmentTypeName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Treatment Date</label>
                        <input type="date" name="treatment_date" class="form-control" required style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Patient</label>
                        <select name="patient_id" class="form-control" required style="padding: 8px;">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['PatientID'] }}">{{ $patient['FullName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Doctor</label>
                        <select name="doctor_id" class="form-control" required style="padding: 8px;">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['FullName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Price</label>
                        <input type="number" name="price" class="form-control" required style="padding: 8px;">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Treatment Result</label>
                        <textarea name="result" class="form-control" rows="3" style="padding: 8px;"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Create Treatment</button>
            </form>
        </div>
    </div>

    <!-- Treatment List -->
    <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Treatment List</div>
        <div class="card-body" style="padding: 20px;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Type</th>
                        <th style="padding: 12px;">Date</th>
                        <th style="padding: 12px;">Patient</th>
                        <th style="padding: 12px;">Doctor</th>
                        <th style="padding: 12px;">Price</th>
                        <th style="padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatments as $treatment)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $treatment['TreatmentID'] }}</td>
                        <td style="padding: 12px;">{{ $treatment['TreatmentTypeName'] }}</td>
                        <td style="padding: 12px;">{{ $treatment['TreatmentDate'] }}</td>
                        <td style="padding: 12px;">{{ $treatment['PatientName'] }}</td>
                        <td style="padding: 12px;">{{ $treatment['DoctorName'] }}</td>
                        <td style="padding: 12px;">{{ number_format($treatment['TotalPrice']) }}</td>
                        <td style="padding: 12px;">
                            <div class="dropdown">
                                <button class="btn btn-link" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="viewDetails({{ $treatment['TreatmentID'] }})">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="editTreatment({{ json_encode($treatment) }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="deleteTreatment({{ $treatment['TreatmentID'] }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include Edit Modal -->
@include('admin.modals.treatment_modal')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let editModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('treatmentModal'));
        
        // Add form submit handler
        document.getElementById('createTreatmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Treatment created successfully!'
            }).then(() => {
                window.location.reload();
            });
        });
    });

    function editTreatment(treatment) {
        document.getElementById('treatmentModalTitle').textContent = 'Edit Treatment';
        document.getElementById('treatment_id').value = treatment.TreatmentID;
        document.getElementById('treatment_type').value = treatment.TreatmentTypeID;
        document.getElementById('treatment_date').value = treatment.TreatmentDate;
        document.getElementById('patient_id').value = treatment.PatientID;
        document.getElementById('doctor_id').value = treatment.DoctorID;
        document.getElementById('treatment_price').value = treatment.TotalPrice;
        document.getElementById('treatment_result').value = treatment.Result || '';
        
        editModal.show();
    }

    function viewDetails(id) {
        Swal.fire({
            title: 'Treatment Details',
            text: 'Loading treatment details...',
            icon: 'info'
        });
    }

    function deleteTreatment(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Treatment has been deleted.', 'success')
                .then(() => {
                    window.location.reload();
                });
            }
        });
    }
</script>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
