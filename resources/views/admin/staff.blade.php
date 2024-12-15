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

    .table tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
</style>

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Doctor Management</h2>

    <!-- Add Doctor Panel -->
    <div class="card mb-4" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Add New Doctor</div>
        <div class="card-body" style="padding: 20px;">
            <form id="createDoctorForm">
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
                        <label style="font-weight: bold; margin-bottom: 5px;">Speciality</label>
                        <select name="speciality" class="form-control" required style="padding: 8px;">
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
                        <label style="font-weight: bold; margin-bottom: 5px;">Title</label>
                        <select name="title" class="form-control" required style="padding: 8px;">
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
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Create Doctor</button>
            </form>
        </div>
    </div>

    <!-- Doctors List -->
    <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Doctors List</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search doctors...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 20px;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Full Name</th>
                        <th style="padding: 12px;">Username</th>
                        <th style="padding: 12px;">Email</th>
                        <th style="padding: 12px;">Phone</th>
                        <th style="padding: 12px;">Speciality</th>
                        <th style="padding: 12px;">Title</th>
                        <th style="padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $doctor['DoctorID'] }}</td>
                        <td style="padding: 12px;">{{ $doctor['FullName'] }}</td>
                        <td style="padding: 12px;">{{ $doctor['Username'] }}</td>
                        <td style="padding: 12px;">{{ $doctor['Email'] }}</td>
                        <td style="padding: 12px;">{{ $doctor['PhoneNumber'] }}</td>
                        <td style="padding: 12px;">{{ $doctor['Speciality'] }}</td>
                        <td style="padding: 12px;">{{ $doctor['Title'] }}</td>
                        <td style="padding: 12px;">
                            <button onclick="editDoctor({{ json_encode($doctor) }})" 
                                    class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteDoctor({{ $doctor['DoctorID'] }})" 
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="edit_fullname" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Speciality</label>
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
                            <label class="form-label">Title</label>
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password (leave blank to keep current)</label>
                            <div class="input-group">
                                <input type="password" id="edit_password" class="form-control">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateDoctor()">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let editModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('editModal'));
        
        // Add form submit handler
        document.getElementById('createDoctorForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Doctor created successfully!'
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
                const speciality = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                const title = row.querySelector('td:nth-child(7)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const phone = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

                if (fullName.includes(searchTerm) || 
                    speciality.includes(searchTerm) || 
                    title.includes(searchTerm) ||
                    email.includes(searchTerm) || 
                    phone.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    function editDoctor(doctor) {
        document.getElementById('edit_id').value = doctor.DoctorID;
        document.getElementById('edit_fullname').value = doctor.FullName;
        document.getElementById('edit_username').value = doctor.Username;
        document.getElementById('edit_email').value = doctor.Email;
        document.getElementById('edit_phone').value = doctor.PhoneNumber;
        document.getElementById('edit_speciality').value = doctor.Speciality;
        document.getElementById('edit_title').value = doctor.Title;
        document.getElementById('edit_password').value = '';
        
        editModal.show();
    }

    function updateDoctor() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Doctor updated successfully!'
        }).then(() => {
            editModal.hide();
            window.location.reload();
        });
    }

    function deleteDoctor(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Doctor has been deleted.', 'success')
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