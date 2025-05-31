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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id('PrescriptionID'); // Primary Key
            $table->date('PrescriptionDate');
            $table->foreignId('PatientID')->constrained('users', 'UserID')->onDelete('cascade');
            $table->foreignId('DoctorID')->constrained('doctors', 'DoctorID')->onDelete('cascade');
            $table->integer('TotalPrice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
