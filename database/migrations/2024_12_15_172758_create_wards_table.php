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
        Schema::create('wards', function (Blueprint $table) {
            $table->id('WardID');
            $table->foreignId('WardTypeID')->constrained('ward_types','WardTypeID')->onDelete('cascade');
            $table->string('WardName', 100);
            $table->integer('Capacity');
            $table->integer('CurrentOccupancy');
            $table->foreignId('DoctorID')->constrained('doctors','DoctorID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
