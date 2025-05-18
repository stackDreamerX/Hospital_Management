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
        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('pricing_vn', 10, 2)->default(300000.00)->after('AvailableHours');
            $table->decimal('pricing_foreign', 10, 2)->default(600000.00)->after('pricing_vn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('pricing_vn');
            $table->dropColumn('pricing_foreign');
        });
    }
};