@extends('admin_layout')
@section('admin_content')

<style>
    .modal {
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }
    
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal-dialog {
        z-index: 1060;
        margin: 30px auto;
    }

    .table tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .dropdown-menu {
        min-width: 120px;
    }

    .dropdown-item {
        padding: 8px 15px;
        font-size: 14px;
    }

    .dropdown-item i {
        margin-right: 8px;
        width: 16px;
    }

    .stats-card {
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }
</style>

<div class="container" style="padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #333;">Pharmacy Management</h2>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-plus"></i> Add New
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#" onclick="showMedicineModal()">
                        <i class="fas fa-pills"></i> Medicine
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="showProviderModal()">
                        <i class="fas fa-truck"></i> Provider
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="showGRNModal()">
                        <i class="fas fa-clipboard-list"></i> Goods Receipt
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="showPrescriptionModal()">
                        <i class="fas fa-file-medical"></i> Prescription
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Medicines</h6>
                    <h2>{{ count($medicines) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Providers</h6>
                    <h2>{{ count($providers) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Today's Prescriptions</h6>
                    <h2>{{ $todayPrescriptions }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Low Stock Items</h6>
                    <h2>{{ $lowStockCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#medicines" role="tab">Medicines</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#providers" role="tab">Providers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#prescriptions" role="tab">Prescriptions</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Medicines Tab -->
        <div class="tab-pane active" id="medicines" role="tabpanel">
            <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Medicine Inventory</h5>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search medicines...">
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Unit Price</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicines as $medicine)
                                <tr>
                                    <td>{{ $medicine['MedicineID'] }}</td>
                                    <td>{{ $medicine['MedicineName'] }}</td>
                                    <td>{{ $medicine['stock']['Quantity'] }}</td>
                                    <td>{{ number_format($medicine['UnitPrice']) }}</td>
                                    <td>{{ $medicine['ExpiryDate'] }}</td>
                                    <td>
                                        @php
                                            $stock = $medicine['stock']['Quantity'];
                                            $class = $stock > 20 ? 'success' : ($stock > 0 ? 'warning' : 'danger');
                                            $status = $stock > 20 ? 'In Stock' : ($stock > 0 ? 'Low Stock' : 'Out of Stock');
                                        @endphp
                                        <span class="badge bg-{{ $class }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-link" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="editMedicine({{ json_encode($medicine) }})">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="addStock({{ $medicine['MedicineID'] }})">
                                                        <i class="fas fa-plus"></i> Add Stock
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="viewHistory({{ $medicine['MedicineID'] }})">
                                                        <i class="fas fa-history"></i> History
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="deleteMedicine({{ $medicine['MedicineID'] }})">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Providers Tab -->
        <div class="tab-pane" id="providers" role="tabpanel">
            <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Providers List</h5>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search providers...">
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Provider Name</th>
                                <th>Total Supplies</th>
                                <th>Last Supply</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($providers as $provider)
                            <tr>
                                <td>{{ $provider['ProviderID'] }}</td>
                                <td>{{ $provider['ProviderName'] }}</td>
                                <td>{{ $provider['TotalSupplies'] ?? 0 }}</td>
                                <td>{{ $provider['LastSupply'] ?? 'N/A' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editProvider({{ json_encode($provider) }})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="viewProviderHistory({{ $provider['ProviderID'] }})">
                                                    <i class="fas fa-history"></i> History
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteProvider({{ $provider['ProviderID'] }})">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Prescriptions Tab -->
        <div class="tab-pane" id="prescriptions" role="tabpanel">
            <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Prescriptions List</h5>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search prescriptions...">
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Total Items</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescriptions as $prescription)
                            <tr>
                                <td>{{ $prescription['PrescriptionID'] }}</td>
                                <td>{{ $prescription['PrescriptionDate'] }}</td>
                                <td>{{ $prescription['PatientName'] }}</td>
                                <td>{{ $prescription['DoctorName'] }}</td>
                                <td>{{ $prescription['TotalItems'] }}</td>
                                <td>{{ number_format($prescription['TotalPrice']) }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="viewPrescription({{ $prescription['PrescriptionID'] }})">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="printPrescription({{ $prescription['PrescriptionID'] }})">
                                                    <i class="fas fa-print"></i> Print
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.modals.medicine_modal')
@include('admin.modals.provider_modal')
@include('admin.modals.grn_modal')
@include('admin.modals.prescription_modal')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Add your JavaScript functions here
    function showMedicineModal() {
        // Show medicine modal
    }

    function showProviderModal() {
        // Show provider modal
    }

    function showGRNModal() {
        // Show GRN modal
    }

    function showPrescriptionModal() {
        // Show prescription modal
    }

    function editMedicine(medicine) {
        // Edit medicine
    }

    function addStock(id) {
        // Add stock
    }

    function viewHistory(id) {
        // View history
    }

    function deleteMedicine(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete medicine
                Swal.fire('Deleted!', 'Medicine has been deleted.', 'success');
            }
        });
    }

    // Initialize tabs
    document.addEventListener('DOMContentLoaded', function() {
        // Your existing initialization code
        
        // Get tab from URL if present
        let hash = window.location.hash;
        if (hash) {
            new bootstrap.Tab(document.querySelector(`a[href="${hash}"]`)).show();
        }
    });

    // Add tab state to URL
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            window.location.hash = e.target.getAttribute('href');
        });
    });
</script>
@endsection
