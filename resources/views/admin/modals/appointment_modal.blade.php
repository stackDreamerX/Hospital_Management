<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="edit_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" id="edit_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Doctor</label>
                        <select id="edit_doctor" class="form-control" required>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['Name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <select id="edit_patient" class="form-control" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['PatientID'] }}">{{ $patient['Name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="edit_status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
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