@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


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
</style>

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Laboratory Management</h2>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Tests</h5>
                    <p class="h4">{{ $totalTests }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Pending Tests</h5>
                    <p class="h4">{{ $pendingTests }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Completed Tests</h5>
                    <p class="h4">{{ $completedTests }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <p class="h4">${{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- labType -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5>Manage Laboratory Types</h5>
        </div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#labTypeModal">Add New Lab Type</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($labTypes as $type)
                    <tr>
                        <td>{{ $type->LaboratoryTypeID }}</td>
                        <td>{{ $type->LaboratoryTypeName }}</td>
                        <td>{{ $type->description }}</td>
                        <td>${{ number_format($type->price, 2) }}</td>
                        <td>
                             <button class="btn btn-primary btn-sm" onclick='editLabType({{ json_encode($type) }})'>Edit</button>
                             <button class="btn btn-danger btn-sm" onclick="deleteLabType({{ $type->LaboratoryTypeID }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!--modal labType -->
    <div class="modal fade" id="labTypeModal" tabindex="-1" role="dialog" aria-labelledby="labTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labTypeModalLabel">Add/Edit Lab Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="labTypeForm">
                        <input type="hidden" id="labTypeID">
                        <div class="mb-3">
                            <label for="labTypeName" class="form-label">Name</label>
                            <input type="text" id="labTypeName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="labTypeDescription" class="form-label">Description</label>
                            <textarea id="labTypeDescription" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="labTypePrice" class="form-label">Price</label>
                            <input type="number" id="labTypePrice" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveLabType()">Save</button>
                </div>
            </div>
        </div>
    </div>


    
    <!-- Create New Laboratory Assignment -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5>Create New Laboratory Assignment</h5>
        </div>
        <div class="card-body">
            <form id="createLabForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="lab_type" class="form-label">Laboratory Type</label>
                        <select name="lab_type" id="lab_type" class="form-select" required>
                            <option value="">Select Laboratory Type</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type->LaboratoryTypeID }}">{{ $type->LaboratoryTypeName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="patient_id" class="form-label">Patient</label>
                        <select name="patient_id" id="patient_id" class="form-select" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->PatientID }}">{{ $patient->FullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="doctor_id" class="form-label">Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="form-select" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->DoctorID }}">{{ $doctor->FullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lab_date" class="form-label">Laboratory Date</label>
                        <input type="date" name="lab_date" id="lab_date" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lab_time" class="form-label">Laboratory Time</label>
                        <input type="time" name="lab_time" id="lab_time" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create Laboratory Assignment</button>
            </form>
        </div>
    </div>

    <!-- Laboratory Assignments List -->
    <div class="card">
            <div class="card-header bg-light">
                <h5>Laboratory Assignments</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laboratories as $lab)
                        <tr>
                            <td>{{ $lab->LaboratoryID }}</td>
                            <td>{{ $lab->laboratoryType->LaboratoryTypeName }}</td>
                            <td>{{ $lab->patient->FullName }}</td>
                            <td>{{ $lab->doctor->FullName }}</td>
                            <td>{{ $lab->LaboratoryDate }} {{ $lab->LaboratoryTime }}</td>
                            <td>${{ number_format($lab->TotalPrice, 2) }}</td>
                            <td>{{ $lab->Status }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="viewDetails({{ $lab->LaboratoryID }})">View</button>
                                <button class="btn btn-primary btn-sm" onclick="editLab({{ $lab }})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteLab({{ $lab->LaboratoryID }})">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Edit and View -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Laboratory Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editLabForm">
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_lab_type" class="form-label">Laboratory Type</label>
                            <select id="edit_lab_type" class="form-select" required>
                                @foreach($labTypes as $type)
                                    <option value="{{ $type->LaboratoryTypeID }}">{{ $type->LaboratoryTypeName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_patient" class="form-label">Patient</label>
                            <select id="edit_patient" class="form-select" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->PatientID }}">{{ $patient->FullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_doctor" class="form-label">Doctor</label>
                            <select id="edit_doctor" class="form-select" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->DoctorID }}">{{ $doctor->FullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_date" class="form-label">Laboratory Date</label>
                            <input type="date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_time" class="form-label">Laboratory Time</label>
                            <input type="time" id="edit_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" id="edit_price" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateLab()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laboratory Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="labDetails">
                    <!-- Chi tiết xét nghiệm sẽ được tải vào đây bằng AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   
    function viewDetails(id) {
        // Gửi yêu cầu AJAX để lấy chi tiết xét nghiệm
        fetch(`/admin/lab/details/${id}`)
            .then(response => response.json())
            .then(data => {
                const details = `
                    <p><strong>Type:</strong> ${data.labType}</p>
                    <p><strong>Patient:</strong> ${data.patientName}</p>
                    <p><strong>Doctor:</strong> ${data.doctorName}</p>
                    <p><strong>Date:</strong> ${data.labDate}</p>
                    <p><strong>Time:</strong> ${data.labTime}</p>
                    <p><strong>Price:</strong> $${data.price}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Result:</strong> ${data.result || 'Pending'}</p>
                `;
                document.getElementById('labDetails').innerHTML = details;
                const modal = new bootstrap.Modal(document.getElementById('viewModal'));
                modal.show();
            })
            .catch(error => {
                alert('Failed to fetch details!');
                console.error(error);
            });
    }

    function saveLabType() {
        const id = document.getElementById('labTypeID').value;
        const name = document.getElementById('labTypeName').value;
        const description = document.getElementById('labTypeDescription').value;
        const price = document.getElementById('labTypePrice').value;

        const url = id ? `/admin/lab-type/update/${id}` : '/admin/lab-type/store';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, description, price }),
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            })
            .catch(error => {
                console.error(error);
                alert('Failed to save lab type!');
            });
    }

    function editLabType(type) {
        document.getElementById('labTypeID').value = type.LaboratoryTypeID;
        document.getElementById('labTypeName').value = type.LaboratoryTypeName;
        document.getElementById('labTypeDescription').value = type.description;
        document.getElementById('labTypePrice').value = type.price;

        const modal = new bootstrap.Modal(document.getElementById('labTypeModal'));
        modal.show();
    }

    function deleteLabType(id) {
        if (confirm('Are you sure you want to delete this lab type?')) {
            fetch(`/admin/lab-type/delete/${id}`, {
                method: 'DELETE',
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to delete lab type!');
                });
        }
    }


</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
