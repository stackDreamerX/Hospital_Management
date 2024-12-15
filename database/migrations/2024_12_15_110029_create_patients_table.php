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
        Schema::create('patients', function (Blueprint $table) {
            $table->id('PatientID'); // Khóa chính
            $table->foreignId('UserID')->constrained('users', 'UserID')->onDelete('cascade'); 
            $table->date('DateOfBirth')->nullable(false);
            $table->enum('Gender', ['male', 'female', 'other'])->nullable(false);
            $table->text('Address')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
