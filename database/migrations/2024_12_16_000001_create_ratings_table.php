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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->references('DoctorID')->on('doctors')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->references('AppointmentID')->on('appointments')->onDelete('cascade');
            $table->integer('doctor_rating')->nullable()->comment('1-5 stars for doctor');
            $table->integer('service_rating')->nullable()->comment('1-5 stars for clinic service');
            $table->integer('cleanliness_rating')->nullable()->comment('1-5 stars for clinic cleanliness');
            $table->integer('staff_rating')->nullable()->comment('1-5 stars for staff behavior');
            $table->integer('wait_time_rating')->nullable()->comment('1-5 stars for wait time');
            $table->text('feedback')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
}; 