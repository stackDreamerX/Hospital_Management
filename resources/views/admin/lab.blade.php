@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('admin_content') 

<section class="container mt-4 mb-4">
    <!-- Lab Management Title -->
    <h3 class="mb-3">Lab Management</h3>

    <!-- View All Lab Details and Generate Report -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="text-decoration-none">View All Lab Details</a>
        <button class="btn btn-primary">Generate Report</button>
    </div>
    
    <!-- Assign Lab For Patient Section -->
    <div class="card p-4">
        <h5 class="mb-4">Assign Lab For Patient</h5>

        <!-- Lab ID with Search -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Lab ID">
                    <button class="btn btn-primary" type="button">Search</button>
                </div>
            </div>
        </div>

        <!-- Lab and Patient Name -->
        <div class="row mb-3">
            <div class="col-md-6">
                <select class="form-select">
                    <option selected disabled>Lab</option>
                    <option>Blood</option>
                    <option>Urine</option>
                    <option>X-Ray</option>
                </select>
            </div>
            <div class="col-md-6">
                <select class="form-select">
                    <option selected disabled>Patient Name</option>
                    <option>John Doe</option>
                    <option>Jane Smith</option>
                    <option>Michael Lee</option>
                </select>
            </div>
        </div>

        <!-- Date and Time -->
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="date" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="time" class="form-control">
            </div>
        </div>

        <!-- Price -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-dark text-white">RS:</span>
                    <input type="text" class="form-control" placeholder="Price">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-start">
            <button type="button" class="btn btn-success me-2">Add</button>
            <button type="button" class="btn btn-primary me-2">Update</button>
            <button type="button" class="btn btn-danger">Delete</button>
        </div>
    </div>

    <!-- Recent Assign Lab For Patient -->
    <div class="card mt-4 p-4">
        <h5>Recent Assign Lab For Patient</h5>
        <small class="text-muted">List of recently assigned labs for patients.</small>
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lab</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Blood</td>
                    <td>John Doe</td>
                    <td>2022-02-15</td>
                    <td>1:25 PM</td>
                    <td>2500.00</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Urine</td>
                    <td>Jane Smith</td>
                    <td>2022-02-16</td>
                    <td>10:30 AM</td>
                    <td>1500.00</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>X-Ray</td>
                    <td>Michael Lee</td>
                    <td>2022-02-17</td>
                    <td>2:15 PM</td>
                    <td>3500.00</td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
