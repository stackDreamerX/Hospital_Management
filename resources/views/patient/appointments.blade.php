@extends('patient_layout')

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
        color: #212529 !important;
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
    
    /* Better badge styling */
    body .modal-body .badge {
        padding: 0.4rem 0.6rem !important;
        font-size: 0.9rem !important;
        display: inline-block !important;
        margin-left: 0.5rem !important;
    }
    
    /* Detail item styling */
    body .modal-body .mb-3 {
        display: flex !important;
        flex-direction: column !important;
        margin-bottom: 1rem !important;
        padding: 0.8rem !important;
        background-color: #f8f9fa !important;
        border-radius: 8px !important;
        border-left: 4px solid #3f8cff !important;
    }
    
    body .modal-body .mb-3 strong {
        font-weight: 600 !important;
        color: #212529 !important;
        margin-bottom: 0.3rem !important;
        display: block !important;
    }
    
    body .modal-body .mb-3 span {
        color: #212529 !important;
        font-size: 1rem !important;
    }
    
    body .modal-body .fas {
        color: #3f8cff !important;
    }
</style>
@endpush

@section('content')

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
                        <tr class="{{ $appointment->AppointmentDate === date('Y-m-d') ? 'table-warning' : '' }}">
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
                                    <button class="btn btn-info view-appointment" data-id="{{ $appointment->AppointmentID }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>
                                    @if($appointment['Status'] == 'pending')
                                        <button class="btn btn-primary edit-appointment" data-id="{{ $appointment->AppointmentID }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger cancel-appointment" data-id="{{ $appointment->AppointmentID }}">
                                            <i class="fas fa-times"></i>
                                            Cancel
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
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel"><i class="fas fa-calendar-check me-2"></i> Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-edit me-2"></i> Edit Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');
        
        // Verify Bootstrap is loaded
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap JS not loaded!');
            // Try to load it dynamically
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js';
            document.head.appendChild(script);
            script.onload = initializeComponents;
        } else {
            console.log('Bootstrap JS detected');
            initializeComponents();
        }
    });
    
    function initializeComponents() {
        console.log('Initializing components');
        // Initialize Bootstrap Modals
        try {
            const detailsModalEl = document.getElementById('detailsModal');
            const editModalEl = document.getElementById('editModal');
            
            if (!detailsModalEl) console.error('Details modal element not found!');
            if (!editModalEl) console.error('Edit modal element not found!');
            
            // Define the modals both as global variables and window properties
            detailsModal = new bootstrap.Modal(detailsModalEl);
            editModal = new bootstrap.Modal(editModalEl);
            
            // Also assign to window for redundancy
            window.detailsModal = detailsModal;
            window.editModal = editModal;
            
            console.log('Modals initialized successfully:', detailsModal, editModal);
            
            // Add test modal button listener
            const testBtn = document.getElementById('testModalBtn');
            if (testBtn) {
                testBtn.addEventListener('click', function() {
                    console.log('Test button clicked, showing modal directly');
                    const content = document.getElementById('detailsContent');
                    content.innerHTML = '<div class="alert alert-info">This is a test modal</div>';
                    
                    const modalElement = document.getElementById('detailsModal');
                    if (modalElement) {
                        showModalReliably(modalElement, detailsModal);
                    } else {
                        console.error('Modal element not found for test');
                    }
                });
            }
            
        } catch (error) {
            console.error('Error initializing modals:', error);
        }
        
        setupEventListeners();
    }
    
    function setupEventListeners() {
        // Handle new appointment form submission
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            createAppointment();
        });
      
        // Add event listeners for view, edit, and cancel buttons
        document.querySelectorAll('.view-appointment').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                viewDetails(id);
            });
        });
        
        document.querySelectorAll('.edit-appointment').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editAppointment(id);
            });
        });
        
        document.querySelectorAll('.cancel-appointment').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                cancelAppointment(id);
            });
        });
      
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const tableBody = document.querySelector('tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
        
        // Reload button
        const reloadButton = document.getElementById('reloadButton');
        if (reloadButton) {
            reloadButton.addEventListener('click', function() {
                window.location.reload();
            });
        }
    }

    let detailsModal, editModal;

    function createAppointment() {
        const data = {
            appointment_date: document.getElementById('appointment_date').value,
            appointment_time: document.getElementById('appointment_time').value,
            reason: document.getElementById('reason').value,
            symptoms: document.getElementById('symptoms').value,
            notes: document.getElementById('notes').value,
            doctor_id: document.getElementById('doctor_id').value
        };
        
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Processing...',
            text: 'Creating your appointment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
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
                // Close loading dialog
                loadingSwal.close();
                
                if (result.message) {
                    Swal.fire('Success', result.message, 'success').then(() => {
                        document.getElementById('appointmentForm').reset();
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Could not create appointment.', 'error');
                }
            })
            .catch(error => {
                // Close loading dialog
                loadingSwal.close();
                
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while creating the appointment. <br> Please ensure the appointment date is in the future.', 'error');
            });
    }

    // Helper function to show modal reliably
    function showModalReliably(modalElement, modalInstance) {
        console.log('Showing modal reliably:', modalElement.id, modalInstance);
        
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

    function viewDetails(appointmentId) {
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Loading...',
            text: 'Fetching appointment details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const url = `{{ route('patient.appointments.showDetail', ['id' => '__id__']) }}`.replace('__id__', appointmentId);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(appointment => {
                // Close loading dialog
                loadingSwal.close();
                
                // Log the response for debugging
                console.log('Appointment data:', appointment);
                
                try {
                    const content = document.getElementById('detailsContent');
                    if (!content) {
                        throw new Error('detailsContent element not found');
                    }
                    
                    // Ensure we have complete data or provide fallbacks
                    const doctorName = appointment.DoctorName || 'Not assigned';
                    const status = appointment.Status || 'Unknown';
                    const reason = appointment.Reason || 'Not specified';
                    const symptoms = appointment.Symptoms || 'Not specified';
                    const notes = appointment.Notes || 'No notes provided';
                    
                    // Format the status with badge
                    const statusBadge = `
                        <span class="badge bg-${
                            status === 'approved' ? 'success' : 
                            (status === 'pending' ? 'warning' : 'danger')
                        }">
                            ${status}
                        </span>
                    `;
                    
                    // Create a nicely formatted content
                    content.innerHTML = `
                        <div class="appointment-details">
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar me-2"></i>Date</strong>
                                <span>${appointment.AppointmentDate || 'Not specified'}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-clock me-2"></i>Time</strong>
                                <span>${appointment.AppointmentTime || 'Not specified'}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-user-md me-2"></i>Doctor</strong>
                                <span>${doctorName}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-info-circle me-2"></i>Status</strong>
                                <span>${statusBadge}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-comment-medical me-2"></i>Reason for Visit</strong>
                                <span>${reason}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-heartbeat me-2"></i>Symptoms</strong>
                                <span>${symptoms}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-sticky-note me-2"></i>Notes</strong>
                                <span>${notes}</span>
                            </div>
                        </div>
                    `;
                    
                    // Ensure the modal element exists
                    const modalElement = document.getElementById('detailsModal');
                    if (!modalElement) {
                        throw new Error('Modal element not found');
                    }
                    
                    // Show the modal with our reliable function
                    setTimeout(() => {
                        const shown = showModalReliably(modalElement, detailsModal);
                        if (shown) {
                            console.log('Modal shown successfully');
                        } else {
                            throw new Error('Failed to show modal with all methods');
                        }
                    }, 100);
                } catch (error) {
                    console.error('Error processing appointment details:', error);
                    Swal.fire('Error', 'Failed to display appointment details: ' + error.message, 'error');
                }
            })
            .catch(error => {
                // Close loading dialog
                loadingSwal.close();
                
                console.error('Error fetching appointment details:', error);
                Swal.fire('Error', 'Could not load appointment details. Please try again.', 'error');
            });
    }

    function editAppointment(id) {
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Loading...',
            text: 'Fetching appointment details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const url = `{{ route('patient.appointments.show', ['id' => '__id__']) }}`.replace('__id__', id);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(appointment => {
                // Close loading dialog
                loadingSwal.close();
                
                // Log the response for debugging
                console.log('Appointment data for edit:', appointment);
                
                try {
                    document.getElementById('edit_id').value = appointment.AppointmentID;
                    document.getElementById('edit_date').value = appointment.AppointmentDate;
                    document.getElementById('edit_time').value = appointment.AppointmentTime;
                    document.getElementById('edit_reason').value = appointment.Reason || '';
                    document.getElementById('edit_symptoms').value = appointment.Symptoms || '';
                    document.getElementById('edit_notes').value = appointment.Notes || '';
                    
                    if (appointment.DoctorID) {
                        document.getElementById('edit_doctor').value = appointment.DoctorID;
                    }
                    
                    // Get the modal element
                    const modalElement = document.getElementById('editModal');
                    if (!modalElement) {
                        throw new Error('Edit modal element not found');
                    }
                    
                    // Show the modal with our reliable function
                    setTimeout(() => {
                        const shown = showModalReliably(modalElement, editModal);
                        if (shown) {
                            console.log('Edit modal shown successfully');
                        } else {
                            throw new Error('Failed to show edit modal with all methods');
                        }
                    }, 100);
                } catch (error) {
                    console.error('Error setting form values:', error);
                    Swal.fire('Error', 'Failed to prepare edit form: ' + error.message, 'error');
                }
            })
            .catch(error => {
                // Close loading dialog
                loadingSwal.close();
                
                console.error('Error:', error);
                Swal.fire('Error', 'Could not load appointment details. Please try again.', 'error');
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
        
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Processing...',
            text: 'Updating your appointment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const url = `{{ route('patient.appointments.update', ['id' => '__id__']) }}`.replace('__id__', id);
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(result => {
                // Close loading dialog
                loadingSwal.close();
                
                if (result.message) {
                    Swal.fire('Success', result.message, 'success').then(() => {
                        editModal.hide();
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Could not update appointment.', 'error');
                }
            })
            .catch(error => {
                // Close loading dialog
                loadingSwal.close();
                
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while updating the appointment.', 'error');
            });
    }

    function cancelAppointment(id) {
        const url = `{{ route('patient.appointments.destroy', ['id' => '__id__']) }}`.replace('__id__', id);

        Swal.fire({
            title: 'Cancel Appointment',
            text: 'Are you sure you want to cancel this appointment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                const loadingSwal = Swal.fire({
                    title: 'Processing...',
                    text: 'Cancelling your appointment',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(result => {
                        // Close loading dialog
                        loadingSwal.close();
                        
                        if (result.message) {
                            Swal.fire('Success', result.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'Could not cancel appointment.', 'error');
                        }
                    })
                    .catch(error => {
                        // Close loading dialog
                        loadingSwal.close();
                        
                        console.error('Error:', error);
                        Swal.fire('Error', 'An error occurred while cancelling the appointment.', 'error');
                    });
            }
        });
    }
</script>
@endsection