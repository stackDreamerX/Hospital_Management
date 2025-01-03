@extends('admin_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('admin_content')


<style>
     modal {
    display: none; /* Ẩn modal ban đầu */
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050; /* Bootstrap 5 modal z-index */
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5); /* Overlay mờ */
    }

    .modal.fade {
    opacity: 0; /* Modal mờ khi chưa được hiển thị */
    transition: opacity 0.15s linear;
    }

    .modal.show {
    display: block; /* Hiển thị modal */
    opacity: 1;
    }

    .modal-dialog {
    position: relative;
    margin: 1.75rem auto; /* Center modal vertically */
    pointer-events: auto;
    max-width: 500px; /* Độ rộng mặc định */
    }

    .modal-dialog.modal-lg {
    max-width: 800px; /* Độ rộng modal lớn */
    }

    .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    border: none;
    border-radius: 0.5rem; /* Bo góc */
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Đổ bóng */
    }

    .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6; /* Border dưới */
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    }

    .modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    }

    .btn-close {
    background: none;
    border: none;
    -webkit-appearance: none;
    }

    .modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
    }

    .modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    }

</style>


<div class="container" style="padding: 20px;">
    <h2 style="color: #333; margin-bottom: 20px;">Treatment Management</h2>

    <!-- Treatment List -->
    <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color: #f8f9fa; padding: 15px;">Treatment List</div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px;">ID</th>
                            <th style="padding: 12px;">Type</th>
                            <th style="padding: 12px;">Date</th>
                            <th style="padding: 12px;">Patient</th>
                            <th style="padding: 12px;">Doctor</th>
                            <th style="padding: 12px;">Price</th>
                            <th style="padding: 12px;">Status</th>
                            <th style="padding: 12px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px;">{{ $treatment->TreatmentID }}</td>
                            <td style="padding: 12px;">{{ $treatment->treatmentType->TreatmentTypeName }}</td>
                            <td style="padding: 12px;">{{ $treatment->TreatmentDate }}</td>
                            <td style="padding: 12px;">{{ $treatment->user->FullName }}</td>
                            <td style="padding: 12px;">{{ $treatment->doctor->user->FullName }}</td>
                            <td style="padding: 12px;">{{ number_format($treatment->TotalPrice) }}</td>
                            <td style="padding: 12px;">
                                <span class="badge bg-{{
                                    $treatment->Status == 'Completed' ? 'success' : 'warning'
                                }}">
                                    {{ $treatment->Status }}
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <div class="dropdown">
                                    <button class="btn btn-link" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="viewDetails({{ $treatment->TreatmentID }})">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteTreatment({{ $treatment->TreatmentID }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No treatments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Treatment Details Modal -->
<div class="modal fade" id="treatmentDetailsModal" tabindex="-1" aria-labelledby="treatmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="treatmentDetailsModalLabel">Treatment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailsContent">
                    <!-- Treatment details will be dynamically loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printTreatmentDetails()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function viewDetails(id) {
    const url = `{{ route('admin.treatment.show', ['id' => '__id__']) }}`.replace('__id__', id);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to fetch treatment details. Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Hiển thị chi tiết treatment trong modal
            const detailsContent = document.getElementById('detailsContent');
            detailsContent.innerHTML = `
                <p><strong>Treatment Type:</strong> ${data.TypeName}</p>
                <p><strong>Patient:</strong> ${data.PatientName}</p>
                <p><strong>Doctor:</strong> ${data.DoctorName}</p>
                <p><strong>Date:</strong> ${data.TreatmentDate}</p>
                <p><strong>Price:</strong>${data.TotalPrice}</p>
                <p><strong>Status:</strong>
                    <span class="badge bg-${data.Status === 'Completed' ? 'success' : 'warning'}">
                        ${data.Status}
                    </span>
                </p>
                <p><strong>Description:</strong> ${data.Description || 'N/A'}</p>
            `;

            // Hiển thị modal
            const treatmentDetailsModal = new bootstrap.Modal(document.getElementById('treatmentDetailsModal'));
            treatmentDetailsModal.show();
        })
        .catch(error => {
            console.error('Error fetching treatment details:', error);
            Swal.fire('Error', 'Failed to load treatment details', 'error');
        });
}

function printTreatmentDetails() {
    // Tạo nội dung để in từ modal
    const content = document.getElementById('detailsContent').innerHTML;
    const newWindow = window.open('', '_blank');
    newWindow.document.write('<html><head><title>Treatment Details</title></head><body>');
    newWindow.document.write(content);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.print();
}


    function deleteTreatment(id) {
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
                const url = `{{ route('admin.treatment.destroy', ['id' => '__id__']) }}`.replace('__id__', id);

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire('Deleted!', 'Treatment has been deleted.', 'success')
                        .then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error('Failed to delete treatment');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to delete treatment', 'error');
                });
            }
        });
    }
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
