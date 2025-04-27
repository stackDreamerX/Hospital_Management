<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'Main Hospital - 123 Medical Drive, Hanoi',
            'Downtown Clinic - 456 Healthcare Street, Ho Chi Minh City',
            'West Medical Center - 789 Wellness Avenue, Da Nang',
            'North Specialty Clinic - 101 Doctor Lane, Hai Phong',
            'East Medical Building - 202 Treatment Road, Nha Trang'
        ];
        
        $schedules = [
            "Monday: 8:00 AM - 5:00 PM\nTuesday: 8:00 AM - 5:00 PM\nWednesday: 9:00 AM - 6:00 PM\nThursday: 8:00 AM - 5:00 PM\nFriday: 8:00 AM - 4:00 PM\nSaturday: 9:00 AM - 12:00 PM\nSunday: Closed",
            
            "Monday: 9:00 AM - 6:00 PM\nTuesday: 9:00 AM - 6:00 PM\nWednesday: Off\nThursday: 9:00 AM - 6:00 PM\nFriday: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 2:00 PM\nSunday: Closed",
            
            "Monday: 7:30 AM - 4:30 PM\nTuesday: 7:30 AM - 4:30 PM\nWednesday: 7:30 AM - 4:30 PM\nThursday: 7:30 AM - 4:30 PM\nFriday: 7:30 AM - 3:00 PM\nSaturday: Closed\nSunday: Closed",
            
            "Monday: 10:00 AM - 7:00 PM\nTuesday: 10:00 AM - 7:00 PM\nWednesday: 10:00 AM - 7:00 PM\nThursday: Off\nFriday: 10:00 AM - 7:00 PM\nSaturday: 10:00 AM - 3:00 PM\nSunday: Closed",
            
            "Monday: 8:00 AM - 4:00 PM\nTuesday: 8:00 AM - 4:00 PM\nWednesday: 8:00 AM - 4:00 PM\nThursday: 8:00 AM - 4:00 PM\nFriday: 8:00 AM - 4:00 PM\nSaturday: 9:00 AM - 1:00 PM\nSunday: 9:00 AM - 12:00 PM"
        ];
        
        // Update all doctors with random locations and schedules
        $doctors = Doctor::all();
        
        foreach ($doctors as $doctor) {
            $locationIndex = array_rand($locations);
            $scheduleIndex = array_rand($schedules);
            
            $doctor->update([
                'WorkLocation' => $locations[$locationIndex],
                'AvailableHours' => $schedules[$scheduleIndex]
            ]);
        }
    }
}
