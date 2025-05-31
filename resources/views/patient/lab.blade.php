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
        color: #212529 !important; /* Dark text color */
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
        color: #212529 !important; /* Darker text for labels */
        margin-bottom: 0.3rem !important;
        display: block !important;
    }

    body .modal-body .mb-3 span {
        color: #212529 !important; /* Ensure text is black */
        font-size: 1rem !important;
    }

    /* Ensure icons are visible but not too prominent */
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
                            <h6 class="mb-0">Pending Tests</h6>
                            <h2 class="mb-0">{{ $pendingTests }}</h2>
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
                            <h6 class="mb-0">Completed Tests</h6>
                            <h2 class="mb-0">{{ $completedTests }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Tests List -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Lab Tests</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search tests...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Test Type</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labTests as $test)
                        <tr>
                            <td>{{ $test['LaboratoryDate'] }}</td>
                            <td>{{ $test['LaboratoryTime'] }}</td>
                            <td>{{ $test['LaboratoryTypeName'] }}</td>
                            <td>{{ $test['DoctorName'] }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $test['Status'] == 'Completed' ? 'success' :
                                    ($test['Status'] == 'Pending' ? 'warning' : 'info')
                                }}">
                                    Pending
                                </span>
                            </td>
                            <td>
                                {{ number_format($test['TotalPrice']) }}
                                <span class="badge bg-success">
                                    <!-- {{ $test['PaymentStatus'] }} -->
                                      $
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info view-test"
                                            data-id="{{ $test['LaboratoryID'] }}"
                                            data-date="{{ $test['LaboratoryDate'] }}"
                                            data-time="{{ $test['LaboratoryTime'] }}"
                                            data-type="{{ $test['LaboratoryTypeName'] }}"
                                            data-doctor="{{ $test['DoctorName'] }}"
                                            data-status="{{ $test['Status'] }}"
                                            data-price="{{ $test['TotalPrice'] }}"
                                            data-payment="{{ $test['PaymentStatus'] }}"
                                            data-result="{{ $test['Result'] ?? '' }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($test['Status'] == 'Completed' && $test['Result'])
                                        <button class="btn btn-primary download-report" data-id="{{ $test['LaboratoryID'] }}">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No lab tests found</td>
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
                <h5 class="modal-title" id="detailsModalLabel"><i class="fas fa-flask me-2"></i> Lab Test Details</h5>
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

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let detailsModal;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the modal
    detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));

    // Add event listeners for buttons
    document.querySelectorAll('.view-test').forEach(button => {
        button.addEventListener('click', function() {
            // Get individual data attributes instead of parsing JSON
            const testData = {
                LaboratoryID: this.getAttribute('data-id'),
                LaboratoryDate: this.getAttribute('data-date'),
                LaboratoryTime: this.getAttribute('data-time'),
                LaboratoryTypeName: this.getAttribute('data-type'),
                DoctorName: this.getAttribute('data-doctor'),
                Status: this.getAttribute('data-status'),
                TotalPrice: this.getAttribute('data-price'),
                PaymentStatus: this.getAttribute('data-payment'),
                Result: this.getAttribute('data-result')
            };

            viewDetails(testData);
        });
    });

    document.querySelectorAll('.download-report').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            downloadReport(id);
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});

// Helper function to show modal reliably
function showModalReliably(modalElement, modalInstance) {

    try {
        // First attempt: Bootstrap modal method
        if (modalInstance && typeof modalInstance.show === 'function') {
            modalInstance.show();
            return true;
        }
    } catch (error) {
        console.warn('Error showing modal via Bootstrap API:', error);
    }

    try {
        // Second attempt: jQuery if available
        if (typeof $ !== 'undefined') {
            $(modalElement).modal('show');
            return true;
        }
    } catch (error) {
        console.warn('Error showing modal via jQuery:', error);
    }

    // Final attempt: Direct DOM manipulation
    try {
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
        }

        return true;
    } catch (error) {
        console.error('All methods to show modal failed:', error);
        return false;
    }
}

function viewDetails(test) {

    // Format the status badge
    const statusBadge = `
        <span class="badge bg-${
            test.Status === 'Completed' ? 'success' :
            (test.Status === 'Pending' ? 'warning' : 'info')
        }">
            ${test.Status || 'Unknown'}
        </span>
    `;

    // Format the payment badge
    const paymentBadge = `
        <span class="badge bg-${test.PaymentStatus === 'Paid' ? 'success' : 'warning'}">
            ${test.PaymentStatus || 'Unknown'}
        </span>
    `;

    // Create nicely formatted content
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="lab-test-details">
            <div class="mb-3">
                <strong><i class="fas fa-vial me-2"></i>Test Type</strong>
                <span>${test.LaboratoryTypeName || 'Not specified'}</span>
            </div>

            <div class="mb-3">
                <strong><i class="fas fa-user-md me-2"></i>Doctor</strong>
                <span>${test.DoctorName || 'Not assigned'}</span>
            </div>

            <div class="mb-3">
                <strong><i class="fas fa-calendar me-2"></i>Date</strong>
                <span>${test.LaboratoryDate || 'Not specified'}</span>
            </div>

            <div class="mb-3">
                <strong><i class="fas fa-clock me-2"></i>Time</strong>
                <span>${test.LaboratoryTime || 'Not specified'}</span>
            </div>

            <div class="mb-3">
                <strong><i class="fas fa-info-circle me-2"></i>Status</strong>
                <span>pending</span>
            </div>

            <div class="mb-3">
                <strong><i class="fas fa-money-bill-wave me-2"></i>Price</strong>
                <span>${test.TotalPrice || '0'}$</span>
            </div>

            ${test.Result ? `
                <div class="mb-3">
                    <strong><i class="fas fa-file-medical-alt me-2"></i>Result</strong>
                    <span>${test.Result}</span>
                </div>
            ` : ''}
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
}

function downloadReport(id) {
    // Show a success notification
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Report downloaded successfully!'
    });

    // In a real implementation, you would initiate a download here
    // Example:
    // window.location.href = `/patient/lab/download/${id}`;
}
</script>
@endsection
