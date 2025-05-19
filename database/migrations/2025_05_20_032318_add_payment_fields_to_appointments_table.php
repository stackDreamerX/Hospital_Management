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
            $table->enum('payment_method', ['cash', 'vnpay'])->default('cash')->after('Status');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('payment_method');
            $table->string('payment_id')->nullable()->after('payment_status');
            $table->decimal('amount', 10, 2)->nullable()->after('payment_id');
            $table->json('payment_details')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'payment_id', 'amount', 'payment_details']);
        });
    }
};
