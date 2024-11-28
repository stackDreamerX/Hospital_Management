<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_service_doctor', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->unsignedBigInteger('service_id');  // Mã định danh dịch vụ
            $table->unsignedBigInteger('doctor_id');  // Mã định danh bác sĩ
            $table->timestamps();  // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_services_doctor');
    }
};
