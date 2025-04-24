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
</style>
@endpush


@section('admin_content')

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">User Management</h2>
        

    <!-- User List -->
    <div class="card mb-4" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">User List</div>
        <div class="card-body" style="padding: 20px;">
        <form id="addUserForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Full Name</label>
                        <input type="text" name="fullname" class="form-control" required style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Username</label>
                        <input type="text" name="username" class="form-control" required
                               pattern="^[a-zA-Z0-9._-]{3,50}$" style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Email</label>
                        <input type="email" name="email" class="form-control" required style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required
                               pattern="^0[0-9]{9}$" style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Role</label>
                        <select name="role" class="form-control" required style="padding: 8px;">
                            <option value="">Select Role</option>
                            <option value="doctor">Doctor</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Password</label>
                        <input type="password" name="password" class="form-control" required
                               pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                               style="padding: 8px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Add User</button>
            </form>


            <div class="mt-4">
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search users...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Full Name</th>
                        <th style="padding: 12px;">Email</th>
                        <th style="padding: 12px;">Phone</th>
                        <th style="padding: 12px;">Gender</th>                    
                        <th style="padding: 12px;">Role</th>
                        <th style="padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $user->UserID }}</td>
                        <td style="padding: 12px;">{{ $user->FullName }}</td>
                        <td style="padding: 12px;">{{ $user->Email }}</td>
                        <td style="padding: 12px;">{{ $user->PhoneNumber }}</td>
                        <td style="padding: 12px;">Male</td>                        
                        <td style="padding: 12px;">{{ $user->RoleID }}</td>
                        <td style="padding: 12px;">
                            <button class="btn btn-primary btn-sm edit-user" data-user='{!! json_encode($user) !!}' style="margin-right: 5px;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->UserID }}">
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

<!-- Include Edit Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label"><i class="fas fa-phone me-2"></i>Phone</label>
                        <input type="tel" id="edit_phone" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gender" class="form-label"><i class="fas fa-venus-mars me-2"></i>Gender</label>
                        <select id="edit_gender" name="gender" class="form-control" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_date_of_birth" class="form-label"><i class="fas fa-birthday-cake me-2"></i>Date of Birth</label>
                        <input type="date" id="edit_date_of_birth" name="date_of_birth" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label"><i class="fas fa-user-tag me-2"></i>Role</label>
                        <select id="edit_role" name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Address</label>
                        <textarea id="edit_address" name="address" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modals
        const userModal = new bootstrap.Modal(document.getElementById('userModal'));
        
        // Add event listeners to edit user buttons
        document.querySelectorAll('.edit-user').forEach(button => {
            button.addEventListener('click', function() {
                try {
                    const user = JSON.parse(this.getAttribute('data-user'));
                    editUser(user);
                } catch (error) {
                    console.error('Error parsing user data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not load user details. Please try again.'
                    });
                }
            });
        });
        
        // Add event listeners to delete user buttons
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                deleteUser(id);
            });
        });
        
        // Connect save button event handler
        document.getElementById('saveUserBtn').addEventListener('click', function() {
            const userId = document.getElementById('edit_user_id').value;
            updateUser(userId);
        });
    });

    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        
        Swal.fire({
            title: 'Adding User...',
            text: 'Please wait while we create the user',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('admin.users.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'User added successfully'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add user.'
            });
        });
    });

    function editUser(user) {
        // Set user data in the form
        document.getElementById('edit_user_id').value = user.UserID;
        document.getElementById('edit_name').value = user.FullName || '';
        document.getElementById('edit_email').value = user.Email || '';
        document.getElementById('edit_phone').value = user.PhoneNumber || '';
        
        // Set optional fields if available
        if (user.Gender) {
            document.getElementById('edit_gender').value = user.Gender.toLowerCase();
        }
        
        if (user.DateOfBirth) {
            document.getElementById('edit_date_of_birth').value = user.DateOfBirth;
        }
        
        if (user.RoleID) {
            const roleMap = {
                '1': 'admin',
                '2': 'doctor',
                '3': 'patient'
            };
            document.getElementById('edit_role').value = roleMap[user.RoleID] || 'patient';
        }
        
        if (user.Address) {
            document.getElementById('edit_address').value = user.Address;
        }

        // Show the modal
        const modalElement = document.getElementById('userModal');
        const modalInstance = new bootstrap.Modal(modalElement);
        showModalReliably(modalElement, modalInstance);
    }

    function updateUser(userId) {
        // Get form data
        const formData = new FormData(document.getElementById('editUserForm'));
        
        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update the user',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Create the URL with the user ID
    //   const url = `/admin/patient/${userId}`;
        const url = `{{ route('admin.patient.update', ['id' => '__id__']) }}`.replace('__id__', userId);
       console.log(url);
        fetch(url, {
            method: 'POST', // Use POST as defined in the routes
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData,
        })
        .then(response => {
            // Check if the response is OK
            if (!response.ok) {
                // Get the response text for debugging
                return response.text().then(text => {
                    console.error('Response not OK:', response.status, text);
                    throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'User updated successfully'
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update user information. Check console for details.'
            });
        });
    }

    function deleteUser(id) {
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
                const url = `{{ route('admin.patient.destroy', ['id' => '__id__']) }}`.replace('__id__', id);
                
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the user',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'User has been deleted.'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete user.'
                    });
                });
            }
        });
    }
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
