@extends('layout')

@section('title', 'My Appointments')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Improved badge styling */
    .badge {
        padding: 0.4rem 0.6rem;
        font-size: 0.9rem;
        display: inline-block;
        margin-left: 0.5rem;
    }

    /* Detail item styling for appointments */
    .appointment-details .mb-3 {
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
        padding: 0.8rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #3f8cff;
    }

    .appointment-details .mb-3 strong {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.3rem;
        display: block;
    }

    .appointment-details .mb-3 span {
        color: #212529;
        font-size: 1rem;
    }

    .appointment-details .fas {
        color: #3f8cff;
    }

    /* Rating stars styling */
    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
        font-size: 1.5em;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ddd;
        cursor: pointer;
        padding: 0 0.1em;
        transition: color 0.2s;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffc107;
    }
</style>
@endsection

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
                                    ($appointment['Status'] == 'pending' ? 'warning' :
                                    ($appointment['Status'] == 'completed' ? 'success' : 'danger'))
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
                                    @if($appointment['Status'] == 'completed')
                                        @if($appointment->has_rating)
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-check"></i>
                                                Already Rated
                                            </button>
                                        @else
                                            <button class="btn btn-success rate-doctor"
                                                    data-id="{{ $appointment->AppointmentID }}"
                                                    data-doctor-id="{{ $appointment->DoctorID }}"
                                                    data-doctor-name="{{ $appointment->doctor->user->FullName ?? 'Doctor' }}">
                                                <i class="fas fa-star"></i>
                                                Rate Doctor
                                            </button>
                                        @endif
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

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ratingModalLabel"><i class="fas fa-star me-2"></i> Rate Your Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ratingForm" action="{{ route('doctor.review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="doctor_id" id="rating_doctor_id">
                <input type="hidden" name="appointment_id" id="rating_appointment_id">

                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <p>How was your experience with <strong id="doctor_name_display"></strong>?</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Doctor Rating</label>
                        <div class="star-rating text-center">
                            <input type="radio" id="rating5" name="doctor_rating" value="5" required />
                            <label for="rating5" class="fas fa-star"></label>
                            <input type="radio" id="rating4" name="doctor_rating" value="4" />
                            <label for="rating4" class="fas fa-star"></label>
                            <input type="radio" id="rating3" name="doctor_rating" value="3" />
                            <label for="rating3" class="fas fa-star"></label>
                            <input type="radio" id="rating2" name="doctor_rating" value="2" />
                            <label for="rating2" class="fas fa-star"></label>
                            <input type="radio" id="rating1" name="doctor_rating" value="1" />
                            <label for="rating1" class="fas fa-star"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Service Rating</label>
                        <div class="star-rating text-center">
                            <input type="radio" id="service5" name="service_rating" value="5" />
                            <label for="service5" class="fas fa-star"></label>
                            <input type="radio" id="service4" name="service_rating" value="4" />
                            <label for="service4" class="fas fa-star"></label>
                            <input type="radio" id="service3" name="service_rating" value="3" />
                            <label for="service3" class="fas fa-star"></label>
                            <input type="radio" id="service2" name="service_rating" value="2" />
                            <label for="service2" class="fas fa-star"></label>
                            <input type="radio" id="service1" name="service_rating" value="1" />
                            <label for="service1" class="fas fa-star"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Cleanliness Rating</label>
                        <div class="star-rating text-center">
                            <input type="radio" id="clean5" name="cleanliness_rating" value="5" />
                            <label for="clean5" class="fas fa-star"></label>
                            <input type="radio" id="clean4" name="cleanliness_rating" value="4" />
                            <label for="clean4" class="fas fa-star"></label>
                            <input type="radio" id="clean3" name="cleanliness_rating" value="3" />
                            <label for="clean3" class="fas fa-star"></label>
                            <input type="radio" id="clean2" name="cleanliness_rating" value="2" />
                            <label for="clean2" class="fas fa-star"></label>
                            <input type="radio" id="clean1" name="cleanliness_rating" value="1" />
                            <label for="clean1" class="fas fa-star"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="feedback" class="form-label fw-bold">Your Feedback</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="4"
                            placeholder="Share your experience..." required></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                        <label class="form-check-label" for="is_anonymous">
                            Post anonymously
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    // Global modal instances
    let detailsModal, editModal, ratingModal;

    document.addEventListener('DOMContentLoaded', function() {

        // Initialize Bootstrap modals
        detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
        editModal = new bootstrap.Modal(document.getElementById('editModal'));
        ratingModal = new bootstrap.Modal(document.getElementById('ratingModal'));

        // Set up event listeners
        setupEventListeners();
    });

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

        // Add event listeners for rating buttons
        document.querySelectorAll('.rate-doctor').forEach(button => {
            button.addEventListener('click', function() {
                const appointmentId = this.getAttribute('data-id');
                const doctorId = this.getAttribute('data-doctor-id');
                const doctorName = this.getAttribute('data-doctor-name');
                openRatingModal(appointmentId, doctorId, doctorName);
            });
        });

        // Rating form submission
        if (document.getElementById('ratingForm')) {
            document.getElementById('ratingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitRating(this);
            });
        }

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

    function openRatingModal(appointmentId, doctorId, doctorName) {
        // First check if this appointment has already been rated
        const ratedAppointmentIds = @json($ratedAppointmentIds ?? []);

        if (ratedAppointmentIds.includes(parseInt(appointmentId))) {
            Swal.fire({
                title: 'Already Rated',
                text: 'You have already submitted a review for this appointment.',
                icon: 'info'
            });
            return;
        }

        document.getElementById('rating_doctor_id').value = doctorId;
        document.getElementById('rating_appointment_id').value = appointmentId;
        document.getElementById('doctor_name_display').textContent = doctorName;
        ratingModal.show();
    }

    function submitRating(form) {
        const formData = new FormData(form);
        const data = {};

        // Properly handle form data, especially checkboxes
        formData.forEach((value, key) => {
            // For checkbox fields, convert to boolean
            if (key === 'is_anonymous') {
                data[key] = true; // Always set to true if present in formData
            } else {
                data[key] = value;
            }
        });

        // Ensure is_anonymous is defined (defaults to false if not checked)
        if (!('is_anonymous' in data)) {
            data.is_anonymous = false;
        }


        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Submitting...',
            text: 'Sending your rating',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {

            // First, check if the response is ok (status in the range 200-299)
            if (!response.ok) {
                return response.json().catch(() => {
                    throw new Error(`Server returned ${response.status} ${response.statusText}`);
                }).then(errorData => {
                    throw new Error(errorData.message || `Server error: ${response.status}`);
                });
            }

            // Check if response is valid JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                console.error('Received non-JSON response:', contentType);
                // Try to get the response text for debugging
                return response.text().then(text => {
                    console.error('Response text:', text.substring(0, 500) + '...');
                    throw new Error('Server returned a non-JSON response');
                });
            }
        })
        .then(result => {
            loadingSwal.close();

            if (result.success) {
                ratingModal.hide();
                Swal.fire('Success', 'Your rating has been submitted. Thank you for your feedback!', 'success')
                .then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error', result.message || 'Something went wrong. Please try again.', 'error');
            }
        })
        .catch(error => {
            loadingSwal.close();
            console.error('Error submitting rating:', error.message);
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while submitting your rating: ' + error.message,
                icon: 'error'
            });
        });
    }

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

        const url = `{{ route('users.appointments.store') }}`;

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

        const url = `{{ route('users.appointments.showDetail', ['id' => '__id__']) }}`.replace('__id__', appointmentId);

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

                try {
                    const content = document.getElementById('detailsContent');

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

                    // Show the modal
                    detailsModal.show();
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

        const url = `{{ route('users.appointments.show', ['id' => '__id__']) }}`.replace('__id__', id);

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

                    // Show edit modal
                    editModal.show();
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

        const url = `{{ route('users.appointments.update', ['id' => '__id__']) }}`.replace('__id__', id);

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
        const url = `{{ route('users.appointments.destroy', ['id' => '__id__']) }}`.replace('__id__', id);

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