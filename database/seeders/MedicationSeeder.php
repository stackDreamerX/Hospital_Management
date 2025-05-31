<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;
use App\Models\PatientWardAllocation;
use App\Models\User;
use Carbon\Carbon;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patient allocations (both active and past)
        $allocations = PatientWardAllocation::all();

        if ($allocations->isEmpty()) {
            $this->command->info('No patient allocations found. Skipping medication seeding.');
            return;
        }

        // Get doctor users who can prescribe medications
        $doctors = User::where('RoleID', 'doctor')->get();

        if ($doctors->isEmpty()) {
            $this->command->info('No doctors found. Skipping medication seeding.');
            return;
        }

        // Common medications by category
        $medications = [
            // Antibiotics
            [
                'name' => 'Amoxicillin',
                'dosage' => '500mg',
                'frequency' => 'Every 8 hours'
            ],
            [
                'name' => 'Azithromycin',
                'dosage' => '250mg',
                'frequency' => 'Once daily'
            ],
            [
                'name' => 'Ciprofloxacin',
                'dosage' => '500mg',
                'frequency' => 'Every 12 hours'
            ],
            [
                'name' => 'Ceftriaxone',
                'dosage' => '1g',
                'frequency' => 'Every 24 hours IV'
            ],

            // Pain management
            [
                'name' => 'Acetaminophen',
                'dosage' => '1000mg',
                'frequency' => 'Every 6 hours as needed'
            ],
            [
                'name' => 'Ibuprofen',
                'dosage' => '600mg',
                'frequency' => 'Every 6 hours with food'
            ],
            [
                'name' => 'Tramadol',
                'dosage' => '50mg',
                'frequency' => 'Every 6 hours as needed for severe pain'
            ],
            [
                'name' => 'Morphine Sulfate',
                'dosage' => '2mg',
                'frequency' => 'Every 4 hours IV as needed for severe pain'
            ],

            // Cardiovascular
            [
                'name' => 'Lisinopril',
                'dosage' => '10mg',
                'frequency' => 'Once daily'
            ],
            [
                'name' => 'Metoprolol',
                'dosage' => '25mg',
                'frequency' => 'Twice daily'
            ],
            [
                'name' => 'Atorvastatin',
                'dosage' => '20mg',
                'frequency' => 'Once daily at bedtime'
            ],
            [
                'name' => 'Furosemide',
                'dosage' => '40mg',
                'frequency' => 'Once daily in the morning'
            ],

            // Respiratory
            [
                'name' => 'Albuterol',
                'dosage' => '2 puffs',
                'frequency' => 'Every 4-6 hours as needed'
            ],
            [
                'name' => 'Fluticasone',
                'dosage' => '2 puffs',
                'frequency' => 'Twice daily'
            ],

            // Gastrointestinal
            [
                'name' => 'Omeprazole',
                'dosage' => '20mg',
                'frequency' => 'Once daily before breakfast'
            ],
            [
                'name' => 'Ondansetron',
                'dosage' => '4mg',
                'frequency' => 'Every 8 hours as needed for nausea'
            ],

            // Other
            [
                'name' => 'Lorazepam',
                'dosage' => '1mg',
                'frequency' => 'Every 8 hours as needed for anxiety'
            ],
            [
                'name' => 'Insulin Regular',
                'dosage' => 'Variable',
                'frequency' => 'Per sliding scale'
            ],
            [
                'name' => 'Heparin',
                'dosage' => '5000 units',
                'frequency' => 'Every 8 hours subcutaneous'
            ],
            [
                'name' => 'Prednisone',
                'dosage' => '20mg',
                'frequency' => 'Once daily in the morning'
            ]
        ];

        // Common instructions
        $instructions = [
            'Take with food',
            'Take on an empty stomach',
            'Do not crush or chew',
            'May cause drowsiness',
            'Avoid alcohol',
            'Take with a full glass of water',
            'May cause stomach upset',
            'Do not take with dairy products',
            'Avoid sun exposure',
            'Monitor blood pressure regularly',
            'Report any unusual bleeding',
            'Discontinue if rash develops',
            'Take at the same time each day',
            'Store at room temperature',
            'Shake well before using'
        ];

        $medicationCount = 0;

        // For each allocation, create 1-4 medications
        foreach ($allocations as $allocation) {
            $medicationsPerPatient = rand(1, 4);

            // Shuffle medications to get random ones
            shuffle($medications);

            // Use a subset of medications for this patient
            $patientMedications = array_slice($medications, 0, $medicationsPerPatient);

            foreach ($patientMedications as $medication) {
                // For active allocations
                if ($allocation->DischargeDate === null) {
                    $startDate = Carbon::parse($allocation->AllocationDate)
                        ->addDays(rand(0, 3)); // Start 0-3 days after admission

                    // 70% of medications have an end date
                    if (rand(1, 10) <= 7) {
                        $endDate = (clone $startDate)->addDays(rand(3, 14)); // 3-14 day course
                    } else {
                        $endDate = null; // Ongoing medication
                    }
                }
                // For past allocations
                else {
                    $startDate = Carbon::parse($allocation->AllocationDate)
                        ->addDays(rand(0, 3)); // Start 0-3 days after admission

                    // End date is before or on discharge date
                    $maxDays = Carbon::parse($allocation->AllocationDate)
                        ->diffInDays(Carbon::parse($allocation->DischargeDate));

                    $courseDuration = min(rand(3, 14), $maxDays); // 3-14 day course or until discharge
                    $endDate = (clone $startDate)->addDays($courseDuration);

                    // Ensure end date is not after discharge
                    if ($endDate->isAfter(Carbon::parse($allocation->DischargeDate))) {
                        $endDate = Carbon::parse($allocation->DischargeDate);
                    }
                }

                // Create the medication record
                Medication::create([
                    'AllocationID' => $allocation->AllocationID,
                    'PatientID' => $allocation->PatientID,
                    'medication_name' => $medication['name'],
                    'dosage' => $medication['dosage'],
                    'frequency' => $medication['frequency'],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'instructions' => $instructions[array_rand($instructions)],
                    'doctor_id' => $doctors->random()->UserID,
                    'created_at' => $startDate,
                    'updated_at' => $startDate
                ]);

                $medicationCount++;
            }
        }

        $this->command->info('Created ' . $medicationCount . ' medication records.');
    }
}