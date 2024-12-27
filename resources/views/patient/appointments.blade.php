@extends('patient_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')

<style>
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

</style>

<div class="container mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending Appointments</h6>
                            <h2 class="mb-0">{{ $pendingCount }}</h2>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Approved Appointments</h6>
                            <h2 class="mb-0">{{ $approvedCount }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Appointment -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Request New Appointment</h5>
        </div>
        <div class="card-body">
            <form id="appointmentForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Preferred Date</label>
                        <input type="date" class="form-control" id="appointment_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Preferred Time</label>
                        <input type="time" class="form-control" id="appointment_time" required>
                    </div>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Reason for Visit</label>
                    <input type="text" class="form-control" id="reason" placeholder="e.g., Regular checkup, Follow-up, etc." required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Symptoms</label>
                    <textarea class="form-control" id="symptoms" rows="2" placeholder="Describe your symptoms" required></textarea>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" rows="2" placeholder="Any additional information"></textarea>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Assign Doctor</label>
                    <select class="form-select" id="doctor_id" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->DoctorID }}">{{ $doctor->user->FullName }}</option>
                        @endforeach
                    </select>
                </div>
            
                <input type="hidden" id="user_id" value="{{ Auth::user()->UserID }}"> <!-- Thêm UserID từ user hiện tại -->
            
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Request Appointment
                </button>
            </form>
            
        </div>
    </div>

    <!-- Appointments List -->
    <div class="card">
            <div class="card-header bg-white py-3">
                        <h5 class="mb-0">My Appointments</h5>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <!-- Search Input -->
                            <div class="input-group" style="width: 300px;">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i> <!-- Icon Search -->
                                </span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search appointments...">
                            </div>
                            
                            <!-- Reload Button -->
                            <button class="btn btn-outline-secondary ms-3" id="reloadButton">
                                <i class="fas fa-sync"></i> Reload
                            </button>
                        </div>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Reason</th>
                            <th>DoctorNotes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->AppointmentDate }}</td>
                            <td>{{ $appointment->AppointmentTime }}</td>
                            <td>{{ $appointment->doctor->user->FullName ?? 'Chưa được chỉ định' }}</td>
                            <td>{{ $appointment->Reason }}</td>
                            <td>{{ $appointment['DoctorNotes'] ?? 'No notes provided' }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $appointment['Status'] == 'approved' ? 'success' :
                                    ($appointment['Status'] == 'pending' ? 'warning' : 'danger')
                                }}">
                                    {{ $appointment['Status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info"
                                             onclick="viewDetails({{ $appointment->AppointmentID  }})">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                    @if($appointment['Status'] == 'pending')
                                        <button class="btn btn-primary"
                                                onclick="editAppointment({{ $appointment->AppointmentID  }})">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger"
                                                onclick="cancelAppointment({{ $appointment->AppointmentID  }})">
                                            <i class="fas fa-times"></i>
                                            Cancle
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No appointments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" id="edit_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" id="edit_reason" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Symptoms</label>
                        <textarea class="form-control" id="edit_symptoms" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Doctor</label>
                        <select id="edit_doctor" class="form-select" required>
                            <option value="">Select a Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->DoctorID }}">{{ $doctor->user->FullName }}</option>
                            @endforeach
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateAppointment()">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let detailsModal, editModal;

    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo Bootstrap Modal
        detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
        editModal = new bootstrap.Modal(document.getElementById('editModal'));
       

        // Xử lý sự kiện gửi form tạo cuộc hẹn mới
        document.getElementById('appointmentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            createAppointment();
        });
      
        const searchInput = document.getElementById('searchInput');
        const reloadButton = document.getElementById('reloadButton');
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

    
        reloadButton.addEventListener('click', function() {
            window.location.reload();
        });

    });


    function createAppointment() {
        const data = {
            appointment_date: document.getElementById('appointment_date').value,
            appointment_time: document.getElementById('appointment_time').value,
            reason: document.getElementById('reason').value,
            symptoms: document.getElementById('symptoms').value,
            notes: document.getElementById('notes').value,
            doctor_id: document.getElementById('doctor_id').value
        };
        const url = `{{ route('patient.appointments.store') }}`;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.message) {
                    Swal.fire('Thành công', result.message, 'success').then(() => {
                        document.getElementById('appointmentForm').reset();
                        window.location.reload();
                    });
                } else {
                    console.error('Error:', error);
                    Swal.fire('Lỗi', 'Không thể tạo cuộc hẹn.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi', 'Đã xảy ra lỗi khi tạo cuộc hẹn. <br> kiểm tra ngày hẹn phải sau ngày hiện tại', 'error');
            });
    }


    function viewDetails(appointmentId) {
        const url =  `{{ route('patient.appointments.showDetail', ['id' => '__id__']) }}`.replace('__id__', appointmentId);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(appointment => {
                const content = document.getElementById('detailsContent');
                content.innerHTML = `
                    <div class="mb-3">
                        <strong>Ngày & Giờ:</strong> ${appointment.AppointmentDate} ${appointment.AppointmentTime}
                    </div>
                    <div class="mb-3">
                        <strong>Bác sĩ:</strong> ${appointment.DoctorName || 'Chưa được chỉ định'}
                    </div>
                    <div class="mb-3">
                        <strong>Trạng thái:</strong> ${appointment.Status}
                    </div>
                    <div class="mb-3">
                        <strong>Lý do:</strong> ${appointment.Reason}
                    </div>
                    <div class="mb-3">
                        <strong>Triệu chứng:</strong> ${appointment.Symptoms}
                    </div>
                    ${
                        appointment.Notes
                            ? `<div class="mb-3"><strong>Ghi chú:</strong> ${appointment.Notes}</div>`
                            : ''
                    }
                `;
                detailsModal.show();
            })
            .catch(error => {
                console.error('Error fetching appointment details:', error);
                Swal.fire('Lỗi', 'Không thể tải thông tin cuộc hẹn.', 'error');
            });
    }



    function editAppointment(id) {
        const url =  `{{ route('patient.appointments.show', ['id' => '__id__']) }}`.replace('__id__', id);

        fetch(url)
            .then(response => response.json())
            .then(appointment => {
                document.getElementById('edit_id').value = appointment.AppointmentID;
                document.getElementById('edit_date').value = appointment.AppointmentDate;
                document.getElementById('edit_time').value = appointment.AppointmentTime;
                document.getElementById('edit_reason').value = appointment.Reason;
                document.getElementById('edit_symptoms').value = appointment.Symptoms;
                document.getElementById('edit_notes').value = appointment.Notes || '';
                document.getElementById('edit_doctor').value = appointment.DoctorID || '';
                editModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi', 'Không thể tải thông tin cuộc hẹn.', 'error');
            });
    }

    function updateAppointment() {
        const id = document.getElementById('edit_id').value;
        const data = {
            appointment_date: document.getElementById('edit_date').value,
            appointment_time: document.getElementById('edit_time').value,
            reason: document.getElementById('edit_reason').value,
            symptoms: document.getElementById('edit_symptoms').value,
            notes: document.getElementById('edit_notes').value,
            doctor_id: document.getElementById('edit_doctor').value,
        };
        const url =  `{{ route('patient.appointments.update', ['id' => '__id__']) }}`.replace('__id__', id);
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.message) {
                    Swal.fire('Thành công', result.message, 'success').then(() => {
                        editModal.hide();
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Lỗi', 'Không thể cập nhật cuộc hẹn.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi', 'Đã xảy ra lỗi khi cập nhật cuộc hẹn.', 'error');
            });
    }


    function cancelAppointment(id) {
        const url =  `{{ route('patient.appointments.destroy', ['id' => '__id__']) }}`.replace('__id__', id);

        Swal.fire({
            title: 'Hủy cuộc hẹn',
            text: 'Bạn có chắc chắn muốn hủy cuộc hẹn này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.message) {
                            Swal.fire('Thành công', result.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Lỗi', 'Không thể hủy cuộc hẹn.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Lỗi', 'Đã xảy ra lỗi khi hủy cuộc hẹn.', 'error');
                    });
            }
        });
    }

</script>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush