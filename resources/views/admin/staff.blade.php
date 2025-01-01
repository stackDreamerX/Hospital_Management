@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


@section('admin_content')

<style>

    modal {
  display: none; 
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050; 
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.5); 
}

.modal.fade {
  opacity: 0;
  transition: opacity 0.15s linear;
}

.modal.show {
  display: block;
  opacity: 1;
}

.modal-dialog {
  position: relative;
  margin: 1.75rem auto;
  pointer-events: auto;
  max-width: 500px; 
}

.modal-dialog.modal-lg {
  max-width: 800px; 
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  background-color: #fff;
  border: none;
  border-radius: 0.5rem; 
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); 
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1rem;
  border-bottom: 1px solid #dee2e6; 
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
}

.btn-close {
  background: none;
  border: none;
  -webkit-appearance: none;
}

.modal-body {
  position: relative;
  flex: 1 1 auto;
  padding: 1rem;
}

.modal-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 1rem;
  border-top: 1px solid #dee2e6;
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
    <form id="createDoctorForm" method="POST">
        @csrf
        <div class="row">
            <!-- Username -->
            <div class="col-md-4 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <!-- Speciality -->
            <div class="col-md-4 mb-3">
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
            <div class="col-md-4 mb-3">
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
                                <button onclick="editDoctor({{ json_encode($doctor) }})"
                                        class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <!-- Nút Delete -->
                                <button onclick="deleteDoctor({{ json_encode($doctor->DoctorID)}})"
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
<div class="modal fade" id="editModal" aria-labelledby="testModalLabel" aria-hidden="true" tabindex="-1" >
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
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
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

        fetch(`staff/edit/${doctorID}`, {
            method: 'POST', // Vì fetch không hỗ trợ PUT trực tiếp
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
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
                const row = document.querySelector(`tr[data-id="${doctorId}"]`);
                if (row) row.remove(); // Xóa dòng khỏi bảng
                // Kích hoạt nút reload
                document.getElementById('reloadButton').click();
            })
            .catch(error => {
                Swal.fire('Error!', error.message || 'An unexpected error occurred.', 'error');
            });
        }
    });
}


    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#doctorsTable tbody tr');

        rows.forEach(row => {
            const fullname = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const username = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const phone = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

            if (fullname.includes(searchTerm) || username.includes(searchTerm) || email.includes(searchTerm) || phone.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

//Fetch API reload doctor list
    document.getElementById('reloadButton').addEventListener('click', function() {
        const tableBody = document.querySelector('#doctorsTable tbody');
        tableBody.innerHTML = ''; // Xóa dữ liệu cũ

        // Gọi API lấy dữ liệu mới
        fetch("{{ route('admin.getDoctorsList') }}")
            .then(response => response.json())
            .then(data => {
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
                            <button onclick="editDoctor(${JSON.stringify(doctor)})"
                                    class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteDoctor(${doctor.DoctorID})"
                                    class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching doctor list:', error);
            });
    });

//CreateDoctorForm
    document.getElementById('createDoctorForm').addEventListener('submit', function (event) {
        event.preventDefault(); 

        const username = document.getElementById('username').value;
        const speciality = document.getElementById('speciality').value;
        const title = document.getElementById('title').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('{{ route('admin.createDoctor') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                username,
                speciality,
                title,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create doctor.',
                });
            });
    });



</script>
@endsection



