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
        Schema::table('patient_ward_allocations', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign('patient_ward_allocations_patientid_foreign');
            
            // Add the new foreign key constraint to reference users table
            $table->foreign('PatientID')
                  ->references('UserID')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_ward_allocations', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign('patient_ward_allocations_patientid_foreign');
            
            // Recreate the original foreign key constraint
            $table->foreign('PatientID')
                  ->references('PatientID')
                  ->on('patients')
                  ->onDelete('cascade');
        });
    }
};
