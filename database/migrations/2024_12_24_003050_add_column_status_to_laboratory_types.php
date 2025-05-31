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
        Schema::table('laboratory_types', function (Blueprint $table) {
            $table->decimal('Price', 10, 2)->nullable(false); // Thêm trường Price
            $table->text('Description')->nullable();
            $table->string('Status', 20)->default('active'); // Thêm trường Status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratory_types', function (Blueprint $table) {
            $table->dropColumn('Price');
            $table->dropColumn('Description');
            $table->dropColumn('Status');
        });
    }
};
