@extends('admin_layout')

@push('styles')

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">

<style>
    /* Reset and improved modal styling */
    body .modal {
        display: none;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1050 !important;
        width: 100% !important;
        height: 100% !important;
        overflow: hidden !important;
        outline: 0 !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }

    body .modal.fade {
        opacity: 0;
        transition: opacity 0.15s linear;
    }

    body .modal.show {
        display: block !important;
        opacity: 1 !important;
    }

    body .modal-dialog {
        position: relative !important;
        margin: 1.75rem auto !important;
        max-width: 500px !important;
        pointer-events: auto !important;
        transform: none !important;
    }

    body .modal-dialog.modal-lg {
        max-width: 800px !important;
    }

    body .modal-content {
        position: relative !important;
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        background-color: #fff !important;
        border: none !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        pointer-events: auto !important;
        outline: 0 !important;
    }

    body .modal-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 1rem !important;
        border-bottom: 1px solid #dee2e6 !important;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
        background: linear-gradient(135deg, #2bb0ed 0%, #3f8cff 100%) !important;
        color: white !important;
    }

    body .modal-title {
        margin-bottom: 0 !important;
        line-height: 1.5 !important;
        font-weight: 600 !important;
        color: white !important;
    }

    body .btn-close {
        background: rgba(255, 255, 255, 0.5) !important;
        border-radius: 50% !important;
        opacity: 1 !important;
        padding: 0.6rem !important;
        border: none !important;
        -webkit-appearance: none !important;
    }

    body .modal-body {
        position: relative !important;
        flex: 1 1 auto !important;
        padding: 1.5rem !important;
        overflow-y: auto !important;
        max-height: 70vh !important;
        color: #212529 !important; /* Dark text color */
    }

    body .modal-footer {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        padding: 1rem !important;
        border-top: 1px solid #dee2e6 !important;
    }

    /* Ensure the modal backdrop is visible */
    body .modal-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1040 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }

    body .modal-backdrop.fade {
        opacity: 0 !important;
    }

    body .modal-backdrop.show {
        opacity: 0.5 !important;
    }
    
    /* Additional CSS to ensure modals appear correctly */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 15px !important;
    }
    
    /* Force modal display when .show-force is applied */
    body .modal.show-force {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }
    
    /* Force backdrop to display */
    body .modal-backdrop-force {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1040 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
        opacity: 0.5 !important;
    }
    
    /* Enhanced form controls */
    body .form-control {
        border-radius: 0.25rem !important;
        border: 1px solid #ced4da !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
    }
    
    body .form-control:focus {
        border-color: #86b7fe !important;
        outline: 0 !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
    
    body .form-label {
        margin-bottom: 0.5rem !important;
        font-weight: 500 !important;
        color: #212529 !important;
    }
    
    /* Button styling */
    body .btn-primary {
        background: linear-gradient(135deg, #2bb0ed 0%, #3f8cff 100%) !important;
        border: none !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    body .btn-primary:hover {
        background: linear-gradient(135deg, #1a9fd6 0%, #2e75e0 100%) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Card styling improvements */
    .card {
        border: none !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        transition: box-shadow 0.3s ease-in-out !important;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-header {
        background: #f8f9fa !important;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        font-weight: 600 !important;
    }
    
    /* Modal content styling */
    #labDetails p {
        padding: 0.8rem !important;
        background-color: #f8f9fa !important;
        border-radius: 8px !important;
        border-left: 4px solid #3f8cff !important;
        margin-bottom: 0.8rem !important;
    }
    
    #labDetails p strong {
        display: block !important;
        color: #212529 !important;
        margin-bottom: 0.3rem !important;
    }
</style>
@endpush

@section('admin_content')

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
                             <button class="btn btn-primary btn-sm edit-lab-type" data-type='{!! json_encode($type) !!}'>Edit</button>
                             <button class="btn btn-danger btn-sm delete-lab-type" data-id="{{ $type->LaboratoryTypeID }}">Delete</button>
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
                    <h5 class="modal-title" id="labTypeModalLabel"><i class="fas fa-vial me-2"></i>Manage Lab Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="labTypeForm">
                        @csrf
                        <input type="hidden" id="labTypeID">
                        <div class="mb-3">
                            <label for="labTypeName" class="form-label"><i class="fas fa-tag me-2"></i>Name</label>
                            <input type="text" id="labTypeName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="labTypeDescription" class="form-label"><i class="fas fa-align-left me-2"></i>Description</label>
                            <textarea id="labTypeDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="labTypePrice" class="form-label"><i class="fas fa-dollar-sign me-2"></i>Price</label>
                            <input type="number" id="labTypePrice" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveLabTypeBtn">Save</button>
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
            <form id="createLabForm" method="POST"">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="lab_type" class="form-label">Laboratory Type</label>
                        <!-- <select name="lab_type" id="lab_type" class="form-select" required>
                            <option value="">Select Laboratory Type</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type->LaboratoryTypeID }}">{{ $type->LaboratoryTypeName }}</option>
                            @endforeach
                        </select> -->

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
                                <button class="btn btn-info btn-sm view-details" data-id="{{ $lab->LaboratoryID }}">View</button>
                                <button class="btn btn-primary btn-sm edit-lab" data-lab='{!! json_encode($lab) !!}'>Edit</button>
                                <button class="btn btn-danger btn-sm delete-lab" data-id="{{ $lab->LaboratoryID }}">Delete</button>
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
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Laboratory Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLabForm">
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_lab_type" class="form-label"><i class="fas fa-flask me-2"></i>Laboratory Type</label>
                            <select id="edit_lab_type" class="form-select" required>
                                @foreach($labTypes as $type)
                                    <option value="{{ $type->LaboratoryTypeID }}">{{ $type->LaboratoryTypeName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_patient" class="form-label"><i class="fas fa-user me-2"></i>Patient</label>
                            <select id="edit_patient" class="form-select" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->UserID }}">{{ $patient->FullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_doctor" class="form-label"><i class="fas fa-user-md me-2"></i>Doctor</label>
                            <select id="edit_doctor" class="form-select" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->DoctorID }}">{{ $doctor->user->FullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_date" class="form-label"><i class="fas fa-calendar me-2"></i>Laboratory Date</label>
                            <input type="date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_time" class="form-label"><i class="fas fa-clock me-2"></i>Laboratory Time</label>
                            <input type="time" id="edit_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label"><i class="fas fa-dollar-sign me-2"></i>Price</label>
                            <input type="number" id="edit_price" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateLabBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-flask me-2"></i>Laboratory Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="labDetails">
                    <!-- Laboratory details will be loaded here via AJAX -->
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

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<script>
   
   // Helper function to show modal reliably
   function showModalReliably(modalElement, modalInstance) {
       console.log('Showing modal reliably:', modalElement.id);
       
       try {
           // First attempt: Bootstrap modal method
           if (modalInstance && typeof modalInstance.show === 'function') {
               modalInstance.show();
               console.log('Modal shown via Bootstrap API');
               return true;
           }
       } catch (error) {
           console.warn('Error showing modal via Bootstrap API:', error);
       }
       
       try {
           // Second attempt: jQuery if available
           if (typeof $ !== 'undefined') {
               $(modalElement).modal('show');
               console.log('Modal shown via jQuery');
               return true;
           }
       } catch (error) {
           console.warn('Error showing modal via jQuery:', error);
       }
       
       // Final attempt: Direct DOM manipulation
       try {
           console.log('Trying direct DOM manipulation for modal');
           // Add classes to modal
           modalElement.classList.add('show', 'show-force');
           modalElement.style.display = 'block';
           modalElement.setAttribute('aria-modal', 'true');
           modalElement.removeAttribute('aria-hidden');
           
           // Add class to body
           document.body.classList.add('modal-open');
           
           // Create backdrop if needed
           if (!document.querySelector('.modal-backdrop')) {
               const backdrop = document.createElement('div');
               backdrop.className = 'modal-backdrop fade show modal-backdrop-force';
               document.body.appendChild(backdrop);
               console.log('Modal backdrop created');
           }
           
           console.log('Modal shown via direct DOM manipulation');
           return true;
       } catch (error) {
           console.error('All methods to show modal failed:', error);
           return false;
       }
   }
   
   // Initialize all modals and event listeners
   document.addEventListener('DOMContentLoaded', function() {
       // Initialize modals
       const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
       const editModal = new bootstrap.Modal(document.getElementById('editModal'));
       const labTypeModal = new bootstrap.Modal(document.getElementById('labTypeModal'));
       
       // Connect button event handlers
       document.getElementById('saveLabTypeBtn').addEventListener('click', saveLabType);
       document.getElementById('updateLabBtn').addEventListener('click', updateLab);
       
       // Add event listeners for view details buttons
       document.querySelectorAll('.view-details').forEach(button => {
           button.addEventListener('click', function() {
               const id = this.getAttribute('data-id');
               viewDetails(id);
           });
       });
       
       // Add event listeners for edit lab type buttons
       document.querySelectorAll('.edit-lab-type').forEach(button => {
           button.addEventListener('click', function() {
               try {
                   const type = JSON.parse(this.getAttribute('data-type'));
                   editLabType(type);
               } catch (error) {
                   console.error('Error parsing lab type data:', error);
                   Swal.fire({
                       icon: 'error',
                       title: 'Error',
                       text: 'Could not load lab type details. Please try again.'
                   });
               }
           });
       });
       
       // Add event listeners for delete lab type buttons
       document.querySelectorAll('.delete-lab-type').forEach(button => {
           button.addEventListener('click', function() {
               const id = this.getAttribute('data-id');
               deleteLabType(id);
           });
       });
       
       // Add event listeners for edit lab buttons
       document.querySelectorAll('.edit-lab').forEach(button => {
           button.addEventListener('click', function() {
               try {
                   const lab = JSON.parse(this.getAttribute('data-lab'));
                   editLab(lab);
               } catch (error) {
                   console.error('Error parsing lab data:', error);
                   Swal.fire({
                       icon: 'error',
                       title: 'Error',
                       text: 'Could not load lab details. Please try again.'
                   });
               }
           });
       });
       
       // Add event listeners for delete lab buttons
       document.querySelectorAll('.delete-lab').forEach(button => {
           button.addEventListener('click', function() {
               const id = this.getAttribute('data-id');
               deleteLab(id);
           });
       });
       
       // Connect lab type price to price field
       document.getElementById('lab_type').addEventListener('change', function () {
           const selectedOption = this.options[this.selectedIndex];
           const price = selectedOption.getAttribute('data-price');
           document.getElementById('price').value = price ? price : '';
       });
       
       // Handle lab form submission
       document.getElementById('createLabForm').addEventListener('submit', function (event) {
           event.preventDefault();
           
           const lab_type = document.getElementById('lab_type').value;
           const user_id = document.getElementById('user_id').value;
           const doctor_id = document.getElementById('doctor_id').value;
           const lab_date = document.getElementById('lab_date').value;
           const lab_time = document.getElementById('lab_time').value;
           const price = document.getElementById('price').value;
           const createLabUrl = "{{ route('admin.lab.create') }}";
           
           Swal.fire({
               title: 'Creating...',
               text: 'Please wait while we create the laboratory assignment',
               allowOutsideClick: false,
               didOpen: () => {
                   Swal.showLoading();
               }
           });
           
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
               Swal.fire({
                   icon: 'success',
                   title: 'Success',
                   text: data.message || 'Laboratory assignment created successfully'
               }).then(() => {
                   window.location.reload();
               });
           })
           .catch(error => {
               console.error('Error:', error);
               Swal.fire({
                   icon: 'error',
                   title: 'Error',
                   text: 'Failed to create laboratory assignment.'
               });
           });
       });
   });

   function viewDetails(id) {
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching laboratory details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const url =  `{{ route('admin.lab.details', ['id' => '__id__']) }}`.replace('__id__', id);
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch details. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                Swal.close();
                
                if (!data || !data.labType || !data.patientName || !data.doctorName) {
                    throw new Error('Incomplete data received from the server.');
                }

                const details = `
                    <p><strong>Type:</strong> ${data.labType}</p>
                    <p><strong>Patient:</strong> ${data.patientName}</p>
                    <p><strong>Doctor:</strong> ${data.doctorName}</p>
                    <p><strong>Date:</strong> ${data.labDate}</p>
                    <p><strong>Time:</strong> ${data.labTime}</p>
                    <p><strong>Price:</strong> $${data.price}</p>
                    <p><strong>Result:</strong> ${data.result || 'Pending'}</p>
                `;
                document.getElementById('labDetails').innerHTML = details;

                const modalElement = document.getElementById('viewModal');
                const modal = new bootstrap.Modal(modalElement);
                showModalReliably(modalElement, modal);
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch laboratory details!'
                });
                console.error('Error fetching lab details:', error.message);
            });
    }


    function saveLabType() {
        const id = document.getElementById('labTypeID').value;
        const name = document.getElementById('labTypeName').value;
        const description = document.getElementById('labTypeDescription').value;
        const price = document.getElementById('labTypePrice').value;

        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we save the laboratory type',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = id ? `{{ route('admin.updateLabType', ['id' => '__id__']) }}`.replace('__id__', id) : '{{ route('admin.storeLabType') }}';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ name, description, price }),
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Laboratory type saved successfully'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save laboratory type!'
            });
        });
    }

    function editLabType(type) {
        document.getElementById('labTypeID').value = type.LaboratoryTypeID;
        document.getElementById('labTypeName').value = type.LaboratoryTypeName;
        document.getElementById('labTypeDescription').value = type.description;
        document.getElementById('labTypePrice').value = type.price;

        const modalElement = document.getElementById('labTypeModal');
        const modal = new bootstrap.Modal(modalElement);
        showModalReliably(modalElement, modal);
    }

    function editLab(lab) {
        // Fill data into modal
        document.getElementById('edit_id').value = lab.LaboratoryID;
        document.getElementById('edit_lab_type').value = lab.LaboratoryTypeID;
        document.getElementById('edit_patient').value = lab.UserID;
        document.getElementById('edit_doctor').value = lab.DoctorID;

        // Split date and time if needed
        const [date, time] = lab.LaboratoryDate.split(' ');
        document.getElementById('edit_date').value = date;
        
        // Set time if available
        if (lab.LaboratoryTime) {
            document.getElementById('edit_time').value = lab.LaboratoryTime;
        } else {
            document.getElementById('edit_time').value = '';
        }

        document.getElementById('edit_price').value = lab.TotalPrice;

        // Show modal
        const modalElement = document.getElementById('editModal');
        const modal = new bootstrap.Modal(modalElement);
        showModalReliably(modalElement, modal);
    }

    function updateLab() {
        const id = document.getElementById('edit_id').value || null;
        const labType = document.getElementById('edit_lab_type').value || null;
        const userId = document.getElementById('edit_patient').value || null;
        const doctorId = document.getElementById('edit_doctor').value || null;
        const labDate = document.getElementById('edit_date').value || null;
        const labTime = document.getElementById('edit_time').value || null;
        const price = document.getElementById('edit_price').value || null;
        
        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update the laboratory assignment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = `{{ route('admin.lab.updateLab', ['id' => '__id__']) }}`.replace('__id__', id);

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
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Laboratory assignment updated successfully'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update laboratory assignment.'
            });
        });
    }

    function deleteLab(id) {
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
                const url = `{{ route('admin.lab.delete', ['id' => '__id__']) }}`.replace('__id__', id);
                
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the laboratory assignment',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

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
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'Laboratory assignment has been deleted.'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete laboratory assignment.'
                    });
                });
            }
        });
    }

    function deleteLabType(id) {
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
                const url = `{{ route('admin.deleteLabType', ['id' => '__id__']) }}`.replace('__id__', id);
                
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the laboratory type',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'Laboratory type has been deleted.'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete laboratory type!'
                    });
                });
            }
        });
    }
</script>
@endsection

