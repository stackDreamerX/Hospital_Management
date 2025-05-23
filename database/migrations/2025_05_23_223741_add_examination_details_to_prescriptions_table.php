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
            $table->text('Diagnosis')->nullable()->after('TotalPrice');
            $table->text('TestResults')->nullable()->after('Diagnosis');
            $table->string('BloodPressure', 20)->nullable()->after('TestResults');
            $table->integer('HeartRate')->nullable()->after('BloodPressure');
            $table->string('Temperature', 10)->nullable()->after('HeartRate');
            $table->integer('SpO2')->nullable()->after('Temperature');
            $table->text('Instructions')->nullable()->after('SpO2');
            $table->string('Status')->default('Pending')->after('Instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'Diagnosis',
                'TestResults',
                'BloodPressure',
                'HeartRate',
                'Temperature',
                'SpO2',
                'Instructions',
                'Status'
            ]);
        });
    }
};
