<div class="modal fade" id="prescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="prescriptionForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select id="prescription_patient" class="form-control" required>
                                <option value="">Select Patient</option>
                                @foreach($patients ?? [] as $patient)
                                    <option value="{{ $patient['PatientID'] }}">{{ $patient['FullName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Doctor</label>
                            <select id="prescription_doctor" class="form-control" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors ?? [] as $doctor)
                                    <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['FullName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Medicine List -->
                    <div id="medicine_list">
                        <div class="medicine-item row mb-3">
                            <div class="col-md-4">
                                <select class="form-control medicine-select" required>
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine['MedicineID'] }}" 
                                                data-price="{{ $medicine['UnitPrice'] }}">
                                            {{ $medicine['MedicineName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control quantity" placeholder="Qty" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control dosage" placeholder="Dosage" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-medicine">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-info mb-3" onclick="addMedicineRow()">
                        <i class="fas fa-plus"></i> Add Medicine
                    </button>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table">
                                <tr>
                                    <th>Total Price:</th>
                                    <td><span id="total_price">0</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="savePrescription()">Save Prescription</button>
            </div>
        </div>
    </div>
</div> 