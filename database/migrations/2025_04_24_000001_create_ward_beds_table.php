<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWardBedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ward_beds', function (Blueprint $table) {
            $table->id('WardBedID');
            $table->unsignedBigInteger('WardID')->nullable();
            $table->string('BedNumber', 10);
            $table->enum('Status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->timestamps();

            $table->foreign('WardID')->references('WardID')->on('wards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ward_beds');
    }
} 