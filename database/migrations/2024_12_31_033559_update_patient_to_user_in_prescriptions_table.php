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
        Schema::table('prescriptions', function (Blueprint $table) {
            // Xóa khóa ngoại và cột cũ
            $table->dropForeign(['PatientID']);
            $table->dropColumn('PatientID');

            // Thêm cột mới UserID
            $table->foreignId('UserID')->after('PrescriptionDate')->constrained('users', 'UserID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Xóa khóa ngoại và cột mới
            $table->dropForeign(['UserID']);
            $table->dropColumn('UserID');

            // Thêm lại cột PatientID
            $table->foreignId('PatientID')->constrained('patients', 'PatientID')->onDelete('cascade');
        });
    }
};
