@extends('admin_layout')
@section('admin_content')

<style>
    .modal {
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }
    
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal-dialog {
        z-index: 1060;
        margin: 30px auto;
    }

    .modal.fade .modal-dialog {
        transform: translate(0, -25%);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translate(0, 0);
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    }

    /* Đảm bảo modal hiển thị trên cùng */
    .modal.show {
        display: block !important;
        padding-right: 17px;
    }
</style>

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Laboratory Management</h2>

    <!-- Create New Laboratory Assignment -->
    <div class="card mb-4" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">
            Create New Laboratory Assignment
        </div>
        <div class="card-body" style="padding: 20px;">
            <form id="createLabForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Laboratory Type</label>
                        <select name="lab_type" class="form-control" required style="padding: 8px;">
                            <option value="">Select Laboratory Type</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type['LaboratoryTypeID'] }}">{{ $type['LaboratoryTypeName'] }}</option>
                            @endforeach
                        </select>
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
                        <label style="font-weight: bold; margin-bottom: 5px;">Laboratory Date</label>
                        <input type="date" name="lab_date" class="form-control" required style="padding: 8px;">
                    </div>
                    <!-- <div class="col-md-12 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Initial Results</label>
                        <textarea name="result" class="form-control" rows="3" style="padding: 8px;"></textarea>
                    </div> -->
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Price</label>
                        <input type="number" name="price" class="form-control" required style="padding: 8px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Create Laboratory Assignment</button>
            </form>
        </div>
    </div>

    <!-- Laboratory Assignments List -->
    <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">
            Laboratory Assignments
            <button onclick="generateReport()" class="btn btn-success float-end">
                <i class="fa fa-file-pdf"></i> Generate Report
            </button>
        </div> -->
        <div class="card-body" style="padding: 20px;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Type</th>
                        <th style="padding: 12px;">Patient</th>
                        <th style="padding: 12px;">Doctor</th>
                        <th style="padding: 12px;">Date</th>
                        <th style="padding: 12px;">Price</th>
                        <th style="padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laboratories as $lab)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $lab['LaboratoryID'] }}</td>
                        <td style="padding: 12px;">{{ $lab['LaboratoryTypeName'] }}</td>
                        <td style="padding: 12px;">{{ $lab['PatientName'] }}</td>
                        <td style="padding: 12px;">{{ $lab['DoctorName'] }}</td>
                        <td style="padding: 12px;">{{ $lab['LaboratoryDate'] }}</td>
                        <td style="padding: 12px;">{{ number_format($lab['TotalPrice']) }}</td>
                        <td style="padding: 12px;">
                            <div class="btn-group">
                                <button onclick="viewDetails({{ $lab['LaboratoryID'] }})" 
                                        class="btn btn-info btn-sm" style="margin-right: 5px;">
                                    <i class="fa fa-eye"></i> View
                                </button>
                                <button onclick="editLab({{ json_encode($lab) }})" 
                                        class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteLab({{ $lab['LaboratoryID'] }})" 
                                        class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Laboratory Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Laboratory Type</label>
                            <select id="edit_lab_type" class="form-control" required>
                                @foreach($labTypes as $type)
                                    <option value="{{ $type['LaboratoryTypeID'] }}">{{ $type['LaboratoryTypeName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient</label>
                            <select id="edit_patient" class="form-control" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient['PatientID'] }}">{{ $patient['FullName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Doctor</label>
                            <select id="edit_doctor" class="form-control" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['FullName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Laboratory Date</label>
                            <input type="date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Results</label>
                            <textarea id="edit_result" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" id="edit_price" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateLab()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laboratory Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="labDetails">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printLabReport()">Print Report</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Create Form Submit
    document.getElementById('createLabForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Laboratory assignment created successfully!'
        }).then(() => {
            window.location.reload();
        });
    });

    // View Laboratory Details
    function viewDetails(id) {
        // Simulate loading details
        const modal = new bootstrap.Modal(document.getElementById('viewModal'));
        document.getElementById('labDetails').innerHTML = `
            <div class="text-center">
                <p><strong>Loading laboratory details...</strong></p>
            </div>
        `;
        modal.show();
    }

    // Edit Laboratory
    function editLab(lab) {
        document.getElementById('edit_id').value = lab.LaboratoryID;
        document.getElementById('edit_lab_type').value = lab.LaboratoryTypeID;
        document.getElementById('edit_patient').value = lab.PatientID;
        document.getElementById('edit_doctor').value = lab.DoctorID;
        document.getElementById('edit_date').value = lab.LaboratoryDate;
        document.getElementById('edit_result').value = lab.Result || '';
        document.getElementById('edit_price').value = lab.TotalPrice;
        
        if (editModal) {
            editModal.show();
        } else {
            console.error('Modal not initialized');
        }
    }

    // Update Laboratory
    function updateLab() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Laboratory assignment updated successfully!'
        }).then(() => {
            window.location.reload();
        });
    }

    // Delete Laboratory
    function deleteLab(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Laboratory assignment has been deleted.',
                    'success'
                ).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    // Generate Report
    // function generateReport() {
    //     Swal.fire({
    //         icon: 'info',
    //         title: 'Generating Report',
    //         text: 'The report is being generated...',
    //         timer: 2000,
    //         timerProgressBar: true,
    //         showConfirmButton: false
    //     }).then(() => {
    //         window.location.reload();
    //     });
    // }

    // Print Laboratory Report
    function printLabReport() {
        window.print();
    }

    // Khởi tạo modal khi document ready
    let editModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            backdrop: 'static',
            keyboard: false
        });
    });
</script>
@endsection