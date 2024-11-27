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
        Schema::create('tbl_patients', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->string('email')->unique();  // Email của bệnh nhân
            $table->string('phone', 15);  // Số điện thoại của bệnh nhân
            $table->string('password');  // Mật khẩu của bệnh nhân
            $table->string('name');  // Tên của bệnh nhân
            $table->boolean('gender');  // Giới tính của bệnh nhân
            $table->date('birthday');  // Ngày sinh của bệnh nhân
            $table->string('address');  // Địa chỉ của bệnh nhân
            $table->string('avatar')->nullable();  // Avatar của bệnh nhân
            $table->timestamps();  // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
