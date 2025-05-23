<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('AllocationID');
            $table->unsignedBigInteger('PatientID');
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('instructions')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->timestamps();

            $table->foreign('AllocationID')->references('AllocationID')->on('patient_ward_allocations')->onDelete('cascade');
            $table->foreign('PatientID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medications');
    }
}
