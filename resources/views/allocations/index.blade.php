@extends('admin_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Bed Allocations</h4>
                    <div class="float-end">
                        <a href="{{ route('allocations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Allocation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('allocations.index') }}" method="GET" class="row g-3">
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
                                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active Allocations</option>
                                        <option value="discharged" {{ $status == 'discharged' ? 'selected' : '' }}>Discharged Patients</option>
                                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Allocations</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search patient name or bed" value="{{ $search ?? '' }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('allocations.index') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Ward</th>
                                    <th>Bed</th>
                                    <th>Allocation Date</th>
                                    <th>Discharge Date</th>
                                    <th>Status</th>
                                    <th>Allocated By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $allocation)
                                <tr>
                                    <td>{{ $allocation->patient->FullName ?? 'Unknown' }}</td>
                                    <td>{{ $allocation->wardBed->ward->WardName ?? 'N/A' }}</td>
                                    <td>{{ $allocation->wardBed->BedNumber ?? 'N/A' }}</td>
                                    <td>{{ $allocation->AllocationDate ? date('M d, Y H:i', strtotime($allocation->AllocationDate)) : 'N/A' }}</td>
                                    <td>{{ $allocation->DischargeDate ? date('M d, Y H:i', strtotime($allocation->DischargeDate)) : '-' }}</td>
                                    <td>
                                        @if($allocation->DischargeDate)
                                            <span class="badge bg-secondary">Discharged</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $allocation->allocatedBy->FullName ?? 'System' }}</td>
                                    <td>
                                        <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$allocation->DischargeDate)
                                            <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No allocations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $allocations->appends(request()->query())->links() }}
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Allocation Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-success">
                                        <h4>Active Allocations</h4>
                                        <h2>{{ $allocations->where('DischargeDate', null)->count() }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-secondary">
                                        <h4>Discharged Patients</h4>
                                        <h2>{{ $allocations->whereNotNull('DischargeDate')->count() }}</h2>
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