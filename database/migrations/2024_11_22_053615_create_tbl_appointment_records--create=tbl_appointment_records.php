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
        Schema::create('tbl_appointment_records', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->foreignId('appointment_id')->constrained('tbl_appointments')->onDelete('cascade');  // Mã cuộc hẹn
            $table->string('reason');  // Lý do ghi nhận
            $table->text('description')->nullable();  // Mô tả ghi nhận
            $table->string('status_before');  // Trạng thái trước khi ghi nhận
            $table->string('status_after');  // Trạng thái sau khi ghi nhận
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
