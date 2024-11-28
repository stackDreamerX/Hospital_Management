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
        Schema::create('tbl_appointments', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->foreignId('doctor_id')->constrained('tbl_doctors')->onDelete('cascade');  // Mã bác sĩ
            $table->foreignId('patient_id')->constrained('tbl_patients')->onDelete('cascade');  // Mã bệnh nhân
            $table->integer('num_enter_order');  // Thứ tự nhập cuộc hẹn
            $table->integer('position');  // Vị trí cuộc hẹn
            $table->string('appointment_time');  // Thời gian hẹn
            $table->date('date');  // Ngày hẹn
            $table->string('status');  // Trạng thái cuộc hẹn
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
