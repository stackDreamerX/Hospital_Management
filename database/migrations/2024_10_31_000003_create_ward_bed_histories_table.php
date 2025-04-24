<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWardBedHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ward_bed_histories', function (Blueprint $table) {
            $table->id('HistoryID');
            $table->unsignedBigInteger('WardBedID');
            $table->unsignedBigInteger('PatientID')->nullable();
            $table->dateTime('FromDate');
            $table->dateTime('ToDate')->nullable();
            $table->enum('Status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->text('Note')->nullable();
            $table->unsignedBigInteger('UpdatedByUserID');
            $table->timestamps();

            $table->foreign('WardBedID')->references('WardBedID')->on('ward_beds')->onDelete('cascade');
            $table->foreign('PatientID')->references('PatientID')->on('patients')->onDelete('set null');
            $table->foreign('UpdatedByUserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ward_bed_histories');
    }
} 