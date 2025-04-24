@extends('admin_layout')

@section('admin_content')
<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments mr-2"></i> All Feedback
        </h1>
        <a href="{{ route('admin.feedback') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
        </a>
    </div>
    
    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-1"></i> Filter Feedback
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.feedback.index') }}" method="GET" class="mb-0">
                <div class="row align-items-end">
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label for="status" class="small font-weight-bold">Status</label>
                        <select class="form-control form-control-sm" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label for="category" class="small font-weight-bold">Category</label>
                        <select class="form-control form-control-sm" id="category" name="category">
                            <option value="">All Categories</option>
                            <option value="doctor" {{ request('category') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            <option value="facility" {{ request('category') == 'facility' ? 'selected' : '' }}>Facility</option>
                            <option value="staff" {{ request('category') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="treatment" {{ request('category') == 'treatment' ? 'selected' : '' }}>Treatment</option>
                            <option value="overall" {{ request('category') == 'overall' ? 'selected' : '' }}>Overall Experience</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label for="rating" class="small font-weight-bold">Rating</label>
                        <select class="form-control form-control-sm" id="rating" name="rating">
                            <option value="">All Ratings</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label for="search" class="small font-weight-bold">Search</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="search" name="search" 
                                placeholder="Search..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_highlighted" 
                                name="is_highlighted" value="1" {{ request('is_highlighted') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_highlighted">Show only highlighted feedback</label>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter mr-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.feedback.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Feedback Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-1"></i> Feedback List
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Actions:</div>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-file-excel mr-1"></i> Export to Excel
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-file-pdf mr-1"></i> Export to PDF
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('feedback.public') }}" target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i> View Public Page
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(count($feedback) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Subject</th>
                                <th width="150">Patient</th>
                                <th width="120">Category</th>
                                <th width="100">Rating</th>
                                <th width="100">Status</th>
                                <th width="120">Date</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedback as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <a href="{{ route('feedback.show', $item->id) }}" class="font-weight-bold text-primary">
                                        {{ Str::limit($item->subject, 50) }}
                                    </a>
                                    @if($item->is_highlighted)
                                        <span class="badge badge-info ml-1">
                                            <i class="fas fa-star"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->is_anonymous ? 'Anonymous' : $item->user->FullName }}
                                </td>
                                <td>
                                    {{ $item->category ? ucfirst($item->category) : 'N/A' }}
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $item->rating)
                                                <i class="fas fa-star fa-sm"></i>
                                            @else
                                                <i class="far fa-star fa-sm"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($item->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('feedback.show', $item->id) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('feedback.edit', $item->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $feedback->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i> No feedback found matching your criteria.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 