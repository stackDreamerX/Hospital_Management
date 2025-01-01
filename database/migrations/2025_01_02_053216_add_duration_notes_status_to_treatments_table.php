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
        Schema::table('treatments', function (Blueprint $table) {
            $table->string('Duration', 50)->nullable()->after('DoctorID');
            $table->text('Notes')->nullable()->after('Duration');
            $table->enum('Status', ['Scheduled', 'Completed', 'Cancelled'])->default('Scheduled')->after('Notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn(['Duration', 'Notes', 'Status']);
        });
    }
};
