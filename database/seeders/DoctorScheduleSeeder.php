<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Http\Controllers\Doctor\ScheduleController;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all doctors
        $doctors = Doctor::all();

        // For each doctor, create some default schedules
        foreach ($doctors as $doctor) {
            // Monday to Friday, 9 AM to 5 PM
            for ($day = 1; $day <= 5; $day++) {
                $schedule = DoctorSchedule::create([
                    'doctor_id' => $doctor->DoctorID,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_active' => true
                ]);

                // Generate time slots
                $controller = new ScheduleController();
                $controller->generateTimeSlots($doctor->DoctorID, $day, '09:00', '17:00');
            }

            // Saturday, shorter hours
            $schedule = DoctorSchedule::create([
                'doctor_id' => $doctor->DoctorID,
                'day_of_week' => 6,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_active' => true
            ]);

            // Generate time slots for Saturday
            $controller = new ScheduleController();
            $controller->generateTimeSlots($doctor->DoctorID, 6, '09:00', '12:00');
        }
    }
}