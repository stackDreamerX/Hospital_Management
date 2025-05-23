@extends('layout')

@section('title', 'Thông tin cá nhân | Medic Hospital')

@section('styles')
<style>
    .profile-header {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #3498db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin-right: 20px;
    }
    .nav-pills .nav-link.active {
        background-color: #3498db;
    }
    .form-label {
        font-weight: 500;
    }
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #edf2f9;
        padding: 1rem 1.5rem;
    }
    .card-title {
        margin-bottom: 0;
        color: #344767;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.dashboard') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thông tin cá nhân</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="profile-header d-flex align-items-center">
        <div class="profile-img">
            {{ substr($user->FullName, 0, 1) }}
        </div>
        <div>
            <h3>{{ $user->FullName }}</h3>
            <p class="text-muted mb-0">{{ $user->Email }}</p>
            <p class="mb-0">
                <span class="badge bg-primary">
                    @if($user->RoleID === 'patient')
                        Bệnh nhân
                    @elseif($user->RoleID === 'doctor')
                        Bác sĩ
                    @else
                        Quản trị viên
                    @endif
                </span>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-3 mb-4">
            <div class="list-group">
                <a href="#personal-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-user me-2"></i> Thông tin cá nhân
                </a>
                <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-lock me-2"></i> Bảo mật
                </a>
                <a href="#appointments" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-calendar-check me-2"></i> Lịch hẹn
                </a>
                <a href="{{ route('users.prescriptions') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-prescription-bottle-alt me-2"></i> Đơn thuốc
                </a>
            </div>
        </div>

        <div class="col-12 col-lg-9">
            <div class="tab-content">
                <!-- Thông tin cá nhân -->
                <div class="tab-pane fade show active" id="personal-info">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Thông tin cá nhân</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="FullName" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control @error('FullName') is-invalid @enderror"
                                               id="FullName" name="FullName" value="{{ old('FullName', $user->FullName) }}">
                                        @error('FullName')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                               id="Email" name="Email" value="{{ old('Email', $user->Email) }}">
                                        @error('Email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="PhoneNumber" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control @error('PhoneNumber') is-invalid @enderror"
                                               id="PhoneNumber" name="PhoneNumber" value="{{ old('PhoneNumber', $user->PhoneNumber) }}">
                                        @error('PhoneNumber')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="DateOfBirth" class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control @error('DateOfBirth') is-invalid @enderror"
                                               id="DateOfBirth" name="DateOfBirth" value="{{ old('DateOfBirth', $user->DateOfBirth) }}">
                                        @error('DateOfBirth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="Gender" class="form-label">Giới tính</label>
                                        <select class="form-select @error('Gender') is-invalid @enderror" id="Gender" name="Gender">
                                            <option value="" {{ old('Gender', $user->Gender) == null ? 'selected' : '' }}>Chọn giới tính</option>
                                            <option value="Male" {{ old('Gender', $user->Gender) == 'Male' ? 'selected' : '' }}>Nam</option>
                                            <option value="Female" {{ old('Gender', $user->Gender) == 'Female' ? 'selected' : '' }}>Nữ</option>
                                            <option value="Other" {{ old('Gender', $user->Gender) == 'Other' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                        @error('Gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vai trò</label>
                                        <input type="text" class="form-control"
                                               value="@if($user->RoleID === 'patient') Bệnh nhân @elseif($user->RoleID === 'doctor') Bác sĩ @else Quản trị viên @endif"
                                               disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Tên đăng nhập</label>
                                        <input type="text" class="form-control" id="username" value="{{ $user->username }}" disabled>
                                        <small class="text-muted">Tên đăng nhập không thể thay đổi</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Address" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control @error('Address') is-invalid @enderror"
                                              id="Address" name="Address" rows="3">{{ old('Address', $user->Address) }}</textarea>
                                    @error('Address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Lưu thông tin
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bảo mật -->
                <div class="tab-pane fade" id="security">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Thay đổi mật khẩu</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-1"></i> Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lịch hẹn -->
                <div class="tab-pane fade" id="appointments">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Lịch hẹn gần đây</h5>
                            <a href="{{ route('users.appointments') }}" class="btn btn-sm btn-outline-primary">
                                Xem tất cả
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Lịch hẹn gần đây sẽ được hiển thị ở đây -->
                            <p class="text-center text-muted">Đang tải lịch hẹn...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Load recent appointments via AJAX
    fetch('{{ route("users.appointments") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Handle the data and update the appointments tab
            const appointmentsContainer = document.querySelector('#appointments .card-body');

            // This will be implemented with actual appointment data later
            // For now, just show a placeholder message
            appointmentsContainer.innerHTML = '<p class="text-center text-muted">Chức năng đang được phát triển</p>';
        })
        .catch(error => {
            console.error('Error fetching appointments:', error);
            const appointmentsContainer = document.querySelector('#appointments .card-body');
            appointmentsContainer.innerHTML = '<p class="text-center text-danger">Không thể tải lịch hẹn. Vui lòng thử lại sau.</p>';
        });
});
</script>
@endsection