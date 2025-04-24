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
        Schema::table('treatments', function (Blueprint $table) {
            // First, get the current values as a backup
            $treatments = DB::table('treatments')->select('TreatmentID', 'Status')->get();
            
            // Drop the current ENUM column
            $table->dropColumn('Status');
        });
        
        Schema::table('treatments', function (Blueprint $table) {
            // Re-create the column as TEXT
            $table->text('Status')->after('TotalPrice')->nullable();
        });
        
        // Restore values from the backup, but now they can be any text value
        $treatments = DB::table('treatments')->select('TreatmentID', 'Status')->get();
        foreach ($treatments as $treatment) {
            if (!empty($treatment->Status)) {
                DB::table('treatments')
                    ->where('TreatmentID', $treatment->TreatmentID)
                    ->update(['Status' => $treatment->Status]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            // First, get the current values
            $treatments = DB::table('treatments')->select('TreatmentID', 'Status')->get();
            
            // Drop the TEXT column
            $table->dropColumn('Status');
            
            // Re-create the original ENUM column
            $table->enum('Status', ['Scheduled', 'Completed', 'Cancelled'])->after('TotalPrice')->default('Scheduled');
            
            // Restore values, but convert any non-enum values to 'Scheduled'
            foreach ($treatments as $treatment) {
                $status = $treatment->Status;
                if (!in_array($status, ['Scheduled', 'Completed', 'Cancelled'])) {
                    $status = 'Scheduled';
                }
                
                DB::table('treatments')
                    ->where('TreatmentID', $treatment->TreatmentID)
                    ->update(['Status' => 'Scheduled']);
            }
        });
    }
};
