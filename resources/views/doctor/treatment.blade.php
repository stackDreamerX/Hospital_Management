@extends('doctor_layout');

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush


@section('content')


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

.modal-footer .btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
}

.modal-footer .btn-success:hover {
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
    transform: translateY(-3px);
}

/* Treatment details styling */
#treatmentDetails p {
    margin-bottom: 1rem;
    padding: 0.8rem 1rem;
    background-color: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

#treatmentDetails strong {
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

/* Custom select styling */
.custom-select {
    padding: 0.75rem 1rem;
    border-radius: 10px;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
}

.custom-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 146, 216, 0.25);
}

/* Enhance the action buttons */
.btn-group .btn {
    margin: 0 2px;
    border-radius: 8px;
    padding: 0.4rem 0.8rem;
    transition: all 0.3s ease;
    border: none;
}

.btn-group .btn-info {
    background: linear-gradient(135deg, #2bb0ed 0%, #3f8cff 100%);
    box-shadow: 0 4px 10px rgba(63, 140, 255, 0.2);
}

.btn-group .btn-warning {
    background: linear-gradient(135deg, #ffa733 0%, #ff8c00 100%);
    box-shadow: 0 4px 10px rgba(255, 140, 0, 0.2);
}

.btn-group .btn-danger {
    background: linear-gradient(135deg, #ff5f6d 0%, #ff427f 100%);
    box-shadow: 0 4px 10px rgba(255, 66, 127, 0.2);
}

.btn-group .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.btn-group .btn i {
    margin-right: 5px;
}
</style>


<div class="container mt-4">
    <!-- Create New Treatment -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Assign New Treatment</h5>
        </div>
        <div class="card-body">
        <form id="treatmentForm"  method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Patient</label>
                    <select id="patient_id" class="form-select" name="patient_id" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->UserID }}">{{ $patient->FullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Treatment Type</label>
                    <select id="treatment_type" class="form-select" name="treatment_type" required>
                        <option value="">Select Type</option>
                        @foreach($treatmentTypes as $type)
                            <option value="{{ $type-> TreatmentTypeID }}">{{ $type ->TreatmentTypeName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" id="treatment_date" name="treatment_date" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration</label>
                    <input type="text" class="form-control" id="duration" name="duration" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Price</label>
                    <input type="number" class="form-control" id="total_price" name="total_price" min="0" placeholder="Enter total price" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Assign Treatment</button>
        </form>

        </div>
    </div>

    <!-- Treatment List -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Treatment List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment->TreatmentDate }}</td>
                            <td>{{ $treatment->user->FullName }}</td>
                            <td>{{ $treatment->treatmentType->TreatmentTypeName  }}</td>
                            <td>{{ $treatment->Duration }}</td>
                            <td>{{ number_format($treatment->TotalPrice, 2) }} $</td>
                            <td>
                                <span class="badge bg-{{
                                    $treatment->Status === 'Completed' ? 'success' :
                                    ($treatment->Status === 'Scheduled' ? 'info' : 'primary')
                                }}">
                                    {{
                                        $treatment->Status === 'Completed' ? 'Completed' :
                                        ($treatment->Status === 'Scheduled' ? 'Scheduled' : $treatment->Status)
                                    }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info btn-sm" onclick="viewTreatment('{{ $treatment->TreatmentID }}')">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editTreatment('{{ $treatment->TreatmentID }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteTreatment('{{ $treatment->TreatmentID }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No treatments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editTreatmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2 text-primary"></i>Edit Treatment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">Update Status</h6>
                <select class="form-select custom-select" id="editStatus">
                    <option value="Scheduled">Scheduled</option>
                    @for ($i = 10; $i <= 90; $i += 10)
                        <option value="{{ $i }}">Progress {{ $i }}%</option>
                    @endfor
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveEditBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Treatment Modal -->
<div class="modal fade" id="treatmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Treatment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="treatmentDetails" class="p-2">
                    <!-- Details will be loaded here -->
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
    document.getElementById('treatmentForm').addEventListener('submit', function (e) {
        e.preventDefault();
        createTreatment();
    });
    
    // Add event listener for save edit button
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('saveEditBtn').addEventListener('click', function() {
            saveEdit();
        });
    });
    
    // Tạo treatment
    function createTreatment() {
        const patientIdElement = document.getElementById('patient_id');
        const treatmentTypeElement = document.getElementById('treatment_type');
        const descriptionElement = document.getElementById('description');
        const treatmentDateElement = document.getElementById('treatment_date');
        const durationElement = document.getElementById('duration');
        const notesElement = document.getElementById('notes');
        const total_price = document.getElementById('total_price');

        if (!patientIdElement || !treatmentTypeElement || !descriptionElement || !treatmentDateElement || !durationElement || !notesElement) {
            console.error('One or more required elements are missing');
            return;
        }

        const data = {
            patient_id: patientIdElement.value,
            treatment_type: treatmentTypeElement.value,
            description: descriptionElement.value,
            treatment_date: treatmentDateElement.value,
            duration: durationElement.value,
            notes: notesElement.value,
            total_price: total_price.value
        };
        
        const createLabUrl = "{{ route('doctor.treatment.store') }}";
        console.log(createLabUrl);
        fetch(createLabUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(data),
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire('Success', result.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', result.message || 'Failed to assign treatment', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            });
    }

    // Hiển thị modal chỉnh sửa
    function editTreatment(id) {
        const url = `{{ route('doctor.treatments.show', ['id' => '__id__']) }}`.replace('__id__', id);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch treatment for edit. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Parse the status to set the correct value in dropdown
                let progressValue = '0';
                
                if (data.Status === 'Completed') {
                    progressValue = 'Completed';
                } else if (data.Status === 'Scheduled') {
                    progressValue = 'Scheduled';
                } else {
                    // Extract percentage value from status like "Progress 20%"
                    const match = data.Status.match(/(\d+)/);
                    progressValue = match ? match[0] : '0';
                }
                
                document.getElementById('editStatus').value = progressValue;
                document.getElementById('saveEditBtn').setAttribute('data-id', id);

                const modal = new bootstrap.Modal(document.getElementById('editTreatmentModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error fetching treatment for edit:', error.message);
                alert('Failed to load treatment details for editing.');
            });
    }


    function saveEdit() {
        const id = document.getElementById('saveEditBtn').getAttribute('data-id');
        const progress = document.getElementById('editStatus').value;
        
        // Format the status to prevent SQL truncation issues
        let status;
        if (progress === 'Completed') {
            status = 'Completed';
        } else if (progress === 'Scheduled') {
            status = 'Scheduled';
        } else {
            // Format the progress percentage without parentheses to avoid truncation
            status = `Progress ${progress}%`;
        }
        
        // Display processing message             
        Swal.fire({
            title: 'Processing...',
            text: 'Updating treatment status',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
                     
        // Use the direct URL path approach instead of route helper
        const url = `/Hospital_Management/doctor/treatments/${id}/updateTreatment`;
        console.log('Sending update request to:', url);
        console.log('Status value:', status);

        // Create form data
        const formData = new FormData();
        formData.append('status', status);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // For debugging
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Error response text:', text);
                        throw new Error(`Server returned ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success response:', data);
                Swal.fire('Success', data.message, 'success').then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                console.error('Error updating treatment:', error);
                Swal.fire('Error', 'Failed to update treatment. Check the console for details.', 'error');
            });
    }


   

    function viewTreatment(id) {       
        const url = `{{ route('doctor.treatments.show', ['id' => '__id__']) }}`.replace('__id__', id);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch treatment. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const details = `
                    <p><strong>Patient:</strong> ${data.PatientName}</p>
                    <p><strong>Type:</strong> ${data.TreatmentTypeName}</p>
                    <p><strong>Date:</strong> ${data.TreatmentDate}</p>
                    <p><strong>Duration:</strong> ${data.Duration}</p>
                    <p><strong>Total Price:</strong> $${parseFloat(data.TotalPrice).toFixed(2)}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(data.Status)}">${data.Status}</span></p>
                    <p><strong>Description:</strong> ${data.Description}</p>
                    <p><strong>Notes:</strong> ${data.Notes || 'N/A'}</p>
                `;
                document.getElementById('treatmentDetails').innerHTML = details;

                const modal = new bootstrap.Modal(document.getElementById('treatmentModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error fetching treatment:', error.message);
                alert('Failed to load treatment details.');
            });
    }
    
    function getStatusColor(status) {
        if (status === 'Completed') return 'success';
        if (status === 'Scheduled') return 'info';
        return 'primary'; // For progress statuses
    }

    function deleteTreatment(id) {
        const url = `{{ route('doctor.treatments.destroy', ['id' => '__id__']) }}`.replace('__id__', id);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire('Deleted!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Error deleting treatment:', error);
                        Swal.fire('Error', 'Failed to delete treatment', 'error');
                    });
            }
        });
    }




</script>
@endsection




@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
