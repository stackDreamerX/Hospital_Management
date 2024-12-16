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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id('TreatmentID');
            $table->foreignId('TreatmentTypeID')->constrained('treatment_types','TreatmentTypeID')->onDelete('cascade');
            $table->date('TreatmentDate');
            $table->foreignId('PatientID')->constrained('patients','PatientID')->onDelete('cascade');
            $table->foreignId('DoctorID')->constrained('doctors','DoctorID')->onDelete('cascade');
            $table->integer('TotalPrice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
