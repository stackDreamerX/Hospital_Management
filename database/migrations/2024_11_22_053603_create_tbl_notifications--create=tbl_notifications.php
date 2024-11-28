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
        Schema::create('tbl_notifications', function (Blueprint $table) {
            $table->id();  // Mã định danh duy nhất
            $table->text('message');  // Nội dung thông báo
            $table->integer('record_id');  // Mã định danh của bản ghi liên quan
            $table->string('record_type');  // Loại bản ghi liên quan
            $table->foreignId('patient_id')->constrained('tbl_patients')->onDelete('cascade');  // Mã bệnh nhân
            $table->boolean('is_read')->default(0);  // Trạng thái đã đọc
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
