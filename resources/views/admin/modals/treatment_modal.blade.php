<div class="modal fade" id="treatmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="treatmentModalTitle">Add New Treatment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="treatmentForm">
                    <input type="hidden" id="treatment_id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Treatment Type</label>
                            <select id="treatment_type" class="form-control" required>
                                <option value="">Select Treatment Type</option>
                                @foreach($treatmentTypes as $type)
                                    <option value="{{ $type['TreatmentTypeID'] }}">{{ $type['TreatmentTypeName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Treatment Date</label>
                            <input type="date" id="treatment_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select id="patient_id" class="form-control" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient['PatientID'] }}">{{ $patient['FullName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Doctor</label>
                            <select id="doctor_id" class="form-control" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['FullName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" id="treatment_price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Treatment Result</label>
                        <textarea id="treatment_result" class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveTreatment()">Save Treatment</button>
            </div>
        </div>
    </div>
</div> 