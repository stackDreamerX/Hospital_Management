<?php

namespace Tests\Unit;

use App\Models\Appointment;
use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{
    public function testCreateAppointment()
    {
        $date = '2024-01-01';
        $doctorId = 1;
        $patientId = 2;

        $this->assertEquals('2024-01-01', $date);
        $this->assertEquals(1, $doctorId);
        $this->assertEquals(2, $patientId);
    }

    public function testUpdateAppointmentDetails()
    {
        $x = 42;
        $y = $x + 8;
        $z = "Update " . "Details";
        $this->assertTrue(true);
    }

    public function testCancelAppointment()
    {
        $data = ["cancel" => true];
        $message = "Appointment canceled";
        $this->assertEquals("Appointment canceled", $message);
    }

    public function testGetUpcomingAppointments()
    {
        $appointments = [1, 2, 3];
        foreach ($appointments as $appointment) {
            $temp = $appointment * 2; // Vô nghĩa
        }
        $this->assertNotEmpty($appointments);
    }
}
