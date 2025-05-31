<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin account
        $this->call(AdminSeeder::class);

        // Seed users first (needed for foreign keys)
        $this->call(UserSeeder::class);

        // Seed doctors and patients (linked to users)
        $this->call(DoctorSeeder::class);

        // Seed medicine related tables
        $this->call(MedicineSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(MedicineStockSeeder::class);

        // Seed laboratory types
        $this->call(LaboratoryTypeSeeder::class);

        // Seed treatment types
        $this->call(TreatmentTypeSeeder::class);

        // Seed ward related tables
        $this->call(WardTypeSeeder::class);
        $this->call(WardSeeder::class);
        $this->call(WardBedSeeder::class);

        // Seed doctor schedules and time slots
        $this->call(DoctorScheduleSeeder::class);
        $this->call(DoctorTimeSlotSeeder::class);

        // Seed appointments and ratings
        $this->call(AppointmentSeeder::class);
        $this->call(RatingSeeder::class);

        // Seed treatments
        $this->call(TreatmentSeeder::class);

        // Seed prescriptions
        $this->call(PrescriptionSeeder::class);
        $this->call(PrescriptionDetailSeeder::class);

        // Seed laboratories
        $this->call(LaboratorySeeder::class);
        $this->call(LaboratoryResultSeeder::class);

        // Seed chatbot FAQs
        $this->call(ChatbotFaqSeeder::class);
        $this->call(MedicalEmergencyFaqSeeder::class);
        $this->call(TechFaqSeeder::class);

        // Seed demo users with complete data
        $this->call(DemoUserSeeder::class);
    }
}
