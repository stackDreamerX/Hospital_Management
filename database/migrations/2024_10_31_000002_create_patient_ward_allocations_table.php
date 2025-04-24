<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientWardAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_ward_allocations', function (Blueprint $table) {
            $table->id('AllocationID');
            $table->unsignedBigInteger('PatientID');
            $table->unsignedBigInteger('WardBedID');
            $table->dateTime('AllocationDate');
            $table->dateTime('DischargeDate')->nullable();
            $table->string('Notes')->nullable();
            $table->unsignedBigInteger('AllocatedByUserID');
            $table->timestamps();

            $table->foreign('PatientID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('WardBedID')->references('WardBedID')->on('ward_beds')->onDelete('cascade');
            $table->foreign('AllocatedByUserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_ward_allocations');
    }
} 