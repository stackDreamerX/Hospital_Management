@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

    .table tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    /* Form enhancements */
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
    
    body .btn-secondary {
        background-color: #6c757d !important;
        border: none !important;
    }
    
    body .btn-danger {
        background-color: #dc3545 !important;
        border: none !important;
    }
    
    body .btn-danger:hover {
        background-color: #bb2d3b !important;
        transform: translateY(-1px) !important;
    }
</style>
@endpush


@section('admin_content')

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Doctor Management</h2>

    <!-- Add Doctor Panel -->
    <form id="createDoctorForm" method="POST">
        @csrf
        <div class="row">
            <!-- Username -->
            <div class="col-md-4 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <!-- Full Name -->
            <div class="col-md-4 mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required>
            </div>

            <!-- Email Address -->
            <div class="col-md-4 mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <!-- Phone Number -->
            <div class="col-md-4 mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </div>

            <!-- Password -->
            <div class="col-md-4 mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary password-toggle" onclick="togglePasswordVisibility(this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="password-feedback"></div>
            </div>

            <!-- Confirm Password -->
            <div class="col-md-4 mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required oninput="validatePasswords()">
                    <button type="button" class="btn btn-outline-secondary password-toggle" onclick="togglePasswordVisibility(this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="confirm-password-feedback"></div>
            </div>

            <!-- Speciality -->
            <div class="col-md-6 mb-3">
                <label for="speciality" class="form-label">Speciality</label>
                <select id="speciality" name="speciality" class="form-control" required>
                    <option value="">Select Speciality</option>
                    <option value="Anesthesiology">Anesthesiology</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Emergency Medicine">Emergency Medicine</option>
                    <option value="Endocrinology">Endocrinology</option>
                    <option value="Family Medicine">Family Medicine</option>
                    <option value="Gastroenterology">Gastroenterology</option>
                    <option value="General Surgery">General Surgery</option>
                    <option value="Geriatric Medicine">Geriatric Medicine</option>
                    <option value="Gynecology">Gynecology</option>
                    <option value="Hematology">Hematology</option>
                    <option value="Infectious Disease">Infectious Disease</option>
                    <option value="Internal Medicine">Internal Medicine</option>
                    <option value="Nephrology">Nephrology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Neurosurgery">Neurosurgery</option>
                    <option value="Obstetrics">Obstetrics</option>
                    <option value="Oncology">Oncology</option>
                    <option value="Ophthalmology">Ophthalmology</option>
                    <option value="Orthopedics">Orthopedics</option>
                    <option value="Otolaryngology">Otolaryngology</option>
                    <option value="Pathology">Pathology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Plastic Surgery">Plastic Surgery</option>
                    <option value="Psychiatry">Psychiatry</option>
                    <option value="Pulmonology">Pulmonology</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Rheumatology">Rheumatology</option>
                    <option value="Urology">Urology</option>
                    <option value="Vascular Surgery">Vascular Surgery</option>
                </select>
            </div>

            <!-- Title -->
            <div class="col-md-6 mb-3">
                <label for="title" class="form-label">Title</label>
                <select id="title" name="title" class="form-control" required>
                    <option value="">Select Title</option>
                    <option value="Resident Doctor">Resident Doctor</option>
                    <option value="Medical Officer">Medical Officer</option>
                    <option value="Senior Medical Officer">Senior Medical Officer</option>
                    <option value="Specialist">Specialist</option>
                    <option value="Senior Specialist">Senior Specialist</option>
                    <option value="Consultant">Consultant</option>
                    <option value="Senior Consultant">Senior Consultant</option>
                    <option value="Chief Consultant">Chief Consultant</option>
                    <option value="Head of Department">Head of Department</option>
                    <option value="Clinical Director">Clinical Director</option>
                    <option value="Medical Director">Medical Director</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create Doctor</button>
    </form>



    <!-- Doctors List -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Doctors List</h5>
            <div class="d-flex justify-content-end">
                 <!-- Nút Reload -->
                <button id="reloadButton" class="btn btn-outline-primary btn-sm" style="margin-right: 10px;">
                    <i class="fas fa-sync-alt"></i> Reload
                </button>
                <!-- Thanh tìm kiếm -->
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search doctors...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table" id="doctorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Speciality</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->DoctorID }}</td>
                            <td>{{ $doctor->user->FullName }}</td>
                            <td>{{ $doctor->user->username }}</td>
                            <td>{{ $doctor->user->Email }}</td>
                            <td>{{ $doctor->user->PhoneNumber }}</td>
                            <td>{{ $doctor->Speciality }}</td>
                            <td>{{ $doctor->Title }}</td>
                            <td>
                                <!-- Nút Edit -->
                                <button class="btn btn-primary btn-sm edit-doctor" 
                                        style="margin-right: 5px;"
                                        data-doctor='{!! json_encode($doctor) !!}'>
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <!-- Nút Delete -->
                                <button class="btn btn-danger btn-sm delete-doctor"
                                        data-id="{!! $doctor->DoctorID !!}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>




<!-- Edit Modal -->
<div class="modal fade" id="editModal" aria-labelledby="editModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-user-md me-2"></i>Edit Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
                            <input type="text" id="edit_fullname" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-user-tag me-2"></i>Username</label>
                            <input type="text" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                            <input type="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-phone me-2"></i>Phone Number</label>
                            <input type="tel" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-stethoscope me-2"></i>Speciality</label>
                            <select id="edit_speciality" class="form-control" required>
                            <option value="">Select Speciality</option>
                            <option value="Anesthesiology">Anesthesiology</option>
                            <option value="Cardiology">Cardiology</option>
                            <option value="Dermatology">Dermatology</option>
                            <option value="Emergency Medicine">Emergency Medicine</option>
                            <option value="Endocrinology">Endocrinology</option>
                            <option value="Family Medicine">Family Medicine</option>
                            <option value="Gastroenterology">Gastroenterology</option>
                            <option value="General Surgery">General Surgery</option>
                            <option value="Geriatric Medicine">Geriatric Medicine</option>
                            <option value="Gynecology">Gynecology</option>
                            <option value="Hematology">Hematology</option>
                            <option value="Infectious Disease">Infectious Disease</option>
                            <option value="Internal Medicine">Internal Medicine</option>
                            <option value="Nephrology">Nephrology</option>
                            <option value="Neurology">Neurology</option>
                            <option value="Neurosurgery">Neurosurgery</option>
                            <option value="Obstetrics">Obstetrics</option>
                            <option value="Oncology">Oncology</option>
                            <option value="Ophthalmology">Ophthalmology</option>
                            <option value="Orthopedics">Orthopedics</option>
                            <option value="Otolaryngology">Otolaryngology</option>
                            <option value="Pathology">Pathology</option>
                            <option value="Pediatrics">Pediatrics</option>
                            <option value="Plastic Surgery">Plastic Surgery</option>
                            <option value="Psychiatry">Psychiatry</option>
                            <option value="Pulmonology">Pulmonology</option>
                            <option value="Radiology">Radiology</option>
                            <option value="Rheumatology">Rheumatology</option>
                            <option value="Urology">Urology</option>
                            <option value="Vascular Surgery">Vascular Surgery</option>

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-award me-2"></i>Title</label>
                            <select id="edit_title" class="form-control" required>
                            <option value="">Select Title</option>
                            <option value="Resident Doctor">Resident Doctor</option>
                            <option value="Medical Officer">Medical Officer</option>
                            <option value="Senior Medical Officer">Senior Medical Officer</option>
                            <option value="Specialist">Specialist</option>
                            <option value="Senior Specialist">Senior Specialist</option>
                            <option value="Consultant">Consultant</option>
                            <option value="Senior Consultant">Senior Consultant</option>
                            <option value="Chief Consultant">Chief Consultant</option>
                            <option value="Head of Department">Head of Department</option>
                            <option value="Clinical Director">Clinical Director</option>
                            <option value="Medical Director">Medical Director</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let editModal;
    
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('editModal');
        if (modalElement) {
            editModal = new bootstrap.Modal(modalElement);
        } else {
            console.warn('Edit modal not found!');
        }

        // Password fields
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        
        // Add input event for password field to trigger validation
        if (passwordField) {
            passwordField.addEventListener('input', validatePasswords);
        }

        // Add event listeners to edit buttons
        document.querySelectorAll('.edit-doctor').forEach(button => {
            button.addEventListener('click', function() {
                try {
                    const doctor = JSON.parse(this.getAttribute('data-doctor'));
                    editDoctor(doctor);
                } catch (error) {
                    console.error('Error parsing doctor data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not load doctor details. Please try again.'
                    });
                }
            });
        });
        
        // Add event listeners to delete buttons
        document.querySelectorAll('.delete-doctor').forEach(button => {
            button.addEventListener('click', function() {
                const doctorId = this.getAttribute('data-id');
                deleteDoctor(doctorId);
            });
        });
        
        // Save changes button
        document.getElementById('saveChangesBtn').addEventListener('click', updateDoctor);

        const searchInput = document.getElementById('searchInput');
        const tableBody = document.querySelector('.table tbody');
        if (searchInput && tableBody) {
            const rows = tableBody.querySelectorAll('tr');
            searchInput.addEventListener('keyup', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                rows.forEach(row => {
                    const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const username = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const phone = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

                    if (fullName.includes(searchTerm) || username.includes(searchTerm) || email.includes(searchTerm) || phone.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        } else {
            console.warn('Search input or table body not found!');
        }
    });

    // Function to toggle password visibility
    function togglePasswordVisibility(button) {
        const input = button.previousElementSibling;
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Function to validate password matching
    function validatePasswords() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const feedback = document.getElementById('confirm-password-feedback');
        
        if (!password || !confirmPassword || !feedback) return;
        
        // Check if passwords match when both fields have values
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            feedback.textContent = 'Passwords do not match';
            return false;
        } else {
            confirmPassword.classList.remove('is-invalid');
            feedback.textContent = '';
            return true;
        }
    }

    function setValueSafe(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || '';
        } else {
            console.warn(`Element with ID "${id}" not found!`);
        }
    }

    function editDoctor(doctor) {
        console.log('Doctor data:', doctor);

        const user = doctor.user;
        if (!user) {
            console.error('User data not found!');
            return;
        }
        console.log('user data:', user);
        setValueSafe('edit_id', doctor.DoctorID);
        setValueSafe('edit_fullname', user.FullName);
        setValueSafe('edit_username', user.username);
        setValueSafe('edit_email', user.Email);
        setValueSafe('edit_phone', user.PhoneNumber);
        setValueSafe('edit_speciality', doctor.Speciality);
        setValueSafe('edit_title', doctor.Title);
        
        // Show the modal with our reliable function
        showModalReliably(document.getElementById('editModal'), editModal);
    }

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

    // Xử lý khi nhấn nút "Save changes"
    function updateDoctor() {
        const doctorID = document.getElementById('edit_id').value;

        const formData = {
            fullname: document.getElementById('edit_fullname').value,
            username: document.getElementById('edit_username').value,
            email: document.getElementById('edit_email').value,
            phone: document.getElementById('edit_phone').value,
            speciality: document.getElementById('edit_speciality').value,
            title: document.getElementById('edit_title').value,
            _method: 'PUT', // Laravel cần method PUT
            _token: '{{ csrf_token() }}', // CSRF Token
        };

        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update the doctor information',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`staff/edit/${doctorID}`, {
            method: 'POST', // Vì fetch không hỗ trợ PUT trực tiếp
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData),
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Doctor updated successfully'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update doctor information'
            });
        });
    }

    function deleteDoctor(doctorId) {
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
                fetch(`staff/delete/${doctorId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to delete the doctor.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Deleted!', 'Doctor has been deleted.', 'success');
                    // Reload the page after successful deletion
                    window.location.reload();
                })
                .catch(error => {
                    Swal.fire('Error!', error.message || 'An unexpected error occurred.', 'error');
                });
            }
        });
    }

    // Update the reload button to use SweetAlert for better UX
    document.getElementById('reloadButton').addEventListener('click', function() {
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching the latest doctor information',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Gọi API lấy dữ liệu mới
        fetch("{{ route('admin.getDoctorsList') }}")
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#doctorsTable tbody');
                tableBody.innerHTML = ''; // Xóa dữ liệu cũ
                
                data.forEach(doctor => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${doctor.DoctorID}</td>
                        <td>${doctor.user.FullName}</td>
                        <td>${doctor.user.username}</td>
                        <td>${doctor.user.Email}</td>
                        <td>${doctor.user.PhoneNumber}</td>
                        <td>${doctor.Speciality}</td>
                        <td>${doctor.Title}</td>
                        <td>
                            <button class="btn btn-primary btn-sm edit-doctor" 
                                    style="margin-right: 5px;"
                                    data-doctor='${JSON.stringify(doctor)}'>
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-doctor"
                                    data-id="${doctor.DoctorID}">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                    
                    // Re-attach event listeners to new buttons
                    row.querySelector('.edit-doctor').addEventListener('click', function() {
                        try {
                            const doctorData = JSON.parse(this.getAttribute('data-doctor'));
                            editDoctor(doctorData);
                        } catch (error) {
                            console.error('Error parsing doctor data:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Could not load doctor details. Please try again.'
                            });
                        }
                    });
                    
                    row.querySelector('.delete-doctor').addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteDoctor(id);
                    });
                });
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Doctor list has been updated',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error fetching doctor list:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load doctor list. Please try again.'
                });
            });
    });

    //CreateDoctorForm
    document.getElementById('createDoctorForm').addEventListener('submit', function (event) {
        event.preventDefault();

        // Validate passwords before submitting
        if (!validatePasswords()) {
            return;
        }

        const username = document.getElementById('username').value;
        const fullname = document.getElementById('fullname').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const confirm_password = document.getElementById('confirm_password').value;
        const speciality = document.getElementById('speciality').value;
        const title = document.getElementById('title').value;
        
        Swal.fire({
            title: 'Creating...',
            text: 'Please wait while we create the new doctor',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("admin.createDoctor") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                username,
                fullname,
                email,
                phone,
                password,
                confirm_password,
                speciality,
                title
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message
                }).then(() => {
                    // Reset form after successful creation
                    document.getElementById('createDoctorForm').reset();
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to create doctor'
            });
        });
    });
</script>
@endsection



