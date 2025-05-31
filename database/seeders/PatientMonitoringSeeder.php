<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatientMonitoring;
use App\Models\PatientWardAllocation;
use App\Models\User;
use Carbon\Carbon;

class PatientMonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active patient allocations
        $activeAllocations = PatientWardAllocation::whereNull('DischargeDate')->get();

        if ($activeAllocations->isEmpty()) {
            $this->command->info('No active allocations found. Skipping patient monitoring seeding.');
            return;
        }

        // Get doctor users who can record monitoring
        $doctors = User::where('RoleID', 'doctor')->get();

        if ($doctors->isEmpty()) {
            $this->command->info('No doctors found. Skipping patient monitoring seeding.');
            return;
        }

        // Treatment outcome options
        $treatmentOutcomes = [
            'improved',
            'stable',
            'worsened'
        ];

        // Notes options
        $notes = [
            'Patient resting comfortably',
            'Patient reports mild pain',
            'Patient reports improved symptoms',
            'Patient experiencing nausea',
            'Patient experiencing headache',
            'Patient sleeping well',
            'Patient has good appetite',
            'Patient reports difficulty sleeping',
            'Patient experiencing shortness of breath',
            'Patient showing signs of improvement',
            null
        ];

        $monitoringRecords = 0;

        // For each active allocation, create multiple monitoring records
        foreach ($activeAllocations as $allocation) {
            // Determine how many days the patient has been hospitalized
            $daysHospitalized = Carbon::now()->diffInDays($allocation->AllocationDate);

            // Create 2-4 monitoring records per day of hospitalization
            $recordsPerDay = rand(2, 4);
            $totalRecords = $daysHospitalized * $recordsPerDay;

            // For very recent admissions, ensure at least 1 record
            if ($totalRecords < 1) {
                $totalRecords = 1;
            }

            // Cap at 30 records per patient to avoid excessive data
            $totalRecords = min($totalRecords, 30);

            for ($i = 0; $i < $totalRecords; $i++) {
                // Calculate a random time during the hospitalization period
                $recordedAt = Carbon::parse($allocation->AllocationDate)
                    ->addMinutes(rand(0, $daysHospitalized * 24 * 60));

                // Ensure the recorded_at is not in the future
                if ($recordedAt->isFuture()) {
                    $recordedAt = Carbon::now()->subHours(rand(1, 12));
                }

                // Generate realistic vital signs
                $bloodPressure = rand(100, 140) . '/' . rand(60, 90);
                $heartRate = rand(60, 100);
                $temperature = round(rand(360, 385) / 10, 1); // 36.0 to 38.5
                $spo2 = rand(92, 100);

                // Create the monitoring record
                PatientMonitoring::create([
                    'AllocationID' => $allocation->AllocationID,
                    'PatientID' => $allocation->PatientID,
                    'blood_pressure' => $bloodPressure,
                    'heart_rate' => $heartRate,
                    'temperature' => $temperature,
                    'spo2' => $spo2,
                    'treatment_outcome' => $treatmentOutcomes[array_rand($treatmentOutcomes)],
                    'notes' => $notes[array_rand($notes)],
                    'doctor_id' => $doctors->random()->UserID,
                    'recorded_at' => $recordedAt,
                    'created_at' => $recordedAt,
                    'updated_at' => $recordedAt
                ]);

                $monitoringRecords++;
            }
        }

        // Add monitoring records for past allocations (discharged patients)
        $pastAllocations = PatientWardAllocation::whereNotNull('DischargeDate')
            ->orderBy('DischargeDate', 'desc')
            ->limit(10) // Only the 10 most recent discharged patients
            ->get();

        foreach ($pastAllocations as $allocation) {
            // Calculate the length of stay
            $lengthOfStay = Carbon::parse($allocation->AllocationDate)
                ->diffInDays(Carbon::parse($allocation->DischargeDate));

            // Create 1-3 records per day of stay
            $recordsPerDay = rand(1, 3);
            $totalRecords = $lengthOfStay * $recordsPerDay;

            // Cap at 20 records per past patient
            $totalRecords = min($totalRecords, 20);

            for ($i = 0; $i < $totalRecords; $i++) {
                // Calculate a random time during the hospitalization period
                $recordedAt = Carbon::parse($allocation->AllocationDate)
                    ->addMinutes(rand(0, $lengthOfStay * 24 * 60));

                // Ensure the recorded_at is before discharge
                if ($recordedAt->isAfter(Carbon::parse($allocation->DischargeDate))) {
                    $recordedAt = Carbon::parse($allocation->DischargeDate)->subHours(rand(1, 12));
                }

                // Generate realistic vital signs
                $bloodPressure = rand(100, 140) . '/' . rand(60, 90);
                $heartRate = rand(60, 100);
                $temperature = round(rand(360, 385) / 10, 1); // 36.0 to 38.5
                $spo2 = rand(92, 100);

                // Create the monitoring record
                PatientMonitoring::create([
                    'AllocationID' => $allocation->AllocationID,
                    'PatientID' => $allocation->PatientID,
                    'blood_pressure' => $bloodPressure,
                    'heart_rate' => $heartRate,
                    'temperature' => $temperature,
                    'spo2' => $spo2,
                    'treatment_outcome' => $treatmentOutcomes[array_rand($treatmentOutcomes)],
                    'notes' => $notes[array_rand($notes)],
                    'doctor_id' => $doctors->random()->UserID,
                    'recorded_at' => $recordedAt,
                    'created_at' => $recordedAt,
                    'updated_at' => $recordedAt
                ]);

                $monitoringRecords++;
            }
        }

        $this->command->info('Created ' . $monitoringRecords . ' patient monitoring records.');
    }
}