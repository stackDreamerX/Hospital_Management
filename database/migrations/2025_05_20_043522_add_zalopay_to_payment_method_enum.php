<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum values to include zalopay
        DB::statement("ALTER TABLE appointments MODIFY COLUMN payment_method ENUM('cash', 'vnpay', 'zalopay') DEFAULT 'cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE appointments MODIFY COLUMN payment_method ENUM('cash', 'vnpay') DEFAULT 'cash'");
    }
};