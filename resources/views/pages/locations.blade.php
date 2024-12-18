@extends('layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Locations and Directions</h1>

    <!-- Locations Section -->
    <div class="row g-4">
        <!-- Ohio Location -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('public/Frontend/images/ThongNhat-location.png') }}" class="card-img-top" alt="Ohio Location">
                <div class="card-body">
                    <h5 class="card-title">Medic Hospital Thong Nhat</h5>
                    <p class="card-text">1 Lý Thường Kiệt, Phường 7, Tân Bình, Hồ Chí Minh 700000, Việt Nam
                    </p>
                    <a href="https://maps.app.goo.gl/KQguonZejpjuxxhJA" target="_blank" class="btn btn-primary">Get Direction</a>
                </div>
            </div>
        </div>

        <!-- Florida Location -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('public/Frontend/images/ChoRay-location.png') }}" class="card-img-top" alt="Florida Location">
                <div class="card-body">
                    <h5 class="card-title">Medic Hospital Cho Ray</h5>
                    <p class="card-text">201B Đ. Nguyễn Chí Thanh, Phường 12, Quận 5, Hồ Chí Minh 700000
                    </p>
                    <a href="https://maps.app.goo.gl/CBHjiyWks5MUQC4JA" target="_blank" class="btn btn-primary">Get Direction</a>
                </div>
            </div>
        </div>

        <!-- Abu Dhabi Location -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('public/Frontend/images/DaKhoa-location.jpg') }}" class="card-img-top" alt="Abu Dhabi Location">
                <div class="card-body">
                    <h5 class="card-title">Medic Hospital Da Khoa Thu Duc</h5>
                    <p class="card-text">64 Lê Văn Chí, Phường Linh Trung, Thủ Đức, Hồ Chí Minh, Việt Nam
                    </p>
                    <a href="https://maps.app.goo.gl/8XgAZ6jFdpw3Ex696" target="_blank" class="btn btn-primary">Get Direction</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
