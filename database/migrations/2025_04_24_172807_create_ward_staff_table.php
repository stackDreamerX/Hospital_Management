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
        Schema::create('ward_staff', function (Blueprint $table) {
            $table->id('WardStaffID');
            $table->foreignId('WardID')->constrained('wards','WardID')->onDelete('cascade');
            $table->string('WardStaffName', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ward_staff');
    }
};
