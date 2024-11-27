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
        Schema::create('tbl_doctors', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->string('email')->unique();  // Email của bác sĩ
            $table->string('phone', 15)->nullable();  // Số điện thoại của bác sĩ
            $table->string('password');  // Mật khẩu của bác sĩ
            $table->string('name');  // Tên của bác sĩ
            $table->string('description')->nullable();  // Mô tả về bác sĩ
            $table->integer('price');  // Giá dịch vụ của bác sĩ
            $table->string('role');  // Vai trò của bác sĩ
            $table->boolean('active')->default(1);  // Trạng thái hoạt động của bác sĩ
            $table->string('avatar')->nullable();  // Avatar của bác sĩ
            $table->timestamps();  // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_doctors');
    }
};
