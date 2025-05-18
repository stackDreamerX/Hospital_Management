@extends('doctor_layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Manage Your Schedule</h4>
                    <p class="text-muted mb-0">Set your working hours for patients to book appointments</p>
                </div>
                <div class="card-body">
                    <!-- New Feature Alert -->
                    <div class="alert alert-success mb-4">
                        <h5 class="alert-heading"><i class="fas fa-star me-2"></i>New Feature!</h5>
                        <p class="mb-0">We've added online appointment booking for your patients. Set your availability below and patients can book directly through your profile page.</p>
                    </div>

                    <!-- Schedule Instructions -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>How scheduling works:</h5>
                        <ol class="mb-0">
                            <li>Set your standard working hours for each day of the week</li>
                            <li>The system will automatically create 30-minute appointment slots</li>
                            <li>Patients can only book available slots in your schedule</li>
                            <li>You can update your schedule anytime</li>
                        </ol>
                    </div>

                    <div class="row">
                        <!-- Current Schedule -->
                        <div class="col-md-7">
                            <h5 class="mb-3">Your Weekly Schedule</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Day</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($weekdays as $day_num => $day_name)
                                            @php
                                                $schedule = $schedules->where('day_of_week', $day_num)->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $day_name }}</td>
                                                <td>
                                                    @if($schedule)
                                                        {{ date('h:i A', strtotime($schedule->start_time)) }}
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($schedule)
                                                        {{ date('h:i A', strtotime($schedule->end_time)) }}
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($schedule)
                                                        <button
                                                            class="btn btn-sm btn-primary edit-schedule"
                                                            data-day="{{ $day_num }}"
                                                            data-day-name="{{ $day_name }}"
                                                            data-start="{{ $schedule->start_time }}"
                                                            data-end="{{ $schedule->end_time }}"
                                                        >
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button
                                                            class="btn btn-sm btn-danger delete-schedule"
                                                            data-id="{{ $schedule->id }}"
                                                            data-day="{{ $day_name }}"
                                                        >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn btn-sm btn-success add-schedule"
                                                            data-day="{{ $day_num }}"
                                                            data-day-name="{{ $day_name }}"
                                                        >
                                                            <i class="fas fa-plus"></i> Add
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Set Schedule Form -->
                        <div class="col-md-5">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0" id="schedule-form-title">Add Working Hours</h5>
                                </div>
                                <div class="card-body">
                                    <form id="scheduleForm">
                                        @csrf
                                        <input type="hidden" id="day_of_week" name="day_of_week">

                                        <div class="mb-3">
                                            <label class="form-label">Day</label>
                                            <input type="text" class="form-control" id="day_name" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Start Time</label>
                                            <select class="form-select" id="start_time" name="start_time" required>
                                                <option value="">Select start time</option>
                                                @foreach($timeSlots as $time)
                                                    <option value="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">End Time</label>
                                            <select class="form-select" id="end_time" name="end_time" required>
                                                <option value="">Select end time</option>
                                                @foreach($timeSlots as $time)
                                                    <option value="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="alert alert-warning d-none" id="time-warning">
                                            End time must be later than start time.
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" id="cancelBtn">Cancel</button>
                                            <button type="submit" class="btn btn-primary" id="saveBtn">Save Schedule</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the schedule for <span id="delete-day" class="fw-bold"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Schedule</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initially hide the form
        $("#scheduleForm").hide();

        // Show form when Add button is clicked
        $(".add-schedule").click(function() {
            const day = $(this).data('day');
            const dayName = $(this).data('day-name');

            $("#day_of_week").val(day);
            $("#day_name").val(dayName);
            $("#start_time").val('');
            $("#end_time").val('');

            $("#schedule-form-title").text("Add Working Hours");
            $("#scheduleForm").show();
        });

        // Show form when Edit button is clicked
        $(".edit-schedule").click(function() {
            const day = $(this).data('day');
            const dayName = $(this).data('day-name');
            const startTime = $(this).data('start');
            const endTime = $(this).data('end');

            $("#day_of_week").val(day);
            $("#day_name").val(dayName);
            $("#start_time").val(startTime);
            $("#end_time").val(endTime);

            $("#schedule-form-title").text("Edit Working Hours");
            $("#scheduleForm").show();
        });

        // Hide form when Cancel is clicked
        $("#cancelBtn").click(function() {
            $("#scheduleForm").hide();
        });

        // Validate end time is after start time
        $("#end_time").change(function() {
            const startTime = $("#start_time").val();
            const endTime = $(this).val();

            if (startTime && endTime && startTime >= endTime) {
                $("#time-warning").removeClass('d-none');
                $("#saveBtn").prop('disabled', true);
            } else {
                $("#time-warning").addClass('d-none');
                $("#saveBtn").prop('disabled', false);
            }
        });

        // Handle form submission
        $("#scheduleForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('doctor.schedule.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message);

                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    // Display errors
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                }
            });
        });

        // Show delete confirmation modal
        $(".delete-schedule").click(function() {
            const id = $(this).data('id');
            const day = $(this).data('day');

            $("#delete-day").text(day);
            $("#deleteModal").modal('show');

            // Store the schedule ID to be deleted
            $("#confirmDelete").data('id', id);
        });

        // Handle delete confirmation
        $("#confirmDelete").click(function() {
            const id = $(this).data('id');

            $.ajax({
                url: "/doctor/schedule/" + id,
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Close the modal
                        $("#deleteModal").modal('hide');

                        // Show success message
                        toastr.success(response.message);

                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    // Show error message
                    toastr.error("An error occurred while deleting the schedule.");
                }
            });
        });
    });
</script>
@endsection
