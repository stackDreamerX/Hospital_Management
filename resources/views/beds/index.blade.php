@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bed Management</h4>
                    <div class="float-end">
                        <a href="{{ route('beds.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Bed
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('beds.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select name="ward_id" class="form-select">
                                        <option value="">All Wards</option>
                                        @foreach($wards as $ward)
                                            <option value="{{ $ward->WardID }}" {{ $wardId == $ward->WardID ? 'selected' : '' }}>
                                                {{ $ward->WardName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ $status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ $status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('beds.index') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Bed Number</th>
                                    <th>Ward</th>
                                    <th>Status</th>
                                    <th>Current Patient</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($beds as $bed)
                                <tr>
                                    <td>{{ $bed->BedNumber }}</td>
                                    <td>{{ $bed->ward->WardName ?? 'N/A' }}</td>
                                    <td>
                                        @if($bed->Status == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($bed->Status == 'occupied')
                                            <span class="badge bg-danger">Occupied</span>
                                        @else
                                            <span class="badge bg-warning">Maintenance</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bed->Status === 'occupied' && $bed->currentAllocation)
                                            {{ $bed->currentAllocation->patient->FullName ?? 'Unknown' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('beds.show', $bed) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('beds.edit', $bed) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($bed->Status != 'occupied')
                                            <form action="{{ route('beds.destroy', $bed) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bed?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No beds found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="admin-custom-pagination">
                        {{ $beds->appends(request()->query())->links('vendor.pagination.admin.bootstrap-4') }}
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Bed Status Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alert alert-success">
                                        <h4>Available Beds</h4>
                                        <h2>{{ $beds->where('Status', 'available')->count() }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-danger">
                                        <h4>Occupied Beds</h4>
                                        <h2>{{ $beds->where('Status', 'occupied')->count() }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-warning">
                                        <h4>Maintenance Beds</h4>
                                        <h2>{{ $beds->where('Status', 'maintenance')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection