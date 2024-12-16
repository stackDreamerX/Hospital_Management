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
        Schema::create('laboratory_details', function (Blueprint $table) {
            $table->id('LaboratoryDetailID');
            $table->foreignId('LaboratoryID')->constrained('laboratories','LaboratoryID')->onDelete('cascade');
            $table->integer('Price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_details');
    }
};
