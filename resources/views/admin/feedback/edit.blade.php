@extends('admin_layout')

@section('admin_content')
<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit mr-2"></i> Edit Feedback
        </h1>
        <div>
            <a href="{{ route('admin.feedback') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
            <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye mr-1"></i> View Details
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pen mr-1"></i> Feedback Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="subject" class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                id="subject" name="subject" value="{{ old('subject', $feedback->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="font-weight-bold">Category</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category">
                                        <option value="" {{ $feedback->category == '' ? 'selected' : '' }}>Select a category</option>
                                        <option value="doctor" {{ $feedback->category == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="facility" {{ $feedback->category == 'facility' ? 'selected' : '' }}>Facility</option>
                                        <option value="staff" {{ $feedback->category == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="treatment" {{ $feedback->category == 'treatment' ? 'selected' : '' }}>Treatment</option>
                                        <option value="overall" {{ $feedback->category == 'overall' ? 'selected' : '' }}>Overall Experience</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rating" class="font-weight-bold">Rating <span class="text-danger">*</span></label>
                                    <select class="form-control @error('rating') is-invalid @enderror" 
                                        id="rating" name="rating" required>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ $feedback->rating == $i ? 'selected' : '' }}>
                                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department" class="font-weight-bold">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                        id="department" name="department" value="{{ old('department', $feedback->department) }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="doctor_name" class="font-weight-bold">Doctor Name</label>
                                    <input type="text" class="form-control @error('doctor_name') is-invalid @enderror" 
                                        id="doctor_name" name="doctor_name" value="{{ old('doctor_name', $feedback->doctor_name) }}">
                                    @error('doctor_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="font-weight-bold">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                id="message" name="message" rows="5" required>{{ old('message', $feedback->message) }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                                <option value="pending" {{ $feedback->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="approved" {{ $feedback->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $feedback->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_notes" class="font-weight-bold">Admin Notes</label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $feedback->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional notes for administrative purposes. These are not visible to the patient.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_highlighted" 
                                    name="is_highlighted" value="1" {{ old('is_highlighted', $feedback->is_highlighted) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_highlighted">Highlight this feedback on public page</label>
                            </div>
                            <small class="form-text text-muted">
                                Highlighted feedback will be displayed prominently in the featured section.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_anonymous" 
                                    name="is_anonymous" value="1" {{ old('is_anonymous', $feedback->is_anonymous) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_anonymous">Anonymous feedback</label>
                            </div>
                            <small class="form-text text-muted">
                                If checked, the patient's name will not be displayed publicly.
                            </small>
                        </div>
                        
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                            <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Patient Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user mr-1"></i> Patient Information
                    </h6>
                </div>
                <div class="card-body">
                    @if($feedback->is_anonymous)
                        <div class="text-center py-3">
                            <i class="fas fa-user-secret fa-3x text-muted mb-3"></i>
                            <h5>Anonymous Feedback</h5>
                            <p class="text-muted">The patient chose to submit this feedback anonymously.</p>
                        </div>
                    @else
                        <div class="text-center mb-3">
                            <img class="img-profile rounded-circle" src="{{ asset('public/avatar.jpg') }}" width="80">
                            <h5 class="mt-3">{{ $feedback->user->FullName }}</h5>
                            <p class="text-muted mb-2">Patient ID: {{ $feedback->user->UserID }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Submission Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-1"></i> Submission Info
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>Submission Date:</span>
                            <span>{{ $feedback->created_at->format('M d, Y, h:i A') }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>Last Updated:</span>
                            <span>{{ $feedback->updated_at->format('M d, Y, h:i A') }}</span>
                        </li>
                        @if($feedback->admin_reviewed_at)
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>Reviewed:</span>
                            <span>{{ $feedback->admin_reviewed_at->format('M d, Y, h:i A') }}</span>
                        </li>
                        @endif
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>Current Status:</span>
                            <span>
                                @if($feedback->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($feedback->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($feedback->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary mb-3">Editing Tips</h6>
                    <ul class="pl-3 mb-0">
                        <li class="mb-2">You can edit the content of feedback to fix typos or formatting issues.</li>
                        <li class="mb-2">Use the admin notes field to add internal comments about this feedback.</li>
                        <li class="mb-2">Changing the status to "Approved" will make the feedback visible to the public.</li>
                        <li class="mb-2">Highlighting feedback will feature it prominently on the public feedback page.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 