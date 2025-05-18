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
        Schema::create('doctor_time_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['available', 'booked', 'cancelled', 'completed'])->default('available');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('DoctorID')->on('doctors')->onDelete('cascade');
            $table->foreign('appointment_id')->references('AppointmentID')->on('appointments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_time_slots');
    }
};