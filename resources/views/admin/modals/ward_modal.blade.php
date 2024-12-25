<div class="modal fade" id="wardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ward</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Ward Name</label>
                        <input type="text" id="edit_ward_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ward Type</label>
                        <select id="edit_ward_type" class="form-control" required>
                            <option value="">Select Ward Type</option>
                            @foreach($wardTypes as $type)
                                <option value="{{ $type['WardTypeID'] }}">{{ $type['TypeName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" id="edit_capacity" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Doctor In Charge</label>
                        <select id="edit_doctor" class="form-control" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['FullName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateWard()">Save changes</button>
            </div>
        </div>
    </div>
</div>