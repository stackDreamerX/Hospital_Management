<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatientWardAllocation;
use App\Models\WardBed;
use App\Models\User;
use Carbon\Carbon;

class PatientWardAllocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patient users
        $patients = User::where('RoleID', 'patient')->get();

        if ($patients->isEmpty()) {
            $this->command->info('No patients found. Skipping patient allocation seeding.');
            return;
        }

        // Get admin/doctor users who can allocate beds
        $staff = User::whereIn('RoleID', ['administrator', 'doctor'])->get();

        if ($staff->isEmpty()) {
            $this->command->info('No staff found. Skipping patient allocation seeding.');
            return;
        }

        // Get all available beds
        $availableBeds = WardBed::where('Status', 'available')->get();

        // Set some beds as occupied and create allocations
        $occupiedBedsCount = min(count($availableBeds) * 0.6, 30); // 60% of available beds or max 30

        // Common medical reasons for hospitalization
        $admissionReasons = [
            'Pneumonia requiring IV antibiotics',
            'Acute appendicitis - post-surgical recovery',
            'Severe dehydration requiring IV fluids',
            'Diabetic ketoacidosis',
            'Congestive heart failure exacerbation',
            'Post-surgical recovery - hip replacement',
            'Post-surgical recovery - knee replacement',
            'Severe asthma exacerbation',
            'Gastrointestinal bleeding',
            'Acute kidney injury',
            'Cellulitis requiring IV antibiotics',
            'Stroke rehabilitation',
            'Severe urinary tract infection',
            'Acute pancreatitis',
            'COPD exacerbation',
            'Post-surgical recovery - appendectomy',
            'Post-surgical recovery - cholecystectomy',
            'Severe anemia requiring transfusion',
            'Syncope workup',
            'Acute pyelonephritis'
        ];

        // Create active allocations (patients currently in hospital)
        $activeAllocations = [];
        for ($i = 0; $i < $occupiedBedsCount / 2; $i++) {
            if ($availableBeds->isEmpty()) {
                break;
            }

            // Get a random bed and mark it as occupied
            $bedIndex = rand(0, count($availableBeds) - 1);
            $bed = $availableBeds[$bedIndex];
            $bed->Status = 'occupied';
            $bed->save();

            // Remove this bed from available beds
            $availableBeds->forget($bedIndex);
            $availableBeds = $availableBeds->values(); // Re-index the collection

            // Create allocation
            $allocationDate = Carbon::now()->subDays(rand(1, 10));

            $allocation = PatientWardAllocation::create([
                'PatientID' => $patients->random()->UserID,
                'WardBedID' => $bed->WardBedID,
                'AllocationDate' => $allocationDate,
                'DischargeDate' => null, // Still in hospital
                'Notes' => $admissionReasons[array_rand($admissionReasons)],
                'AllocatedByUserID' => $staff->random()->UserID,
                'created_at' => $allocationDate,
                'updated_at' => $allocationDate
            ]);

            $activeAllocations[] = $allocation;
        }

        // Create past allocations (discharged patients)
        for ($i = 0; $i < $occupiedBedsCount / 2; $i++) {
            // Get a random bed (we don't need to mark it as occupied since these are past allocations)
            $bed = WardBed::inRandomOrder()->first();

            // Create allocation with discharge date
            $allocationDate = Carbon::now()->subDays(rand(15, 90)); // Admission 15-90 days ago
            $stayDuration = rand(3, 14); // Hospital stay of 3-14 days
            $dischargeDate = (clone $allocationDate)->addDays($stayDuration);

            PatientWardAllocation::create([
                'PatientID' => $patients->random()->UserID,
                'WardBedID' => $bed->WardBedID,
                'AllocationDate' => $allocationDate,
                'DischargeDate' => $dischargeDate,
                'Notes' => $admissionReasons[array_rand($admissionReasons)],
                'AllocatedByUserID' => $staff->random()->UserID,
                'created_at' => $allocationDate,
                'updated_at' => $dischargeDate
            ]);
        }

        // Create a few long-term patients
        for ($i = 0; $i < 3; $i++) {
            if ($availableBeds->isEmpty()) {
                break;
            }

            // Get a random bed and mark it as occupied
            $bedIndex = rand(0, count($availableBeds) - 1);
            $bed = $availableBeds[$bedIndex];
            $bed->Status = 'occupied';
            $bed->save();

            // Remove this bed from available beds
            $availableBeds->forget($bedIndex);
            $availableBeds = $availableBeds->values(); // Re-index the collection

            // Create allocation with a long stay (30+ days)
            $allocationDate = Carbon::now()->subDays(rand(30, 60));

            $allocation = PatientWardAllocation::create([
                'PatientID' => $patients->random()->UserID,
                'WardBedID' => $bed->WardBedID,
                'AllocationDate' => $allocationDate,
                'DischargeDate' => null, // Still in hospital
                'Notes' => 'Long-term care: ' . $admissionReasons[array_rand($admissionReasons)],
                'AllocatedByUserID' => $staff->random()->UserID,
                'created_at' => $allocationDate,
                'updated_at' => $allocationDate
            ]);

            $activeAllocations[] = $allocation;
        }

        // Update ward occupancy counts
        $this->updateWardOccupancy();

        $this->command->info('Created ' . count($activeAllocations) . ' active allocations and ' .
                            ($occupiedBedsCount / 2) . ' past allocations.');
    }

    /**
     * Update the CurrentOccupancy for each ward based on occupied beds
     */
    private function updateWardOccupancy()
    {
        // Get all wards
        $wards = \App\Models\Ward::all();

        foreach ($wards as $ward) {
            // Count occupied beds in this ward
            $occupiedCount = $ward->beds()->where('Status', 'occupied')->count();

            // Update ward occupancy
            $ward->CurrentOccupancy = $occupiedCount;
            $ward->save();
        }
    }
}