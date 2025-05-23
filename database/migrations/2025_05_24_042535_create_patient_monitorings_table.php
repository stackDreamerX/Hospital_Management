<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_monitorings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('AllocationID');
            $table->unsignedBigInteger('PatientID');
            $table->string('blood_pressure')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('spo2')->nullable();
            $table->enum('treatment_outcome', ['improved', 'stable', 'worsened'])->default('stable');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->timestamp('recorded_at')->useCurrent();
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
        Schema::dropIfExists('patient_monitorings');
    }
}
