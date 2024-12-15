@extends('admin_layout')
@section('admin_content')

<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Ward Management</h2>

    <!-- Create New Ward Form -->
    <div class="card mb-4" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Create New Ward</div>
        <div class="card-body" style="padding: 20px;">
            <form id="createWardForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Ward Name</label>
                        <input type="text" name="ward_name" class="form-control" required style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Ward Type</label>
                        <select name="ward_type" class="form-control" required style="padding: 8px;">
                            <option value="">Select Ward Type</option>
                            @foreach($wardTypes as $type)
                                <option value="{{ $type['WardTypeID'] }}">{{ $type['TypeName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Capacity</label>
                        <input type="number" name="capacity" class="form-control" required min="1" style="padding: 8px;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-weight: bold; margin-bottom: 5px;">Doctor In Charge</label>
                        <select name="doctor_id" class="form-control" required style="padding: 8px;">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['DoctorID'] }}">{{ $doctor['FullName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Create Ward</button>
            </form>
        </div>
    </div>

    <!-- Ward List -->
    <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ward List</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search wards...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 20px;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Ward Name</th>
                        <th style="padding: 12px;">Type</th>
                        <th style="padding: 12px;">Capacity</th>
                        <th style="padding: 12px;">Occupancy</th>
                        <th style="padding: 12px;">Doctor In Charge</th>
                        <th style="padding: 12px;">Status</th>
                        <th style="padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wards as $ward)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $ward['WardID'] }}</td>
                        <td style="padding: 12px;">{{ $ward['WardName'] }}</td>
                        <td style="padding: 12px;">{{ $ward['TypeName'] }}</td>
                        <td style="padding: 12px;">{{ $ward['Capacity'] }}</td>
                        <td style="padding: 12px;">{{ $ward['CurrentOccupancy'] }}</td>
                        <td style="padding: 12px;">{{ $ward['DoctorName'] }}</td>
                        <td style="padding: 12px;">
                            @php
                                $occupancyRate = ($ward['CurrentOccupancy'] / $ward['Capacity']) * 100;
                                $class = $occupancyRate >= 90 ? 'danger' : 
                                        ($occupancyRate >= 70 ? 'warning' : 'success');
                                $status = $occupancyRate >= 90 ? 'Full' : 
                                         ($occupancyRate >= 70 ? 'High' : 'Available');
                            @endphp
                            <span class="badge bg-{{ $class }}">{{ $status }}</span>
                        </td>
                        <td style="padding: 12px;">
                            <button onclick="editWard({{ json_encode($ward) }})" 
                                    class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteWard({{ $ward['WardID'] }})" 
                                    class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
@include('admin.modals.ward_modal')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let editModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('wardModal'));
        
        // Add form submit handler
        document.getElementById('createWardForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Ward created successfully!'
            }).then(() => {
                window.location.reload();
            });
        });

        // Add search functionality
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.querySelector('.table tbody');
        const rows = tableBody.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            rows.forEach(row => {
                const wardName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const wardType = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const doctor = row.querySelector('td:nth-child(6)').textContent.toLowerCase();

                if (wardName.includes(searchTerm) || 
                    wardType.includes(searchTerm) || 
                    doctor.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    function editWard(ward) {
        document.getElementById('edit_id').value = ward.WardID;
        document.getElementById('edit_ward_name').value = ward.WardName;
        document.getElementById('edit_ward_type').value = ward.WardTypeID;
        document.getElementById('edit_capacity').value = ward.Capacity;
        document.getElementById('edit_doctor').value = ward.DoctorID;
        
        editModal.show();
    }

    function updateWard() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Ward updated successfully!'
        }).then(() => {
            editModal.hide();
            window.location.reload();
        });
    }

    function deleteWard(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Ward has been deleted.', 'success')
                .then(() => {
                    window.location.reload();
                });
            }
        });
    }
</script>
@endsection
