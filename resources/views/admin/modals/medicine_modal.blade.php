<div class="modal fade" id="medicineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicineModalTitle">Add New Medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="medicineForm">
                    <input type="hidden" id="medicine_id">
                    <div class="mb-3">
                        <label class="form-label">Medicine Name</label>
                        <input type="text" id="medicine_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Manufacturing Date</label>
                        <input type="date" id="manufacturing_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" id="expiry_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit Price</label>
                        <input type="number" id="unit_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" id="initial_stock" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveMedicine()">Save</button>
            </div>
        </div>
    </div>
</div> 