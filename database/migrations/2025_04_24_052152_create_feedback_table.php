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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('subject', 255);
            $table->text('message');
            $table->integer('rating')->comment('Rating from 1-5');
            $table->string('category', 50)->nullable(); // e.g., 'doctor', 'facility', 'staff', 'treatment', 'overall'
            $table->string('department', 100)->nullable();
            $table->string('doctor_name', 100)->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_highlighted')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamp('admin_reviewed_at')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
