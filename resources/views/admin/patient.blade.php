@extends('admin_layout')
@section('admin_content')

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Patient Management</h2>
    
    <!-- Create New Patient Form -->
    <div class="card mb-4" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Create New Patient</div>
        <div class="card-body" style="padding: 20px;">
            <form id="createPatientForm">
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
                        <label style="font-weight: bold; margin-bottom: 5px;">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" required style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Gender</label>
                        <select name="gender" class="form-control" required style="padding: 8px;">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Address</label>
                        <textarea name="address" class="form-control" rows="3" style="padding: 8px;"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" required 
                                   pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                                   style="padding: 8px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Create Patient</button>
            </form>
        </div>
    </div>

    <!-- Patient List -->
    <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Patient List</div>
        <div class="card-body" style="padding: 20px;">
            <div class="mb-3">
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search patients...">
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
                        <th style="padding: 12px;">Age</th>
                        <th style="padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $patient['PatientID'] }}</td>
                        <td style="padding: 12px;">{{ $patient['FullName'] }}</td>
                        <td style="padding: 12px;">{{ $patient['Email'] }}</td>
                        <td style="padding: 12px;">{{ $patient['PhoneNumber'] }}</td>
                        <td style="padding: 12px;">{{ ucfirst($patient['Gender']) }}</td>
                        <td style="padding: 12px;">
                            @php
                                $birthDate = new DateTime($patient['DateOfBirth']);
                                $today = new DateTime();
                                $age = $today->diff($birthDate)->y;
                            @endphp
                            {{ $age }}
                        </td>
                        <td style="padding: 12px;">
                            <button onclick="editPatient({{ json_encode($patient) }})" 
                                    class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button onclick="deletePatient({{ $patient['PatientID'] }})" 
                                    class="btn btn-danger btn-sm">
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
@include('admin.modals.patient_modal')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let editModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('patientModal'));
        
        // Add form submit handler
        document.getElementById('createPatientForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Patient created successfully!'
            }).then(() => {
                window.location.reload();
            });
        });

        // Add search functionality
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.querySelector('.table tbody');
        const rows = tableBody.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            rows.forEach(row => {
                const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const phone = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                if (fullName.includes(searchTerm) || 
                    email.includes(searchTerm) || 
                    phone.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    function editPatient(patient) {
        document.getElementById('edit_id').value = patient.PatientID;
        document.getElementById('edit_fullname').value = patient.FullName;
        document.getElementById('edit_username').value = patient.Username;
        document.getElementById('edit_email').value = patient.Email;
        document.getElementById('edit_phone').value = patient.PhoneNumber;
        document.getElementById('edit_dob').value = patient.DateOfBirth;
        document.getElementById('edit_gender').value = patient.Gender;
        document.getElementById('edit_address').value = patient.Address;
        document.getElementById('edit_password').value = '';
        
        editModal.show();
    }

    function deletePatient(id) {
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
                Swal.fire('Deleted!', 'Patient has been deleted.', 'success')
                .then(() => {
                    window.location.reload();
                });
            }
        });
    }

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
</script>
@endsection
