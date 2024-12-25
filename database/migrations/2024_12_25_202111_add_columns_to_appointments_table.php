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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('Reason')->nullable(); // Lý do của cuộc hẹn
            $table->text('Symptoms')->nullable(); // Triệu chứng
            $table->text('Notes')->nullable(); // Ghi chú
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['Reason', 'Symptoms', 'Notes']);
        });
    }
};
