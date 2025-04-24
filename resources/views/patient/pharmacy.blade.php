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
    
    /* Style for medicine list */
    body .modal-body ul {
        list-style-type: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    
    body .modal-body ul li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        color: #212529;
    }
    
    body .modal-body ul li:last-child {
        border-bottom: none;
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
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending Prescriptions</h6>
                            <h2 class="mb-0">{{ $pendingCount }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Completed Prescriptions</h6>
                            <h2 class="mb-0">{{ $completedCount }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Spent</h6>
                            <h2 class="mb-0">{{ number_format($totalSpent) }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">My Prescriptions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Medicines</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription['Date'] }}</td>
                            <td>{{ $prescription['DoctorName'] }}</td>
                            <td>
                                @foreach($prescription['Items'] as $item)
                                    <div>{{ $item['Name'] }} - {{ $item['Dosage'] }}</div>
                                @endforeach
                            </td>
                            <td>{{ number_format($prescription['TotalAmount']) }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $prescription['Status'] == 'Completed' ? 'success' : 'warning'
                                }}">{{ $prescription['Status'] }}</span>
                                <span class="badge bg-{{
                                    $prescription['PaymentStatus'] == 'Paid' ? 'success' : 'warning'
                                }}">{{ $prescription['PaymentStatus'] }}</span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm view-prescription" data-prescription='{!! json_encode($prescription) !!}'>
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No prescriptions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel"><i class="fas fa-prescription-bottle-alt me-2"></i> Prescription Details</h5>
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
    detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
    
    // Add event listeners to view buttons
    document.querySelectorAll('.view-prescription').forEach(button => {
        button.addEventListener('click', function() {
            try {
                const prescription = JSON.parse(this.getAttribute('data-prescription'));
                viewDetails(prescription);
            } catch (error) {
                console.error('Error parsing prescription data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not load prescription details. Please try again.'
                });
            }
        });
    });
});

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

function viewDetails(prescription) {
    console.log('Prescription data:', prescription); // Log the data for debugging
    
    // Format the status badge
    const statusBadge = `
        <span class="badge bg-${
            prescription.Status === 'Completed' ? 'success' : 'warning'
        }">
            ${prescription.Status || 'Unknown'}
        </span>
    `;
    
    // Format the payment badge
    const paymentBadge = `
        <span class="badge bg-${prescription.PaymentStatus === 'Paid' ? 'success' : 'warning'}">
            ${prescription.PaymentStatus || 'Unknown'}
        </span>
    `;
    
    // Generate list of medicine items
    const medicineItems = prescription.Items.map(item => 
        `<li><i class="fas fa-pills me-2"></i>${item.Name} - ${item.Dosage} x ${item.Quantity}</li>`
    ).join('');
    
    // Create nicely formatted content
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="prescription-details">
            <div class="mb-3">
                <strong><i class="fas fa-calendar me-2"></i>Date</strong>
                <span>${prescription.Date || 'Not specified'}</span>
            </div>
            
            <div class="mb-3">
                <strong><i class="fas fa-user-md me-2"></i>Doctor</strong>
                <span>${prescription.DoctorName || 'Not assigned'}</span>
            </div>
            
            <div class="mb-3">
                <strong><i class="fas fa-info-circle me-2"></i>Status</strong>
                <span>${prescription.Status || 'Unknown'} ${statusBadge}</span>
            </div>
            
            <div class="mb-3">
                <strong><i class="fas fa-money-bill-wave me-2"></i>Payment</strong>
                <span>${prescription.PaymentStatus || 'Unknown'} ${paymentBadge}</span>
            </div>
            
            <div class="mb-3">
                <strong><i class="fas fa-pills me-2"></i>Medicines</strong>
                <ul>${medicineItems}</ul>
            </div>
            
            <div class="mb-3">
                <strong><i class="fas fa-dollar-sign me-2"></i>Total Amount</strong>
                <span>${number_format(prescription.TotalAmount) || '0'}</span>
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
}

// Helper function for formatting numbers (similar to PHP's number_format)
function number_format(number) {
    return new Intl.NumberFormat().format(number);
}
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush