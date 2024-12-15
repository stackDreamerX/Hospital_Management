<div class="modal fade" id="providerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="providerModalTitle">Add New Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="providerForm">
                    <input type="hidden" id="provider_id">
                    <div class="mb-3">
                        <label class="form-label">Provider Name</label>
                        <input type="text" id="provider_name" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveProvider()">Save</button>
            </div>
        </div>
    </div>
</div> 