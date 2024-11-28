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
        Schema::create('tbl_treatments', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->foreignId('appointment_id')->constrained('tbl_appointments')->onDelete('cascade');  // Mã cuộc hẹn
            $table->string('name');  // Tên phương pháp điều trị
            $table->string('type');  // Loại phương pháp điều trị
            $table->integer('times');  // Số lần thực hiện
            $table->string('purpose');  // Mục đích điều trị
            $table->string('instruction')->nullable();  // Hướng dẫn điều trị
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
