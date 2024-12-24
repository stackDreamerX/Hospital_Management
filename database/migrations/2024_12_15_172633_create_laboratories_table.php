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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id('LaboratoryID');
            $table->foreignId('LaboratoryTypeID')->constrained('laboratory_types','LaboratoryTypeID')->onDelete('cascade');
            $table->date('LaboratoryDate'); // Ngày xét nghiệm
            $table->time('LaboratoryTime'); // Thêm trường giờ xét nghiệm
            $table->date('LaboratoryDate');
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
        Schema::dropIfExists('laboratories');
    }
};
