@extends('doctor_layout');

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')

<style>
    /* Enhanced Modal Styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }
    
    .modal-header .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
    }
    
    .modal-header .btn-close {
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        opacity: 1;
        padding: 0.6rem;
        margin: -0.5rem -0.5rem -0.5rem auto;
        transition: all 0.3s ease;
    }
    
    .modal-header .btn-close:hover {
        background-color: rgba(255, 255, 255, 0.8);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    
    .modal-footer .btn {
        border-radius: 10px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .modal-footer .btn-secondary {
        background-color: #f8f9fa;
        color: var(--text-color);
        border: 1px solid #ddd;
    }
    
    .modal-footer .btn-secondary:hover {
        background-color: #e9ecef;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .modal-footer .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        box-shadow: 0 4px 10px rgba(0, 146, 216, 0.2);
    }
    
    .modal-footer .btn-primary:hover {
        box-shadow: 0 6px 15px rgba(0, 146, 216, 0.3);
        transform: translateY(-3px);
    }
    
    /* Lab details styling */
    #labDetails p {
        margin-bottom: 1rem;
        padding: 0.8rem 1rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }
    
    #labDetails strong {
        color: var(--primary-dark);
        font-weight: 600;
        min-width: 120px;
        display: inline-block;
    }
    
    /* Animation for modal */
    .modal.fade .modal-dialog {
        transform: scale(0.95) translateY(-20px);
        transition: transform 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
    
    /* Enhanced form controls */
    .form-select, .form-control {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
    }
    
    .form-select:focus, .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 146, 216, 0.25);
    }
    
    /* Enhance the action buttons */
    .btn-info, .btn-primary, .btn-danger {
        margin: 0 2px;
        border-radius: 8px;
        padding: 0.5rem 0.8rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #2bb0ed 0%, #3f8cff 100%);
        box-shadow: 0 4px 10px rgba(63, 140, 255, 0.2);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        box-shadow: 0 4px 10px rgba(0, 146, 216, 0.2);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ff5f6d 0%, #ff427f 100%);
        box-shadow: 0 4px 10px rgba(255, 66, 127, 0.2);
    }
    
    .btn-info:hover, .btn-primary:hover, .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    
    /* Statistics Cards Enhancement */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        font-weight: 600;
        padding: 1.25rem;
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    .text-white h5 {
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .text-white .h4 {
        font-size: 1.8rem;
        font-weight: 700;
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

   

<div>
    <br>
</div>
    
    <!-- Create New Laboratory Assignment -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5>Create New Laboratory Assignment</h5>
        </div>
        <div class="card-body">
            <form id="createLabForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="lab_type" class="form-label">Laboratory Type</label>
                        <select name="lab_type" id="lab_type" class="form-select" required>
                            <option value="">Select Laboratory Type</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type->LaboratoryTypeID }}" data-price="{{ $type->price }}">
                                    {{ $type->LaboratoryTypeName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="user_id" class="form-label">Patient</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->UserID }}">{{ $patient->FullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="doctor_id" class="form-label">Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="form-select" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->DoctorID }}">{{ $doctor->user->FullName }}</option>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laboratories as $lab)
                    <tr>
                        <td>{{ $lab->LaboratoryID }}</td>
                        <td>{{ $lab->laboratoryType->LaboratoryTypeName }}</td>
                        <td>{{ $lab->user->FullName }}</td>
                        <td>{{ $lab->doctor->user->FullName }}</td>
                        <td>{{ $lab->LaboratoryDate }} {{ $lab->LaboratoryTime }}</td>
                        <td>${{ number_format($lab->TotalPrice, 2) }}</td>
                        <td>
                            <button class="btn btn-info view-btn" data-id="{{ $lab->LaboratoryID }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-primary edit-btn" data-id="{{ $lab->LaboratoryID }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger delete-btn" data-id="{{ $lab->LaboratoryID }}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals for Edit and View -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2 text-white"></i> Edit Laboratory Assignment</h5>
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
                                    <option value="{{ $patient->UserID }}">{{ $patient->FullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_doctor" class="form-label">Doctor</label>
                            <select id="edit_doctor" class="form-select" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->DoctorID }}">{{ $doctor->user->FullName }}</option>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-flask me-2 text-white"></i> Laboratory Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="labDetails" class="p-2">
                    <!-- Laboratory details will be loaded here -->
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
    // Add event listeners once the DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // View button event listeners
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                viewDetails(id);
            });
        });
        
        // Edit button event listeners
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editLab(id);
            });
        });
        
        // Delete button event listeners
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                deleteLab(id);
            });
        });
        
        // Lab type change event
        document.getElementById('lab_type').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('price').value = price ? price : '';
        });
        
        // Form submit event
        document.getElementById('createLabForm').addEventListener('submit', function (event) {
            event.preventDefault();
            createLabAssignment();
        });
    });
   
    function viewDetails(id) {
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Loading...',
            text: 'Fetching laboratory details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = `{{ route('doctor.lab.details', ['id' => '__id__']) }}`.replace('__id__', id);
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch details. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Close loading dialog
                loadingSwal.close();
                
                // Safely display lab details
                let details = '<p>No details available</p>';
                
                try {
                    details = `
                        <p><strong>Type:</strong> ${data.labType || 'N/A'}</p>
                        <p><strong>Patient:</strong> ${data.patientName || 'N/A'}</p>
                        <p><strong>Doctor:</strong> ${data.doctorName || 'N/A'}</p>
                        <p><strong>Date:</strong> ${data.labDate || 'N/A'}</p>
                        <p><strong>Time:</strong> ${data.labTime || 'N/A'}</p>
                        <p><strong>Price:</strong> $${data.price || '0.00'}</p>
                        <p><strong>Result:</strong> ${data.result || 'Pending'}</p>
                    `;
                } catch (e) {
                    console.error('Error formatting lab details:', e);
                }
                
                document.getElementById('labDetails').innerHTML = details;

                const modal = new bootstrap.Modal(document.getElementById('viewModal'));
                modal.show();
            })
            .catch(error => {
                // Ensure loading dialog is closed on error
                loadingSwal.close();
                
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to fetch laboratory details. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
                console.error('Error fetching lab details:', error.message);
            });
    }

    function editLab(id) {
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Processing...',
            text: 'Loading laboratory details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = `{{ route('doctor.lab.details', ['id' => '__id__']) }}`.replace('__id__', id);
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch details. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Close loading dialog
                loadingSwal.close();
                
                // Handle different data formats
                let labData = data;
                
                // If we received an object with a lab property (common API pattern)
                if (data.lab) {
                    labData = data.lab;
                }
                
                // Direct assignment with fallbacks for all fields
                document.getElementById('edit_id').value = id;
                
                // Try to set lab type if available
                if (document.getElementById('edit_lab_type')) {
                    const labTypeSelect = document.getElementById('edit_lab_type');
                    if (labData.LaboratoryTypeID) {
                        labTypeSelect.value = labData.LaboratoryTypeID;
                    }
                }
                
                // Try to set patient if available
                if (document.getElementById('edit_patient')) {
                    const patientSelect = document.getElementById('edit_patient');
                    if (labData.UserID) {
                        patientSelect.value = labData.UserID;
                    }
                }
                
                // Try to set doctor if available
                if (document.getElementById('edit_doctor')) {
                    const doctorSelect = document.getElementById('edit_doctor');
                    if (labData.DoctorID) {
                        doctorSelect.value = labData.DoctorID;
                    }
                }
                
                // Try to set date
                if (document.getElementById('edit_date')) {
                    let dateValue = '';
                    if (labData.LaboratoryDate) {
                        // If date contains time, extract just the date part
                        if (labData.LaboratoryDate.includes(' ')) {
                            dateValue = labData.LaboratoryDate.split(' ')[0];
                        } else {
                            dateValue = labData.LaboratoryDate;
                        }
                    }
                    document.getElementById('edit_date').value = dateValue;
                }
                
                // Try to set time
                if (document.getElementById('edit_time')) {
                    let timeValue = '';
                    if (labData.LaboratoryTime) {
                        timeValue = labData.LaboratoryTime;
                    } else if (labData.LaboratoryDate && labData.LaboratoryDate.includes(' ')) {
                        timeValue = labData.LaboratoryDate.split(' ')[1];
                    }
                    document.getElementById('edit_time').value = timeValue;
                }
                
                // Try to set price
                if (document.getElementById('edit_price')) {
                    document.getElementById('edit_price').value = labData.TotalPrice || '';
                }

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            })
            .catch(error => {
                // Ensure loading dialog is closed on error
                loadingSwal.close();
                
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to fetch laboratory details. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function updateLab() {
        const id = document.getElementById('edit_id')?.value || null;
        const labType = document.getElementById('edit_lab_type')?.value || null;
        const userId = document.getElementById('edit_patient')?.value || null;
        const doctorId = document.getElementById('edit_doctor')?.value || null;
        const labDate = document.getElementById('edit_date')?.value || null;
        const labTime = document.getElementById('edit_time')?.value || null;
        const price = document.getElementById('edit_price')?.value || null;

        // Validate fields
        if (!id || !labType || !userId || !doctorId || !labDate || !labTime || !price) {
            Swal.fire({
                title: 'Validation Error',
                text: 'Please fill in all the required fields.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Processing...',
            text: 'Updating laboratory assignment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = `{{ route('doctor.lab.updateLab', ['id' => '__id__']) }}`.replace('__id__', id);

        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                labType,
                userId,
                doctorId,
                labDate,
                labTime,
                price,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Close loading dialog
            loadingSwal.close();
            
            Swal.fire({
                title: 'Success!',
                text: data.message || 'Laboratory assignment updated successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            // Close loading dialog
            loadingSwal.close();
            
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update laboratory assignment. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        });
    }

    function deleteLab(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                const loadingSwal = Swal.fire({
                    title: 'Processing...',
                    text: 'Deleting laboratory assignment',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const url = `{{ route('doctor.lab.delete', ['id' => '__id__']) }}`.replace('__id__', id);
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Failed to delete laboratory assignment.');
                    }
                })
                .then(data => {
                    // Close loading dialog
                    loadingSwal.close();
                    
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message || 'Laboratory assignment deleted successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    // Close loading dialog
                    loadingSwal.close();
                    
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete laboratory assignment. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                });
            }
        });
    }

    function createLabAssignment() {
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Processing...',
            text: 'Creating new laboratory assignment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Collect form data
        const lab_type = document.getElementById('lab_type').value;
        const user_id = document.getElementById('user_id').value;
        const doctor_id = document.getElementById('doctor_id').value;
        const lab_date = document.getElementById('lab_date').value;
        const lab_time = document.getElementById('lab_time').value;
        const price = document.getElementById('price').value;
        const createLabUrl = "{{ route('doctor.lab.create') }}";

        fetch(createLabUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                lab_type,
                user_id,
                doctor_id,
                lab_date,
                lab_time,
                price,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Close loading dialog
            loadingSwal.close();
            
            Swal.fire({
                title: 'Success!',
                text: data.message || 'Laboratory assignment created successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            // Close loading dialog
            loadingSwal.close();
            
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to create laboratory assignment. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        });
    }
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
