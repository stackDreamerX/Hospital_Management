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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('AppointmentID');
            $table->date('AppointmentDate');
            $table->time('AppointmentTime');
            $table->foreignId('UserID')->constrained('users','UserID')->onDelete('cascade');
            $table->foreignId('DoctorID')->constrained('doctors','DoctorID')->onDelete('cascade');
            $table->enum('Status', ['rejected', 'pending', 'approved', 'completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
