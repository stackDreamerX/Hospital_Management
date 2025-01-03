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
        Schema::table('prescription_details', function (Blueprint $table) {
            $table->string('Duration')->nullable()->after('Dosage'); // Thêm cột Duration sau cột Dosage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_details', function (Blueprint $table) {
            $table->dropColumn('Duration'); // Xóa cột Duration khi rollback
        });
    }
};
